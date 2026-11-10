<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PilotAccounts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PilotLoginController extends Controller
{
    public function show(): View
    {
        return view('auth.pilot');
    }

    public function store(Request $request, PilotAccounts $accounts): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:72'],
            'code' => ['nullable', 'string', 'digits:6'],
        ]);

        $code = $credentials['code'] ?? null;
        $credentials = [
            'email' => strtolower(trim($credentials['email'])),
            'password' => $credentials['password'],
            'provider' => 'soutelo-pilot',
        ];

        if (! Auth::attemptWhen($credentials, function (User $user) use ($accounts, $code): bool {
            return match (PilotAccounts::role($user)) {
                'admin' => $accounts->verifyAdminCode($user, $code),
                null => false,
                default => true,
            };
        })) {
            throw ValidationException::withMessages(['email' => 'Verifica o e-mail, a palavra-passe e o código de autenticação.']);
        }

        $request->session()->regenerate();
        $user = $request->user();
        $request->session()->put('pilot.access_version', $user->pilot_access_version);
        if (PilotAccounts::role($user) === 'admin') {
            $request->session()->put('pilot.mfa_user_id', $user->getKey());
        }

        return redirect()->route('pilot.home');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
