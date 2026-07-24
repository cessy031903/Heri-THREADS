<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // In local dev, APP_URL and the hostname actually typed into the browser
        // (localhost vs 127.0.0.1) are easy to mismatch — CSP treats them as
        // different origins and silently blocks media-src 'self' either way.
        $mediaSrc = app()->environment('local')
            ? "media-src 'self' http://localhost:* http://127.0.0.1:*"
            : "media-src 'self'";

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://github.com",
            "img-src 'self' data: blob:",
            $mediaSrc,
            "connect-src 'self'",
            "frame-src https://www.youtube.com",
            "object-src 'none'",
            "base-uri 'self'",
        ]));

        return $response;
    }
}
