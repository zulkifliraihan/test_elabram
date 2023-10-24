<?php

namespace App\Http\Middleware;

use App\Traits\ReturnResponser;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    use ReturnResponser;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd("Hello", $guard,  $this->auth->guard('api')->guest(), $this->auth->guard('api')->check());
        if (!$this->auth->guard('api')->check()) {
            return $this->errorServer(null, 401, 'Unauthorized');
        }

        return $next($request);
    }
}
