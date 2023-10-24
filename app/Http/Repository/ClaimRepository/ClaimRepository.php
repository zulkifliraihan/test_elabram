<?php
namespace App\Http\Repository\ClaimRepository;

use App\Models\ClaimRequest;

class ClaimRepository implements ClaimInterface {
    private $claimRequest;

    public function __construct(ClaimRequest $claimRequest)
    {
        $this->claimRequest = $claimRequest;
    }

    public function query(): ?object
    {
        $claimRequests = $this->claimRequest->query();

        return $claimRequests;
    }

    public function index($with = []): ?object
    {
        $claimRequests = $this->claimRequest->with($with)->get();

        return $claimRequests;
    }

    public function create($data): object
    {
        $claimRequest = $this->claimRequest->create($data);

        return $claimRequest;

    }

    public function detail($id, $with = []): ?object
    {
        $claimRequest = $this->claimRequest->with($with)->find($id);

        return $claimRequest;
    }

    public function update($id, $data): object
    {
        $claimRequest = $this->claimRequest->find($id);

        $claimRequest->update($data);

        return $claimRequest;
    }

    public function delete($id): object
    {
        $claimRequest = $this->claimRequest->find($id);

        $claimRequest->delete();

        return $claimRequest;
    }


}
