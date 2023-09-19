<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Requests\User\RegisterRequest;
use App\Traits\Api\ApiResponses;
use App\Traits\DB\UserTable;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponses, UserTable;

    public function register(RegisterRequest $request)
    {
        $request->validated();

        try {
            $user = $request->all();
            $user["password"] = Hash::make($user["password"]);
            $data = $this->registerUser($user);

            return $this->generateSuccessResponse(
                'Successfully created a ' . $request->first_name . ' ' . $request->last_name . '!',
                $data
            );
        } catch (\Exception $e) {
            return $this->generateFailedResponse(
                'Failed to create user',
                $e
            );
        }
    }
}
