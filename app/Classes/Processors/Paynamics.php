<?php

namespace App\Classes\Processors;

use App\Classes\Constants\GlobeDetails;
use App\Classes\Constants\PaynamicsPaymentUrlIdentifiers;
use App\Classes\Constants\PaynamicsResponseCodes;
use App\Models\ProcessorTransactions;
use App\Traits\ConnectionManager;
use Carbon\Carbon;

class Paynamics{
    use ConnectionManager;

    private $merchantId;
    private $mkey;
    private $username;
    private $password;
    private $requestPrefix;
    private $host;
    private $requestData;
    private $customerData;
    private $postData;
    private $response;
    private $transactionQuery;

    public function __construct(){
        $this->merchantId    = env('PAYNAMICS_MERCHANT_ID', '000000270723BE9F8924');
        $this->mkey          = env('PAYNAMICS_MERCHANT_KEY', '9860ABA557DBF69B50223817918D129E');
        $this->username      = env('PAYNAMICS_MERCHANT_USERNAME', 'globetelecom!*@');
        $this->password      = env('PAYNAMICS_MERCHANT_USERNAME', '1F8hDB7zvNJq');
        $this->requestPrefix = 'GPN';
        $this->host          = env('PAYNAMICS_MERCHANT_HOST', 'https://payin.payserv.net');
    }

    public function processRequest(object $data){

        $this->postData     = $data;
        $this->customerData = GlobeDetails::GLORIETTA;
        $data = [
            'transaction'   => $this->generateTransactionInfo($data),
            'customer_info' => $this->generateCustomerInfo($this->customerData),
            'order_details' => $this->generateOrderInfo($data)
        ];

        if(env('FAIL_TRANSACTION', false)){
            $data['billing_info']  = [];
        }else{
            $data['billing_info']  = $this->customerData['billing_info'];
        }

        $this->requestData = $data;
        $this->response    = $this->sendRequest();
        $this->saveRequest();

        return $this->response;
    }

