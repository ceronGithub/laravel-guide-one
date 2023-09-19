<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{

    public function requestToken(Request $request)
    {
        $request = $request->all();
        try {
            $response = Http::asForm()->post(url('oauth/token'), [
                'grant_type' => 'client_credentials',
                'client_id' => $request['client_id'],
                'client_secret' => $request['client_secret'],
                'scope' => $request['scope'] ?? 'default',
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                "error" => [$e->getMessage()]
            ]);
        }
    }
}
