<?php
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () use ($router) {

    // $url = 'https://eightforty840.files.wordpress.com/2019/10/lumen.png';
    // $user = User::create([
    //     'name'=> 'Zulkifli Raihan',
    //     'email'=> 'zuran2907@gmail.com',
    //     'password'=> '123123',
    // ]);
    // $user->addMediaFromUrl($url)
    // ->toMediaCollection('user-logo');

    return response()->json([ 
        'status' => 'success',
        'message'=> 'success'
     ]);
});

$router->get('/public/country/all', ['as' => 'public.country.all', 'uses' => 'CountryController@index']);
$router->get('/public/country/currency', ['as' => 'public.country.currency', 'uses' => 'CountryController@currency']);


$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});

$router->group([
    'middleware'=> ['auth', 'auth.jwt'],
    'prefix' => 'internal'
], function () use ($router) {
    $router->post('onboarding', ['as' => 'internal.onboarding', 'uses' => 'OnboardingController@create']);
    $router->get('/invitation/accept-team/{token}', ['as' => 'internal.invitation.accept', 'uses' => 'TeamController@acceptInvitation']);

    $router->group([
        'middleware'=> ['currentteam'],
    ], function () use ($router) {

        $router->group([
            'prefix' => 'teams'
        ], function () use ($router) {
            $router->get('/', ['as' => 'internal.teams.index', 'uses' => 'TeamController@index']);
            $router->get('/with-me', ['as' => 'internal.teams.index', 'uses' => 'TeamController@index']);
            $router->post('/', ['as' => 'internal.teams.create', 'uses' => 'TeamController@create']);
            $router->get('/{id}', ['as' => 'internal.teams.detail', 'uses' => 'TeamController@detail']);
            $router->put('/{id}', ['as' => 'internal.teams.update', 'uses' => 'TeamController@update']);
            $router->delete('/{id}', ['as' => 'internal.teams.delete', 'uses' => 'TeamController@delete']);
        }); 

        $router->group([
            'prefix' => 'category-claim'
        ], function () use ($router) {
            $router->get('/', ['as' => 'internal.category.index', 'uses' => 'CategoryClaimController@index']);
            $router->post('/', ['as' => 'internal.category.create', 'uses' => 'CategoryClaimController@create']);
            $router->get('/{id}', ['as' => 'internal.category.detail', 'uses' => 'CategoryClaimController@detail']);
            $router->put('/{id}', ['as' => 'internal.category.update', 'uses' => 'CategoryClaimController@update']);
            $router->delete('/{id}', ['as' => 'internal.category.delete', 'uses' => 'CategoryClaimController@delete']);
        }); 

        $router->group([
            'prefix' => 'claim-request'
        ], function () use ($router) {
            $router->get('/', ['as' => 'internal.claim.index', 'uses' => 'ClaimController@index']);
            $router->post('/', ['as' => 'internal.claim.create', 'uses' => 'ClaimController@create']);
            $router->get('/{id}', ['as' => 'internal.claim.detail', 'uses' => 'ClaimController@detail']);
            $router->put('/{id}', ['as' => 'internal.claim.update', 'uses' => 'ClaimController@update']);
            $router->delete('/{id}', ['as' => 'internal.claim.delete', 'uses' => 'ClaimController@delete']);
            
        }); 

        // Member / Employee
        $router->group([
            'prefix' => 'member'
        ], function () use ($router) {
            $router->get('/my-teams', ['as' => 'internal.teams.index', 'uses' => 'TeamController@indexByCurrentUser']);
            $router->post('/change-team/{teamId}', ['as' => 'internal.teams.index', 'uses' => 'TeamController@changeCurrentTeamUser']);
            $router->post('/invite-to-team', ['as' => 'internal.teams.index', 'uses' => 'TeamController@inviteToTeam']);
            
            $router->get('/category-claim', ['as' => 'internal.category.index', 'uses' => 'CategoryClaimController@index']);
            
            $router->group([
                'prefix' => 'claim'
            ], function () use ($router) {

                $router->get('/', ['as' => 'internal.claim.index', 'uses' => 'ClaimController@index']);
                $router->post('/', ['as' => 'internal.claim.create', 'uses' => 'ClaimController@create']);
                $router->get('/{id}', ['as' => 'internal.claim.detail', 'uses' => 'ClaimController@detail']);
                $router->put('/{id}', ['as' => 'internal.claim.update', 'uses' => 'ClaimController@update']);
                $router->delete('/{id}', ['as' => 'internal.claim.delete', 'uses' => 'ClaimController@delete']);
                $router->post('/{id}/review', ['as' => 'internal.claim.review', 'uses' => 'ClaimController@review']);

            }); 
        }); 
    }); 

}); 