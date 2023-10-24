<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimFormRequest;
use App\Http\Services\ClaimService;
use App\Models\Claim;
use App\Models\Mobil;
use App\Models\Motor;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    private $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    public function index()
    {
        try {
            $claimService = $this->claimService->index();

            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function indexByCurrentUser()
    {
        try {
            $claimService = $this->claimService->indexByCurrentUser();
            
            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function create(ClaimFormRequest $request)
    {
        try {
            $claimService = $this->claimService->create($request->all());

            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function detail($id)
    {
        try {
            $claimService = $this->claimService->detail($id);

            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }

    public function detailByCurrentUser($id)
    {
        try {
            $claimService = $this->claimService->detailByCurrentUser($id);

            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }

    public function update(ClaimFormRequest $request, $id)
    {
        $data = $request->all();

        try {
            $claimService = $this->claimService->update($id, $data);
            
            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            return $this->success(
                $claimService['response'],
                $claimService['data'],
            );
            
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            $claimService = $this->claimService->delete($id);
            
            if (!$claimService['status']) {
                if ($claimService['response'] == 'validation') {
                    return $this->errorvalidator($claimService['errors']);
                } else {
                    return $this->errorServer($claimService['errors']);
                }
            }

            else {
                return $this->success(
                    $claimService['response'],
                    $claimService['data'],
                );
            }
    
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

}
