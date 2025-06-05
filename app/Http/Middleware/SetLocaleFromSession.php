<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session; // It's good practice to import Session facade

class SetLocaleFromSession
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
        if (Session::has('applocale')) {
            $locale = Session::get('applocale');
            $availableLocales = ['en', 'es', 'pt']; // Supported locales

            if (in_array($locale, $availableLocales)) {
                App::setLocale($locale);
            } else {
                // Optionally, set to a default/fallback if the session locale is invalid
                // App::setLocale(config('app.fallback_locale', 'en'));
                // For now, let's stick to only setting if valid,
                // otherwise default Laravel behavior (from config) will apply.
            }
        } else {
            // Optionally, set to a default/fallback if no session locale is set
            // App::setLocale(config('app.fallback_locale', 'en'));
        }

        return $next($request);
    }
}
