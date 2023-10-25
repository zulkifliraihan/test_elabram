<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryClaimRequest;
use App\Http\Services\CategoryClaimService;
use App\Models\CategoryClaim;
use Illuminate\Http\Request;

class CategoryClaimController extends Controller
{
    private $categoryClaimService;

    public function __construct(CategoryClaimService $categoryClaimService)
    {
        $this->categoryClaimService = $categoryClaimService;
    }

    public function index()
    {
        try {
            $categoryClaimService = $this->categoryClaimService->index();

            return $this->success(
                $categoryClaimService['response'],
                $categoryClaimService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function create(CategoryClaimRequest $request)
    {
        try {
            $categoryClaimService = $this->categoryClaimService->create($request->all());

            return $this->success(
                $categoryClaimService['response'],
                $categoryClaimService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function detail($id)
    {
        try {
            $categoryClaimService = $this->categoryClaimService->detail($id);

            if (!$categoryClaimService['status']) {
                return $this->errorvalidator($categoryClaimService['errors'], $categoryClaimService['message'], 400);
            }
            return $this->success(
                $categoryClaimService['response'],
                $categoryClaimService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }

    public function update(CategoryClaimRequest $request, $id)
    {
        $data = $request->all();

        try {
            $categoryClaimService = $this->categoryClaimService->update($id, $data);
            
            if (!$categoryClaimService['status']) {
                return $this->errorvalidator($categoryClaimService['errors'], $categoryClaimService['message'], 400);
            }
            else {
                return $this->success(
                    $categoryClaimService['response'],
                    $categoryClaimService['data'],
                );
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            $categoryClaimService = $this->categoryClaimService->delete($id);
            
            if (!$categoryClaimService['status']) {
                return $this->errorvalidator($categoryClaimService['errors'], $categoryClaimService['message'], 400);
            }
            else {
                return $this->success(
                    $categoryClaimService['response'],
                    $categoryClaimService['data'],
                );
            }
    
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

}
