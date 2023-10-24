<?php

namespace App\Http\Middleware;

use App\Traits\ReturnResponser;
use Closure;
use Illuminate\Support\Facades\Auth;

class DeterminateCurrentTeam
{
    use ReturnResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var App\Models\User */
        $user = Auth::user();

        if (!$user->currentTeam) {
            $teams = $user->teams;
            // dd($teams[0]->id);
            // Jika teams nya null
            if ($teams->count() == 0) {
                $errors = [
                    "User doesn't have company",
                    "contact your HR for invite you in team company or Create company in " . route('internal.onboarding') 
                ];
                return $this->errorServer($errors);
            }

            // Update current team
            $user->update([
                'current_team_id' => $teams[0]->id,
            ]);
            
        }

        return $next($request);
    }
}
