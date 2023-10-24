<?php

namespace App\Http\Services;

use App\Http\Repository\AuthRepository\AuthInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthService {
    private $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function register($data): array
    {
        $return = [];
        
        $findByEmail = $this->authInterface->detailByEmail($data['email']);
        
        if ($findByEmail) {
            $return = [
                'status' => false,
                'errors' => [
                    'email' => ['Email has already register.']
                ]
            ];
        }

        else {
            $data['password'] = Hash::make($data['password']);
            $auth = $this->authInterface->create($data);

            $return = [
                'status' => true,
                'response' => 'created',
                'data' => $auth
            ];
        }


        return $return;

    }

    public function login($data): array
    {
        $return = [];

        $findByEmail = $this->authInterface->detailByEmail($data['email']);

        if (!$findByEmail) {
            $return = [
                'status' => false,
                'response' => 'validation',
                'errors' => [
                    'email' => ['Email not found']
                ]
            ];
        }
        else {
            $token = Auth::guard('api')->attempt($data);

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'response' => 'failed-server',
                    'errors' => ['Unauthorized'],
                ], 401);
            }

            $user = Auth::guard('api')->user();

            $resultData = [
                'authorization' => [
                    'type' => 'Bearer',
                    'token' => $token
                ],
                'user' => $user
            ];

            $return = [
                'status' => true,
                'response' => 'created',
                'data' => $resultData
            ];
        }


        return $return;

    }

    public function logout($request)
    {
        Auth::logout();
        JWTAuth::invalidate($request->bearerToken());

        return true;
    }
}
