<?php
namespace App\Http\Services;

use App\Enums\TeamRole;
use App\Enums\UserRole;
use App\Http\Repository\ClaimRepository\ClaimInterface;
use App\Http\Repository\StokRepository\StokInterface;
use App\Models\Mobil;
use App\Models\Motor;
use Illuminate\Support\Facades\Auth;

class ClaimService {
    private $claimInterface, $user, $currentRoleTeam;

    public function __construct(ClaimInterface $claimInterface)
    {
        $this->claimInterface = $claimInterface;
         /** @var \App\Models\User */
        $this->user = Auth::user();

        $this->currentRoleTeam = $this->user->teams()->firstWhere('team_id', $this->user->current_team_id)?->pivot->role;

    }

    public function index($inteam): array
    {
        $with = ['category', 'team', 'requester', 'reviewer', 'currency'];

        if ($this->user->role == UserRole::SUPERADMIN->value) {
            $claim = $this->claimInterface->index($with);
        }
        else {
            if ($inteam && $this->currentRoleTeam != TeamRole::MEMBER->value) {
                $claim = $this->claimInterface->query()
                    ->with($with)
                    ->where('team_id', $this->user->current_team_id)
                    ->get();
            }
            else {
                $claim = $this->claimInterface->query()
                    ->with($with)
                    ->where('request_user_id', $this->user->id)
                    ->where('team_id', $this->user->current_team_id)
                    ->get();
            }
        }


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

        if($this->user->current_team_id != $claim->team_id && $this->user->role != UserRole::SUPERADMIN->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different current team, change team first.']
            ];

            return $return;
        }

        if(
            $this->user->role == UserRole::MEMBER->value &&
            $this->currentRoleTeam == TeamRole::MEMBER->value
        ) {
            
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different requested user with current user.']
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

        if($this->user->role != UserRole::SUPERADMIN->value) {
            unset($data['request_user_id']);
        }

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

        if($this->user->id != $findById->request_user_id && $this->user->role != UserRole::SUPERADMIN->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different requested user with current user.']
            ];

            return $return;
        }

        if($this->user->current_team_id != $findById->team_id && $this->user->role != UserRole::SUPERADMIN->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different current team, change team first.']
            ];

            return $return;
        }

        if($this->currentRoleTeam == TeamRole::MEMBER->value) {
            unset(
                $data['review_user_id'],
                $data['status'],
                $data['reason']
            );
        }

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

        if($this->user->id != $findById->request_user_id && $this->user->role != UserRole::SUPERADMIN->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different requested user with current user.']
            ];

            return $return;
        }

        if($this->user->current_team_id != $findById->team_id && $this->user->role != UserRole::SUPERADMIN->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different current team, change team first.']
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

    public function review($id, $data): array
    {
        $review = $this->claimInterface->detail($id);

        if(!$review) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];

            return $return;
        }

        if($this->user->id != $review->request_user_id && $this->currentRoleTeam == TeamRole::MEMBER->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Restricted! Different role for review.']
            ];
    
            return $return;
        }
    
        if($this->user->current_team_id != $review->team_id && $this->currentRoleTeam == TeamRole::MEMBER->value) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['Different current team, change team first.']
            ];
    
            return $return;
        }
    
        $update = $this->claimInterface->update($id, $data);
    
        $return = [
            'status' => true,
            'response' => 'updated',
            'message' => 'Successfully update status to ' . $data['status'] , 
            'data' => $update
        ];
        
        return $return;

    }

}
