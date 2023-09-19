<?php
namespace App\Classes\ResponseValidators;

use App\Classes\Constants\PaynamicsResponseCodes;
use App\Classes\Payments\ProcessPayments;
use App\Models\Responses;
use App\Models\ProcessorTransactions;
use App\Traits\DB\TransactionTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
class PaynamicsResponse{
    private $transactionData;
    private $responseData;
    private $merchantId;
    private $mkey;

    use TransactionTable;

    public function __construct(){
        $this->merchantId    = env('PAYNAMICS_MERCHANT_ID', '000000270723BE9F8924');
        $this->mkey          = env('PAYNAMICS_MERCHANT_KEY', '9860ABA557DBF69B50223817918D129E');
    }

    public function processResponse(string $postData = null){
        if(is_null($postData)){
            $postData = file_get_contents('php://input');
        }

        Log::info($postData);
        $this->responseData    = json_decode($postData, true);
        // $this->responseData = json_decode('{"response_code":"GR001","response_advise":"Transaction is approved","response_message":"Transaction Successful","signature":"e987577fd1a52c4c17fdd8fa1dad1226030d4722f51c9259b53ecf0442db0de715999e03ff4a32ec3694b394a675c092704ce2b13bc3c7773167fa6bc01a5d4c","response_id":"26878620418757360","merchant_id":"000000270723BE9F8924","pchannel":"gc","request_id":"GPN64c9515213a54","processor_response_id":"812322987","timestamp":"2023-08-02T02:39:53.000+08:00"}', true);
        $this->transactionData = $this->getTransactionData($this->responseData['request_id']);
        if($this->responseData !== null){
            if($this->validateSignature($this->responseData)){
                if($this->saveResponse($this->responseData)){
                    if($this->updateTransactionData()){
                        if($this->transactionData->response_code !== PaynamicsResponseCodes::PENDING){
                            $this->addPaymentData($this->responseData);
                        }

                        if($this->detectSuccessStatus($this->responseData)){
                            //insert payment data
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        throw new \Exception('Error updating transaction');
                    }
                }else{
                    throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_SAVING_RESPONSE]);
                }
            }else{
                Log::info($this->responseData);
                throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_SIGNATURE_VERIFICATION]);
            }
        }
    }

    private function saveResponse(array $data) :bool
    {
        try{

            $data = Responses::updateOrCreate([
                'request_id' => $data['request_id']
            ],[
                'transaction_id'        => $this->transactionData->id,
                'request_id'            => $data['request_id'],
                'processor_response_id' => $this->transactionData->processor_response_id,
                'response_data'         => json_encode($data),
                'signature'             => $data['signature'],
                'response_code'         => $data['response_code'],
                'response_message'      => $data['response_message'],
                'total_amount'          => $this->transactionData->total_amount,
                'amount_paid'           => $this->transactionData->amount_paid,
            ]);
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    private function updateTransactionData() :bool
    {
        try{
            $this->transactionData->response_code    = $this->responseData['response_code'];
            $this->transactionData->response_message = $this->responseData['response_message'];
            $this->transactionData->save();
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    private function getTransactionData(string $requestId) :object
    {
        return ProcessorTransactions::where('request_id', $requestId)->firstOrFail();
    }

    private function detectSuccessStatus(array $data) :bool
    {
        if($data[PaynamicsResponseCodes::RESPONSE_CODE_KEY] == PaynamicsResponseCodes::SUCCESS){
            return true;
        }
        return false;
    }

    public function showResponse(object $transaction):object
    {
        if($transaction->response_code == PaynamicsResponseCodes::PENDING){
            $transaction->response_message = "Processing payment, please wait...";
        } else if($transaction->response_code == PaynamicsResponseCodes::SUCCESS || $transaction->response_code == PaynamicsResponseCodes::SUCCESS_3DS){
            $transaction->response_message = $transaction->response_message . '. You may now close this page and check the screen on the vending machine.';
        } else {
            $transaction->response_message = 'Transaction failed. Please close this page and try another payment method on the vending machine.';
        }

        return $transaction;
    }

    public function getResponseData(string $requestId) :array
    {
        $response     = Responses::where('request_id', $requestId)->first();
        $transaction  = ProcessorTransactions::where('request_id', $requestId)->firstOrFail();
        $localTransaction = null;
        $responseData = [];


        $localTransaction  = $this->getPurchaseOrderData($transaction->transaction_no, true);

        $data = [
            'code'    => $transaction->response_code,
            'message' => $transaction->response_message,
            'details' => $responseData,
        ];

        if(!is_null($response)){
            $responseData = json_decode($response->response_data);
            $responseData = [
                'transaction_no'       => $transaction->transaction_no, //reference no
                'amount_paid'          => $transaction->amount_paid,
                'payment_gateway_txid' => $responseData->response_id,
                'tx_date'              => date('Y-m-d', strtotime($response->updated_at)),
                'tx_time'              => date('H:i:s', strtotime($response->updated_at)),
                'payment_details'      => $localTransaction->payment_details,
                'response_code'        => $response->response_code,
                'response_message'     => $response->response_message,
                'store_code'           => $localTransaction->store_code, //sloc number
                'product_code'         => $localTransaction->product_code, //matcode
                'product_serial'       => $localTransaction->product_serial,
                'request_id'           => $requestId //payment reference no
            ];

            $data['details'] = $responseData;
        }

        if($transaction->response_code == PaynamicsResponseCodes::SUCCESS || $transaction->response_code == PaynamicsResponseCodes::SUCCESS_3DS){
            $data = [
                'code' => PaynamicsResponseCodes::SUCCESS_RESPONSE,
                'message' => PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::SUCCESS_RESPONSE],
                'details' => $data,
            ];
        } else if($transaction->response_code == PaynamicsResponseCodes::PENDING){
            $data = [
                'code' => PaynamicsResponseCodes::PENDING_RESPONSE,
                'message' => PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::PENDING_RESPONSE],
                'details' => $data,
            ];
        }
        return $data;
    }

    private function validateSignature(array $data) :bool
    {
        $data['gateway_id']                  = $data['gateway_id'] ?? '';
        $data['response_advise']             = $data['response_advise'] ?? '';
        $data['processor_response_authcode'] = $data['processor_response_authcode'] ?? '';
        $data['processor_response_id']       = $data['processor_response_id'] ?? '';
        $data['pay_reference']               = $data['pay_reference'] ?? '';

        $forSign = $this->merchantId  .
            $data['request_id'] .
            $data['response_id'] .
            $data['gateway_id'] .
            $data['response_code'] .
            $data['response_message'] .
            $data['response_advise'].
            $data['timestamp'] .
            $data['processor_response_id'] .
            $data['processor_response_authcode'] .
            $data['pay_reference'] .
            $this->mkey;

        $forSign = hash('sha512', $forSign);

        if($forSign == $data['signature']){
            return true;
        }

        return false;
    }

    private function addPaymentData(array $data) :bool
    {
        $response     = Responses::where('request_id', $data['request_id'])->first();
        $responseData = json_decode($response->response_data);
        try{
            $requestData = [
                'purchase_order_id'           => $this->transactionData->transaction_no,
                'terminal_date'               => date('Y-m-d', strtotime($response->updated_at)),
                'terminal_time'               => date('H:i:s', strtotime($response->updated_at)),
                'terminal_message_status'     => $response->response_message,
                'terminal_merchant'           => 'Paynamics',
                'terminal_paid_amount'        => $this->transactionData->amount_paid,
                'terminal_appr_code'          => $responseData->processor_response_authcode ?? '',
                'terminal_tid'                => $responseData->response_id,
                'terminal_mid'                => null,
                'terminal_payment_mode'       => $this->transactionData->payment_method,
                'created_at'                  => $response->updated_at
            ];

            $requestData = new Request($requestData);
            $paymentData = new ProcessPayments();
            $paymentData = $paymentData->process($requestData);
            return $paymentData['status'] ?? false;
        }catch(\Exception $e){
            return false;
        }
    }

}
