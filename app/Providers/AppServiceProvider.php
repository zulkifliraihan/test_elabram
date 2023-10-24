<?php

namespace App\Providers;

use App\Http\Repository\AuthRepository\AuthInterface;
use App\Http\Repository\AuthRepository\AuthRepository;
use App\Http\Repository\CategoryClaimRepository\CategoryClaimInterface;
use App\Http\Repository\CategoryClaimRepository\CategoryClaimRepository;
use App\Http\Repository\ClaimRepository\ClaimInterface;
use App\Http\Repository\ClaimRepository\ClaimRepository;
use App\Http\Repository\TeamRepository\TeamInterface;
use App\Http\Repository\TeamRepository\TeamRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(TeamInterface::class, TeamRepository::class);
        $this->app->bind(CategoryClaimInterface::class, CategoryClaimRepository::class);
        $this->app->bind(ClaimInterface::class, ClaimRepository::class);
        $this->app->bind(TeamInterface::class, TeamRepository::class);
        $this->app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function() {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });
    }
}
