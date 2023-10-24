<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Models\Auth;
use App\Models\Mobil;
use App\Models\Motor;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;

    }

    public function register(RegisterRequest $registerRequest)
    {
        try {
            $authService = $this->authService->register($registerRequest->all());
            
            if (!$authService['status']) {
                
                return $this->errorvalidator($authService['errors']);
            }
            return $this->success(
                $authService['response'],
                $authService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $authService = $this->authService->login($request->all());

            if (!$authService['status']) {
                if ($authService['response'] == 'validation') {
                    return $this->errorvalidator($authService['errors']);
                } else {
                    return $this->errorServer($authService['errors']);
                }
                
            }

            return $this->success(
                $authService['response'],
                $authService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function logout(Request $request)
    {
        try {
            $authService = $this->authService->logout($request);

            return $this->success(
                "logout", null, null, "Successfully logged out"
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

}
