<?php

use App\Models\CategoryClaim;
use App\Models\Country;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('claim_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CategoryClaim::class, 'category_id')->references('id')->on('category_claims');
            $table->foreignIdFor(User::class, 'request_user_id')->references('id')->on('users');
            $table->foreignIdFor(Team::class, 'team_id')->references('id')->on('teams');
            $table->foreignIdFor(User::class,'review_user_id')->nullable()->references('id')->on('users');
            $table->foreignIdFor(Country::class, 'currency_id');
            $table->string('status')->nullable();
            $table->string('reason')->nullable();
            $table->date('date');
            $table->string('amount');
            $table->text('description');
            $table->timestamps();

            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_requests');
    }
};
