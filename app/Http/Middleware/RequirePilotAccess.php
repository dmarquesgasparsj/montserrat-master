<?php

namespace App\Http\Middleware;

use App\Services\PilotAccounts;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePilotAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('pilot.enabled')) {
            return $next($request);
        }

        abort_if(! $request->isSecure() && (
            str_starts_with((string) config('app.url'), 'https://')
            || ! in_array($request->getHost(), ['127.0.0.1', 'localhost', '[::1]'], true)
        ), 403);

        if ($request->routeIs('login', 'pilot.login')) {
            return $this->privateResponse($next($request));
        }

        $user = $request->user();
        if (! $user) {
            abort_if($request->expectsJson(), 401);

            return $this->privateResponse(redirect()->route('login'));
        }

        if ($request->routeIs('logout')) {
            return $this->privateResponse($next($request));
        }

        $role = PilotAccounts::role($user);
        abort_unless($role && $request->session()->get('pilot.access_version') === $user->pilot_access_version, 403);
        abort_if($role === 'admin' && $request->session()->get('pilot.mfa_user_id') !== $user->getKey(), 403);

        $allowed = match ($role) {
            'admin' => array_merge(config('pilot.reception_routes'), config('pilot.admin_routes'), [
                'pilot.kitchen', 'pilot.housekeeping', 'pilot.room-cleaned',
            ]),
            'reception' => config('pilot.reception_routes'),
            'kitchen' => ['pilot.kitchen'],
            'housekeeping' => ['pilot.housekeeping', 'pilot.room-cleaned'],
        };

        abort_unless($request->routeIs('pilot.home', 'pilot.entry', ...$allowed), 403);

        return $this->privateResponse($next($request));
    }

    private function privateResponse(Response $response): Response
    {
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }
}
