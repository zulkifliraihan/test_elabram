<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardingRequest;
use App\Http\Services\OnboardingService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    private $onboardingService;

    public function __construct(OnboardingService $onboardingService)
    {
        $this->onboardingService = $onboardingService;

    }

    public function create(OnboardingRequest $onboardingRequest) {
        $data = $onboardingRequest->all();

        try {
            $onboardingService = $this->onboardingService->create($data);
            
            if (!$onboardingService['status']) {
                
                return $this->errorvalidator($onboardingService['errors']);
            }
            return $this->success(
                $onboardingService['response'],
                $onboardingService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }
}
