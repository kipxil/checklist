<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->admin) {
            return response()->json(['message' => 'Forbidden: Admin only'], 403);
        }
        return $next($request);
    }
}
