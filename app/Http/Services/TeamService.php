<?php
namespace App\Http\Services;

use App\Http\Repository\TeamRepository\TeamInterface;
use App\Http\Repository\StokRepository\StokInterface;
use App\Models\Mobil;
use App\Models\Motor;
use Illuminate\Support\Facades\Auth;

class TeamService {
    private $teamInterface;

    public function __construct(TeamInterface $teamInterface)
    {
        $this->teamInterface = $teamInterface;
    }

    public function index(): array
    {
        $teams = $this->teamInterface->index();

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $teams
        ];

        return $return;
    }

    public function indexByCurrentUser() : array
    {
        /** @var App\Models\User */
        $user = Auth::user();

        $teams = $user->teams;

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $teams
        ];
    }

    public function changeCurrentTeamUser($teamId) : array
    {
        /** @var App\Models\User */
        $user = Auth::user();

        $checkTeam = $this->teamInterface->detail($teamId);

        if (! $checkTeam) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        } else {
            
            $user->update([
                'current_team_id' => $teamId
            ]);

            $return = [
                'status' => true,
                'response' => 'get',
                'data' => $user
            ];
        }
        


        return $return;
    }


    public function create($data): array
    {
        $return = [];

        $teams = $this->teamInterface->create($data);

        $return = [
            'status' => true,
            'response' => 'created',
            'data' => $teams
        ];

        return $return;
    }

    public function detail($id): array
    {
        $teams = $this->teamInterface->detail($id);
        if (!$teams) {
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
                'data' => $teams
            ];
        }

        return $return;
    }

    public function update($id, $data): array
    {
        $return = [];

        $findById = $this->teamInterface->detail($id);
        if (!$findById) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        }
        else {

            $update = $this->teamInterface->update($id, $data);
    
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
        $findById = $this->teamInterface->detail($id);
        if (!$findById) {
            $return = [
                'status' => false,
                'message' => 'ID Not Found',
                'errors' => null 
            ];
        }
        else {
            $delete = $this->teamInterface->delete($id);
            
            $return = [
                'status' => true,
                'response' => 'deleted',
                'data' => $delete
            ];
        }


        return $return;
    }

}
