<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteToTeamRequest;
use App\Http\Requests\TeamRequest;
use App\Http\Services\TeamService;
use App\Models\Team;
use App\Models\Mobil;
use App\Models\Motor;
use App\Models\User;
use App\Notifications\TeamInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Mail;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

class TeamController extends Controller
{
    private $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        try {
            $teamService = $this->teamService->index();

            return $this->success(
                $teamService['response'],
                $teamService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function create(TeamRequest $request)
    {
        try {
            $teamService = $this->teamService->create($request->all());

            return $this->success(
                $teamService['response'],
                $teamService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function detail($id)
    {
        try {
            $teamService = $this->teamService->detail($id);

            if (!$teamService['status']) {
                return $this->errorvalidator($teamService['errors'], $teamService['message'], 400);
            }
            return $this->success(
                $teamService['response'],
                $teamService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());

        }
    }

    public function update(TeamRequest $request, $id)
    {
        $data = $request->all();

        try {
            $teamService = $this->teamService->update($id, $data);
            
            if (!$teamService['status']) {
                return $this->errorvalidator($teamService['errors'], $teamService['message'], 400);
            }
            else {
                return $this->success(
                    $teamService['response'],
                    $teamService['data'],
                );
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            $teamService = $this->teamService->delete($id);
            
            if (!$teamService['status']) {
                return $this->errorvalidator($teamService['errors'], $teamService['message'], 400);
            }
            else {
                return $this->success(
                    $teamService['response'],
                    $teamService['data'],
                );
            }
    
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function indexByCurrentUser()
    {
        try {
            $teamService = $this->teamService->indexByCurrentUser();

            return $this->success(
                $teamService['response'],
                $teamService['data'],
            );
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function changeCurrentTeamUser($teamId)
    {
        try {
            $teamService = $this->teamService->changeCurrentTeamUser($teamId);

            if (!$teamService['status']) {
                return $this->errorvalidator($teamService['errors'], $teamService['message'], 400);
            }
            return $this->success(
                $teamService['response'],
                $teamService['data'],
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function inviteToTeam(InviteToTeamRequest $request)
    {
        $data = $request->all();

        /** @var App\Models\User */
        $currentUser = Auth::user();
        
        // /** @var App\Models\Team */
        $team = $currentUser->currentTeam;

        if($team->owner_id != $currentUser->id) {
            $return = [
                'status' => false,
                'response' => 'server',
                'message' => null,
                'errors' => ['invite team must be a owner or admin']
            ];

            return $return;
        }

        $user = User::where('email', $data['email'])->first();

        // Teamwork::inviteToTeam($user ? $user : $data['email'], $team, function (TeamInvite $invitation) use ($user, $data) {
        //     // dd($invitation);
        //     // $invitation->update(['role' => $data['role']]);

        //     // if ($client) {
        //     //     $client->notify(new WorkspaceInvitationNotification($invitation));
        //     // } else {
        //     //     Notification::route('mail', $email)
        //     //         ->notify(new WorkspaceInvitationNotification($invitation));
        //     // }
        // });

        $success = null;

        $invite = new TeamInvite();
        $invite->user_id = $currentUser->getKey();
        $invite->team_id = $team->id;
        $invite->type = 'invite';
        $invite->email = $data['email'];
        $invite->role = $data['role'];
        $invite->accept_token = md5(uniqid(microtime()));
        $invite->deny_token = md5(uniqid(microtime()));
        $invite->save();
        // dd(!is_null($success));
        // if (!is_null($success)) {
            event(new UserInvitedToTeam($invite));
            // $success($invite);

            Notification::route('mail', $data['email'])
                    ->notify(new TeamInvitationMail($invite, $team));

            $recipient = 'recipient@example.com';
            $message = 'This is the plain text email message.';

            Mail::to($data['email'])->raw($message);

            dd("On");
        // }

        dd("Success", $invite);
    }
}
