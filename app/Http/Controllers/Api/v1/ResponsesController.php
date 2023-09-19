<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Classes\Constants\PaynamicsResponseCodes;
use App\Classes\ResponseValidators\PaynamicsResponse;
use App\Models\ProcessorTransactions;
use Illuminate\Http\Request;

class ResponsesController extends Controller
{

    private $responseProcessor;

    public function __construct(){
        $this->responseProcessor = new PaynamicsResponse();
    }

    public function index($requestId){
        $transaction = ProcessorTransactions::where('request_id', $requestId)->firstOrFail();
        $transaction = $this->responseProcessor->showResponse($transaction);

        return view('transaction-status', compact('transaction', 'requestId'));
    }

    public function store(Request $request){
        $data = $this->responseProcessor->processResponse();
    }

    public function show(Request $request){
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
}
