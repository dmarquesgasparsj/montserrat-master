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
        $locale = Session::get('applocale', config('app.locale'));
        $availableLocales = ['en', 'es', 'pt']; // Supported locales

        if (in_array($locale, $availableLocales)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
