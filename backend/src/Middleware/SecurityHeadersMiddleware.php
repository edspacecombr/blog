<?php

namespace App\Middleware;

/**
 * F7.7: Security headers middleware
 * Adds HSTS, CSP, and other security headers
 */
class SecurityHeadersMiddleware
{
    public function handle(callable $next)
    {
        $response = $next();

        // F7.7: HSTS - Force HTTPS
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload', false);

        // CSP - Content Security Policy
        header(
            "Content-Security-Policy: default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' pagead2.googlesyndication.com; "
            . "style-src 'self' 'unsafe-inline'; "
            . "img-src 'self' data: https:; "
            . "font-src 'self' data:; "
            . "connect-src 'self' pagead2.googlesyndication.com; "
            . "frame-ancestors 'none'",
            false
        );

        // X-Content-Type-Options - Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff', false);

        // X-Frame-Options - Prevent clickjacking
        header('X-Frame-Options: DENY', false);

        // X-XSS-Protection - Old XSS protection
        header('X-XSS-Protection: 1; mode=block', false);

        // Referrer-Policy
        header('Referrer-Policy: strict-origin-when-cross-origin', false);

        // Permissions-Policy (formerly Feature-Policy)
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()', false);

        return $response;
    }
}
