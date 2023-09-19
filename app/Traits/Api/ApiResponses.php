<?php

namespace App\Traits\Api;

trait ApiResponses
{

    public function generateSuccessResponse(String $message, $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function generateFailedResponse(String $message, $exception = null, int $code = 200)
    {
        if ($exception != null)
            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => $exception->getMessage(),
            ], $code);

        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
