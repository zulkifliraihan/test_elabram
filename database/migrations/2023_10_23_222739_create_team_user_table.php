<?php

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('teamwork.team_user_table'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->references('id')->on('teams');
            $table->foreignIdFor(User::class)->references('id')->on('users');
            $table->string('role')->default(TeamRole::MEMBER->value);
            $table->timestamps();

            $table->index('team_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
