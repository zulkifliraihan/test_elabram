<?php
namespace App\Http\Services;

use App\Http\Repository\ClaimRepository\ClaimInterface;
use App\Http\Repository\StokRepository\StokInterface;
use App\Models\Mobil;
use App\Models\Motor;
use Illuminate\Support\Facades\Auth;

class ClaimService {
    private $claimInterface;
    private $user;

    public function __construct(ClaimInterface $claimInterface)
    {
        $this->claimInterface = $claimInterface;
         /** @var App\Models\User */
        $this->user = Auth::user();
    }

    public function index(): array
    {
        $with = ['category', 'team', 'requester', 'reviewer', 'currency'];
        $claim = $this->claimInterface->index($with);

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $claim
        ];

        return $return;
    }

    public function indexByCurrentUser(): array
    {

        $with = ['category', 'team', 'requester', 'reviewer', 'currency'];
        $claim = $this->claimInterface->query()
            ->with($with)
            ->where('request_user_id', $this->user->id)
            ->where('team_id', $this->user->current_team_id)
            ->get();

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $claim
        ];

        return $return;
    }

    public function create($data): array
    {
        $return = [];


        $data['request_user_id'] = $this->user->id;
        $data['status'] = 'submission';
        $data['team_id'] = $this->user->current_team_id;

        $claim = $this->claimInterface->create($data);

        foreach ($data['file_support'] as $key => $value) {
            $claim->addMedia($value)->toMediaCollection('claimrequest-file_support');
        }

        $return = [
            'status' => true,
            'response' => 'created',
            'data' => $claim
        ];

        return $return;
    }

    public function detail($id): array
    {
        $with = ['category', 'team', 'requester', 'reviewer', 'currency'];

        $claim = $this->claimInterface->detail($id, $with);
        if (!$claim) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];

            return $return;

        }

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $claim
        ];

        return $return;
    }

    public function detailByCurrentUser($id): array
    {
        $with = ['category', 'team', 'requester', 'reviewer', 'currency'];

        $claim = $this->claimInterface->detail($id, $with);
        if (!$claim) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];
            return $return;
        }

        if ($claim->request_user_id != $this->user->id) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['current user not same with request user'] 
            ];

            return $return;
        }

        if ($claim->team_id != $this->user->current_team_id) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['different current team with claim request team user'] 
            ];

            return $return;
        }

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $claim
        ];


        return $return;
    }

    public function update($id, $data): array
    {
        $return = [];
        $findById = $this->claimInterface->detail($id);

        if (!$findById) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];

            return $return;

        }
        else {
            $update = $this->claimInterface->update($id, $data);
    
            if (array_key_exists('file_support', $data)) {
                $update->clearMediaCollection('claimrequest-file_support');

                foreach ($data['file_support'] as $key => $value) {
                    $update->addMedia($value)->toMediaCollection('claimrequest-file_support');
                }
            }
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
        $findById = $this->claimInterface->detail($id);
        if (!$findById) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];

            return $return;

        }
        $delete = $this->claimInterface->delete($id);
        
        $return = [
            'status' => true,
            'response' => 'deleted',
            'data' => $delete
        ];



        return $return;
    }

}
