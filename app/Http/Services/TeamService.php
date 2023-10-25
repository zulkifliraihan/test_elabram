<?php
namespace App\Http\Services;

use App\Enums\TeamRole;
use App\Http\Repository\TeamRepository\TeamInterface;
use App\Http\Repository\StokRepository\StokInterface;
use App\Mail\TeamInvitationMail;
use App\Models\Mobil;
use App\Models\Motor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

class TeamService {
    private $teamInterface, $user;


    public function __construct(TeamInterface $teamInterface)
    {
        $this->teamInterface = $teamInterface;

        /** @var \App\Models\User */
        $this->user = Auth::user();
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

    public function indexByCurrentUser() : array
    {

        $teams = $this->user->teams;

        $return = [
            'status' => true,
            'response' => 'get',
            'data' => $teams
        ];

        return $return;
    }

    public function changeCurrentTeamUser($teamId) : array
    {

        $checkTeam = $this->teamInterface->detail($teamId);

        if (! $checkTeam) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['ID Not Found.']
            ];
        } else {
            
            $this->user->update([
                'current_team_id' => $teamId
            ]);

            $return = [
                'status' => true,
                'response' => 'updated',
                'data' => $this->user
            ];
        }
        


        return $return;
    }

    public function inviteToTeam($data)
    {
        /** @var \CategoryClaim */
        $team = $this->user->currentTeam;

        if($team->owner_id != $this->user->id) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['invite team must be a owner or admin']
            ];

            return $return;
        }

        $invite = new TeamInvite();
        $invite->user_id = $this->user->getKey();
        $invite->team_id = $team->id;
        $invite->type = 'invite';
        $invite->email = $data['email'];
        $invite->role = $data['role'];
        $invite->accept_token = md5(uniqid(microtime()));
        $invite->deny_token = md5(uniqid(microtime()));
        $invite->save();
        
        event(new UserInvitedToTeam($invite));

        Mail::to($data['email'])->send(new TeamInvitationMail($team, $invite));

        $return = [
            'status' => true,
            'response' => null,
            'message' => 'Successfully invited',
            'data' => $invite
        ];

        return $return;
    }

    public function acceptInvitation($token)
    {
        $invitation = TeamInvite::where('accept_token', $token)->first(); // Returns a TeamworkInvite model or null

        if (!$invitation) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['invalid accept token.']
            ];

            return $return;
        }

        $user = User::where('email', $invitation->email)->first();
        
        if (!$user) {
            $user = User::create([
                'current_team_id' => $invitation->team_id,
                'name'=> $invitation->email,
                'email'=> $invitation->email,
            ]);
        }

        $user->attachTeam($invitation->team, ['role' => $invitation->role]);
        $invitation->delete();

        $return = [
            'status' => true,
            'response' => "invited",
            'message' => 'Successfully invited',
            'data' => $user
        ];
        return $return;
    }
}
