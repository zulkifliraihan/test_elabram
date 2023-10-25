<?php

namespace Database\Seeders;

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name"=> "Zulkifli Raihan",
            "email"=> "zuran2907@gmail.com",        
            "password"=> Hash::make(123123123),
        ]);

        $team = Team::create([
            "owner_id" => $user->id,
            "name" => "PT. C Channel Indonesia",
            "address" => "Kuningan, Jakarta Selatan",
            "phone" => "085155030102",
            "finish_onboarding_at" => Carbon::now(),
        ]);

        $team2 = Team::create([
            "owner_id" => $user->id,
            "name" => "PT. ZuRan International",
            "address" => "Pondok Kelapa, Jakarta Timur",
            "phone" => "085691166309",
            "finish_onboarding_at" => Carbon::now(),
        ]);

        $user->attachTeam($team, [
            'role' => TeamRole::OWNER->value,
        ]);
        $user->attachTeam($team2, [
            'role' => TeamRole::OWNER->value,
        ]);
    }
}
