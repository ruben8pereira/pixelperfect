<?php

namespace App\Http\Middleware;

// app/Http/Middleware/SetLocale.php - Ensure this middleware is registered in the kernel

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set locale from session
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        // Set locale from report language if viewing a report
        if ($request->route('report')) {
            $report = $request->route('report');
            if ($report->language) {
                App::setLocale($report->language);
            }
        }

        // Set locale from language parameter for PDF exports
        if ($request->has('language')) {
            App::setLocale($request->language);
        }

        return $next($request);
    }
}
