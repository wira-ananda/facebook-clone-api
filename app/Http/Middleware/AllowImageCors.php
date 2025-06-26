<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowImageCors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Izinkan hanya untuk file dari folder "storage"
        if ($request->is('storage/*')) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        return $response;
    }
}
