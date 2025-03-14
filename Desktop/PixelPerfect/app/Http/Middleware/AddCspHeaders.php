<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowUnsafeEval
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Get existing CSP header if any
        $csp = $response->headers->get('Content-Security-Policy');

        if ($csp) {
            // Check if script-src exists and add 'unsafe-eval' to it
            if (strpos($csp, 'script-src') !== false) {
                $csp = preg_replace('/script-src([^;]*);/', 'script-src$1 \'unsafe-eval\';', $csp);
            } else {
                // Add script-src if it doesn't exist
                $csp .= "; script-src 'self' 'unsafe-inline' 'unsafe-eval';";
            }
        } else {
            // Create a new CSP header if none exists
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
