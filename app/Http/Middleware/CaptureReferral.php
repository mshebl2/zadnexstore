<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferral
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('ref')) {
            $ref = strtoupper(trim($request->query('ref')));
            session(['referred_by' => $ref]);
            cookie()->queue('referred_by', $ref, 60 * 24 * 30); // 30 days cookie
        }

        return $next($request);
    }
}

