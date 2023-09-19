<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Classes\Constants\PaymentOptions;
use App\Classes\Processors\Paynamics;
use App\Classes\Registries\ValidatorRegistry;
use App\Classes\ResponseValidators\PaynamicsResponse;
use App\Models\ProcessorTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProcessorTransactionsController extends Controller
{

    private $validatorRegistry;
    private $requestProcessor;
    private $responseProcessor;
    public function __construct(ValidatorRegistry $validatorRegistry)
    {
        $this->validatorRegistry = $validatorRegistry;
        $this->requestProcessor  = new Paynamics();
        $this->responseProcessor  = new PaynamicsResponse();
    }

    public function store(Request $request)
    {
        try{
            $validator = $this->validatorRegistry->get(PaymentOptions::PAYNAMICS)->validate($request);
            // $transactions = ProcessorTransactions::create($request->validated());

            if($validator->passes()){
                $data = $this->requestProcessor->processRequest($request);

                return response()->json([
                    'status' => 1,
                    'data'   => $data
                ]);
            }else{
                return response()->json([
                    "status" => 0,
                    "data"  => [],
                    "error" => $validator->errors()->all()
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "status" => 0,
                "data"  => [],
                "error" => env('APP_DEBUG') ? $e->getMessage() : 'Failed to process your request. Please try again later.'
            ]);
        }
    }

    public function show(Request $request){
        $response = $this->requestProcessor->queryResponseData($request->request_id);
        //save response
        $this->responseProcessor->processResponse($response);

        try{
            $transaction  = ProcessorTransactions::where('request_id', $request->request_id)->firstOrFail();
            $data         = $this->responseProcessor->getResponseData($request->request_id);

            return response()->json([
                'status' => 1,
                'data'   => $data
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 0,
                'data'   => [],
                'error'  => env('APP_DEBUG') ? $e->getMessage() : 'Missing transaction or response data'
            ]);
        }
    }

    public function update(Request $request){
        $response = $this->requestProcessor->cancelRequest($request->request_id);
        //save response
        // $this->responseProcessor->processResponse($response, $request->request_id);

        try{
            // $transaction  = ProcessorTransactions::where('request_id', $request->request_id)->firstOrFail();
            $cancelData   = $this->requestProcessor->cancelTransaction($request->request_id);
            $data         = $this->responseProcessor->getResponseData($request->request_id);

            return response()->json([
                'status' => 1,
                'data'   => $data
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 0,
                'data'   => [],
                'error'  => env('APP_DEBUG') ? $e->getMessage() : 'Missing transaction or response data'
            ]);
        }
    }
}
