<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::check() || !Auth::user()->is_admin) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Administrator access required'], 403);
        }

        return $next($request);
    }
}
