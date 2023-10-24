<?php

namespace App\Http\Middleware;

use App\Traits\ReturnResponser;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    use ReturnResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('authorization');

        if (!$authorization) {
            return $this->errorServer("Token is required!", 400, "Unauthorized Access");
        }
        try {
            $token = str_replace('Bearer ', '', $authorization);
            $user = JWTAuth::parseToken()->authenticate($token);

        } catch (TokenExpiredException $e) {
            return $this->errorServer("Token has expired!", 400, "Unauthorized Access");

        } catch (TokenInvalidException $e) {
            return $this->errorServer("Token is invalid!", 400, "Unauthorized Access");

        }

        if ($user->_id !== auth()->user()->_id) {
            return $this->errorServer("Token is invalid!", 400, "Unauthorized Access");
        }

        return $next($request);
    }
}
