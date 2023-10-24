<?php
namespace App\Http\Services;

use App\Http\Repository\CategoryClaimRepository\CategoryClaimInterface;
use App\Http\Repository\StokRepository\StokInterface;
use App\Models\Mobil;
use App\Models\Motor;

class CategoryClaimService {
    private $categoryClaimInterface;

    public function __construct(CategoryClaimInterface $categoryClaimInterface)
    {
        $this->categoryClaimInterface = $categoryClaimInterface;
    }

    public function index(): array
    {
        $categoryClaim = $this->categoryClaimInterface->index();

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $categoryClaim
        ];

        return $return;
    }

    public function create($data): array
    {
        $return = [];

        $categoryClaim = $this->categoryClaimInterface->create($data);

        $return = [
            'status' => true,
            'response' => 'created',
            'data' => $categoryClaim
        ];

        return $return;
    }

    public function detail($id): array
    {
        $categoryClaim = $this->categoryClaimInterface->detail($id);
        if (!$categoryClaim) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        }
        else {
            $return = [
                'status' => true,
                'response' => 'get',
                'data' => $categoryClaim
            ];
        }

        return $return;
    }

    public function update($id, $data): array
    {
        $return = [];

        $findById = $this->categoryClaimInterface->detail($id);
        if (!$findById) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        }
        else {

            $update = $this->categoryClaimInterface->update($id, $data);
    
            $return = [
                'status' => true,
                'response' => 'updated',
                'data' => $update
            ];
        }

        return $return;
    }

    public function delete($id): array
    {
        $findById = $this->categoryClaimInterface->detail($id);
        if (!$findById) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        }
        else {
            $delete = $this->categoryClaimInterface->delete($id);
            
            $return = [
                'status' => true,
                'response' => 'deleted',
                'data' => $delete
            ];
        }


        return $return;
    }

}
