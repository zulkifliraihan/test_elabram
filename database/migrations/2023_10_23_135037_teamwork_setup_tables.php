<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TeamworkSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(config('teamwork.teams_table'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->references('id')->on('users')->nullable();
            $table->string('name');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->dateTime('finish_onboarding_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('teamwork.teams_table'));
    }
}
