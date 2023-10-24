<?php
namespace App\Http\Repository\CategoryClaimRepository;

use App\Models\CategoryClaim;

class CategoryClaimRepository implements CategoryClaimInterface {
    private $categoryClaim;

    public function __construct(CategoryClaim $categoryClaim)
    {
        $this->categoryClaim = $categoryClaim;
    }

    public function index(): ?object
    {
        $categoryClaims = $this->categoryClaim->all();

        return $categoryClaims;
    }

    public function create($data): object
    {
        $categoryClaim = $this->categoryClaim->create($data);

        return $categoryClaim;

    }

    public function detail($id): ?object
    {
        $categoryClaim = $this->categoryClaim->find($id);

        return $categoryClaim;
    }

    public function update($id, $data): object
    {
        $categoryClaim = $this->categoryClaim->find($id);

        $categoryClaim->update($data);

        return $categoryClaim;
    }

    public function delete($id): object
    {
        $categoryClaim = $this->categoryClaim->find($id);

        $categoryClaim->delete();

        return $categoryClaim;
    }


}
