<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteToTeamRequest;
use App\Http\Requests\TeamRequest;
use App\Http\Services\TeamService;
use App\Mail\TeamInvitationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Mpociot\Teamwork\Events\UserInvitedToTeam;
use Mpociot\Teamwork\Events\UserJoinedTeam;
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
                if ($teamService['response'] == 'validation') {
                    return $this->errorvalidator($teamService['errors']);
                } else {
                    return $this->errorServer($teamService['errors']);
                }
            }
            return $this->success(
                $teamService['response'],
                $teamService['data']
            );
            
    
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }

    }

    public function indexByCurrentUser()
    {
        try {
            $teamService = $this->teamService->indexByCurrentUser();

            if (!$teamService['status']) {
                if ($teamService['response'] == 'validation') {
                    return $this->errorvalidator($teamService['errors']);
                } else {
                    return $this->errorServer($teamService['errors']);
                }
            }

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
                if ($teamService['response'] == 'validation') {
                    return $this->errorvalidator($teamService['errors']);
                } else {
                    return $this->errorServer($teamService['errors']);
                }
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

        try {
            $teamService = $this->teamService->inviteToTeam($data);
            
            if (!$teamService['status']) {
                if ($teamService['response'] == 'validation') {
                    return $this->errorvalidator($teamService['errors']);
                } else {
                    return $this->errorServer($teamService['errors']);
                }
            }

            return $this->success(
                $teamService['response'],
                $teamService['data'],
                null,
                $teamService['message']
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    public function acceptInvitation($token)
    {
        try {
            $teamService = $this->teamService->acceptInvitation($token);
            
            if (!$teamService['status']) {
                if ($teamService['response'] == 'validation') {
                    return $this->errorvalidator($teamService['errors']);
                } else {
                    return $this->errorServer($teamService['errors']);
                }
            }

            return $this->success(
                $teamService['response'],
                $teamService['data'],
                null,
                $teamService['message']
            );

        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }
}
