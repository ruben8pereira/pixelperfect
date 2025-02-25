<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, $locale)
    {
        // Validate the locale
        $validLocales = config('app.available_locales', ['en']);

        if (!in_array($locale, $validLocales)) {
            $locale = config('app.locale');
        }

        // Set session locale
        session(['locale' => $locale]);

        // Redirect back
        return redirect()->back();
    }
}
