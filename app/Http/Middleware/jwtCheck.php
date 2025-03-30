<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;


class jwtCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            $token = $request->header('Authorization');

            $newtoken = str_replace('Bearer ', '', $token);
            $user = \Tymon\JWTAuth\Facades\JWTAuth::setToken($newtoken)->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }
        return $next($request);
    }
}
