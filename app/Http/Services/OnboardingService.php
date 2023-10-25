<?php

namespace App\Http\Services;
use App\Enums\TeamRole;
use App\Http\Repository\TeamRepository\TeamInterface;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OnboardingService {

    public function create($data): array {
        /** @var \App\Models\User */
        $user = Auth::user();
        
        $data['owner_id'] = $user->id;
        $data['finish_onboarding_at'] = Carbon::now();

        $team = Team::create($data);

        $user->attachTeam($team, [
            'role' => TeamRole::OWNER->value,
        ]);
        
        $return = [
            'status' => true,
            'response' => 'created',
            'data' => $user
        ];

        return $return;
    }
}