<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAuthenticationDebug
{
    public function handle(Request $request, Closure $next)
    {
        // Log authentication details for debugging
        Log::info('API Request Debug', [
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => [
                'authorization' => $request->header('Authorization') ? 'Bearer ***' : 'None',
                'accept' => $request->header('Accept'),
                'content-type' => $request->header('Content-Type'),
            ],
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'is_authenticated' => auth()->check(),
            'guard' => auth()->getDefaultDriver(),
        ]);

        return $next($request);
    }
}
