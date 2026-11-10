<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->replace(
            \Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustPilotProxy::class,
        );

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => config('pilot.enabled') ? route('pilot.home') : AppServiceProvider::HOME);

        $middleware->validateCsrfTokens(except: [
            'stripe/webhooks',
        ]);

        $middleware->throttleApi();

        $middleware->prependToPriorityList(
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\RequirePilotAccess::class,
        );

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\RequirePilotAccess::class,
            \App\Http\Middleware\SetLocaleFromSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash(['code']);
    })->create();