    public function queryResponseData(string $originalRequestId){
        $requestId = $this->generateRequestId();
        $data = [
            'merchant_id' => $this->merchantId,
            'request_id'  => $requestId,
            'org_trxid2'  => $originalRequestId,
            'signature'   => $this->generateHash($this->merchantId . $requestId . $originalRequestId . $this->mkey)
        ];

        $response = $this->connect(
            $this->username,
            $this->password,
            ['Content-Type: application/json'],
            json_encode($data),
            $this->host .'/paygate/transactions/query'
        );

        $response = json_decode($response, true);
        if($response === null){
            throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_PARSE_RESPONSE]);
        }else{
            return json_encode($response);
        }
    }

    public function cancelRequest(string $originalRequestId){
        $requestId = $this->generateRequestId();
        $data = [
            'merchant_id'      => $this->merchantId,
            'request_id'       => $requestId,
            'org_request_id'   => $originalRequestId,
            'ip_address'       => $_SERVER['REMOTE_ADDR'],
            'notification_url' => env('PAYNAMICS_NOTIFICATION_URL','http://webhook.site/mat'),
            'response_url'     => env('PAYNAMICS_RESPONSE_URL','https://payin.payserv.net/paygate') . '/' . $requestId,
        ];

        $data['signature'] = $this->generateHash($this->merchantId . $data['request_id'] . $originalRequestId . $data['ip_address'] . $data['notification_url'] . $data['response_url'] . $this->mkey);

        $response = $this->connect(
            $this->username,
            $this->password,
            ['Content-Type: application/json'],
            json_encode($data),
            $this->host .'/paygate/transactions/cancel'
        );

        $response = json_decode($response, true);

        if($response === null){
            throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_PARSE_RESPONSE]);
        }else{
            return json_encode($response);
        }
    }

    public function cancelTransaction(string $requestId){
        try{
            ProcessorTransactions::where('request_id', $requestId)->update([
                'response_code' => PaynamicsResponseCodes::EXPIRED,
                'response_message' => 'Transaction Expired'
            ]);
            return true;
        }catch(\Exception $e){
            throw new \Exception('Missing transaction');
        }

    }

    private function saveRequest(){
        try{
            $data = ProcessorTransactions::create([
                'client_id'           => 1,
                'store_id'            => $this->postData['store_id'],
                'machine_id'          => $this->postData['machine_id'],
                'transaction_no'      => $this->postData['transaction_no'],
                'request_id'          => $this->requestData['transaction']['request_id'],
                'customer_info'       => json_encode($this->requestData['customer_info']),
                'billing_info'        => json_encode($this->requestData['billing_info']),
                'transaction_info'    => json_encode($this->requestData['transaction']),
                'order_details'       => json_encode($this->requestData['order_details']),
                'signature'           => $this->requestData['transaction']['signature'],
                'total_amount'        => $this->requestData['order_details']['totalorderamount'],
                'amount_paid'         => $this->requestData['order_details']['totalorderamount'],
                'transaction_payload' => json_encode($this->transactionQuery),
                'payment_method'      => $this->postData['payment_method'],
                'response_code'       => $this->transactionQuery['response_code'],
                'response_message'    => $this->transactionQuery['response_message'],
            ]);

            return true;
        }catch(\Exception $e){
            throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_SAVING_REQUEST]);
        }
    }

    private function sendRequest()
    {
        $response = $this->connect(
            $this->username,
            $this->password,
            ['Content-Type: application/json'],
            json_encode($this->requestData),
            $this->host .'/paygate/transactions/'
        );


        $response = json_decode($response, true);
        $this->transactionQuery = $response;

        if($response === null){
            throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_PARSE_RESPONSE]);
        }else{
            return $this->processResponse($response);
        }

    }

    private function processResponse($response) :array
    {
        $data = [];
        if(isset($response[PaynamicsResponseCodes::RESPONSE_CODE_KEY]) && $response[PaynamicsResponseCodes::RESPONSE_CODE_KEY] == PaynamicsResponseCodes::PENDING){

            $data['expiry_limit'] = $response['expiry_limit'] ?? null;
            $data['request_id']   = $response['request_id'] ?? null;
            $data['response_id']  = $response['response_id'] ?? null;

            if(isset($response[PaynamicsPaymentUrlIdentifiers::GCASH_URL]) && $response[PaynamicsPaymentUrlIdentifiers::GCASH_URL] != ""){
                $data['url'] = $response[PaynamicsPaymentUrlIdentifiers::GCASH_URL];
            }else if(isset($response[PaynamicsPaymentUrlIdentifiers::CC_URL]) && $response[PaynamicsPaymentUrlIdentifiers::CC_URL] != ""){
                $data['url'] = $response[PaynamicsPaymentUrlIdentifiers::CC_URL];
            }else{
                throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_MISSING_PAY_URL]);
            }
            return $data;
        }else{
            throw new \Exception(PaynamicsResponseCodes::API_RESPONSE_CODES[PaynamicsResponseCodes::ERROR_FAILED_TO_OBTAIN_URL]);
        }
    }

    private function generateOrderInfo(object $data) :array
    {
        $orders = [];
        $totalPrice = 0;
        foreach($data->orders as $order){
            $orders[] = [
                'itemname'   => $order['itemname'],
                'quantity'   => $order['quantity'],
                'unitprice'  => $order['unitprice'],
                'totalprice' => $order['quantity'] * $order['unitprice'],
            ];

            $totalPrice = $totalPrice + $order['totalprice'];
        }

        $requestData = [
            'orders'           => $orders,
            'subtotalprice'    => $totalPrice,
            'shippingprice'    => 0,
            'discountamount'   => 0,
            'totalorderamount' => $totalPrice
        ];

        return $requestData;
    }

    private function generateCustomerInfo(array $customerData) :array
    {
        $customerInfo              = $customerData['customer_info'];
        $forHashKey = $customerInfo['fname'] . $customerInfo['lname'] . $customerInfo['mname'] . $customerInfo['email'] . $customerInfo['phone'] . $customerInfo['mobile'] . $customerInfo['dob'] . $this->mkey;
        $customerInfo['signature'] = $this->generateHash($forHashKey);

        return $customerInfo;
    }

    private function generateTransactionInfo(object $data) :array
    {
        $requestId = $this->generateRequestId();
        $requestData = [
            'request_id'                   => $requestId,
            'notification_url'             => env('PAYNAMICS_NOTIFICATION_URL','http://webhook.site/mat'),
            'response_url'                 => env('PAYNAMICS_RESPONSE_URL','https://payin.payserv.net/paygate') . '/' . $requestId,
            'cancel_url'                   => env('PAYNAMICS_CANCEL_URL','https://payin.payserv.net/paygate') . '/' . $requestId,
            'payment_action'               => 'url_link',
            'schedule'                     => '',
            'collection_method'            => 'single_pay',
            'deferred_period'              => '',
            'deferred_time'                => '',
            'dp_balance_info'              => '',
            'amount'                       => $data->total_amount,
            'currency'                     => 'PHP',
            'descriptor_note'              => $this->customerData['customer_info']['fname'] .' '. $this->customerData['customer_info']['lname'],
            'pay_reference'                => '',
            'payment_notification_status'  => '1',
            'payment_notification_channel' => '',
            'expiry_limit'                 => Carbon::now()->addHours(2)->format('Y-m-d H:i:s'),
        ];

        switch($this->postData['payment_method']){
            case "gcash":
                $requestData['pmethod']  = 'wallet';
                $requestData['pchannel'] = 'gc';
            break;
            case "credit_card":
                $requestData['pmethod']  = 'creditcard';
                $requestData['pchannel'] = 'gpap_cc_ph';
            break;
        }

        $forHashKey = $this->merchantId .
            $requestData['request_id'] .
            $requestData['notification_url'] .
            $requestData['response_url'] .
            $requestData['cancel_url'] .
            $requestData['pmethod'] .
            $requestData['payment_action'] .
            $requestData['schedule'] .
            $requestData['collection_method'] .
            $requestData['deferred_period'] .
            $requestData['deferred_time'] .
            $requestData['dp_balance_info'] .
            $requestData['amount'] .
            $requestData['currency'] .
            $requestData['descriptor_note'] .
            $requestData['payment_notification_status'] .
            $requestData['payment_notification_channel'] .
            $this->mkey;


        $requestData['signature'] = $this->generateHash($forHashKey);

        return $requestData;
    }

    private function generateRequestId() :string
    {
        return uniqid($this->requestPrefix);
    }

    private function generateHash($data, string $hash = 'sha512') :string
    {
        return hash($hash, $data);
    }
}
