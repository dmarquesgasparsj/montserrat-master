<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use PragmaRX\Google2FA\Google2FA;

class PilotAccounts
{
    public const ROLES = ['admin', 'reception', 'kitchen', 'housekeeping'];

    public static function role(User $user): ?string
    {
        if ($user->provider !== 'soutelo-pilot') {
            return null;
        }

        if (Gate::forUser($user)->allows('superuser')) {
            return 'admin';
        }

        foreach (['reception', 'kitchen', 'housekeeping'] as $role) {
            if ($user->hasRole('pilot-'.$role)) {
                return $role;
            }
        }

        return null;
    }

    public function set(string $email, string $password, string $role, ?string $secret = null): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 14 || strlen($password) > 72
            || ! in_array($role, self::ROLES, true)
            || ($role === 'admin' && ! preg_match('/\A[A-Z2-7]{32}\z/', $secret ?? ''))) {
            throw new InvalidArgumentException('E-mail, palavra-passe, perfil ou chave de autenticação inválidos.');
        }

        DB::transaction(function () use ($email, $password, $role, $secret) {
            $user = User::query()->where('provider', 'soutelo-pilot')
                ->where('provider_id', $email)->lockForUpdate()->first()
                ?? new User(['email' => $email, 'provider' => 'soutelo-pilot', 'provider_id' => $email]);

            $roleRecord = $this->roleRecord($role);

            $user->forceFill([
                'name' => $user->name ?? $email,
                'username' => $email,
                'avatar' => '',
                'password' => Hash::make($password),
                'pilot_totp_secret' => $role === 'admin' ? $secret : null,
                'pilot_totp_step' => null,
                'pilot_access_version' => $user->pilot_access_version + 1,
            ])->save();
            $user->roles()->sync([$roleRecord->id]);
            $this->record('account_provisioned', null, $user->id, $role);
        });
    }

    public function assignStaffRole(User $actor, User $target, string $role): void
    {
        DB::transaction(function () use ($actor, $target, $role) {
            $target = User::whereKey($target->id)->lockForUpdate()->firstOrFail();
            $previousRole = self::role($target);
            abort_unless($previousRole && $previousRole !== 'admin' && $role !== 'admin', 403);
            if ($previousRole === $role) {
                return;
            }

            $target->roles()->sync([$this->roleRecord($role)->id]);
            $target->forceFill(['pilot_access_version' => $target->pilot_access_version + 1])->save();
            $this->record('staff_role_changed', $actor->id, $target->id, $role);
        });
    }

    private function roleRecord(string $role): Role
    {
        if ($role === 'admin') {
            return Permission::whereName('superuser')->firstOrFail()->roles()->firstOrFail();
        }

        $record = Role::unguarded(fn () => Role::firstOrCreate(['name' => 'pilot-'.$role]));
        if ($role === 'reception') {
            $permissions = Permission::whereIn('name', config('pilot.reception_permissions'))->pluck('id');
            $record->permissions()->sync($permissions);
        }

        return $record;
    }

    public function revoke(string $email): void
    {
        DB::transaction(function () use ($email) {
            $user = User::where('provider', 'soutelo-pilot')->where('provider_id', $email)
                ->lockForUpdate()->firstOrFail();

            $user->roles()->detach();
            $user->forceFill([
                'pilot_access_version' => $user->pilot_access_version + 1,
                'pilot_totp_secret' => null,
                'pilot_totp_step' => null,
            ])->save();
            $this->record('account_revoked', null, $user->id);
        });
    }

    public function verifyAdminCode(User $user, ?string $code): bool
    {
        if (! preg_match('/\A[0-9]{6}\z/', $code ?? '')) {
            return false;
        }

        return DB::transaction(function () use ($user, $code) {
            $locked = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if (! $locked->pilot_totp_secret || $locked->pilot_access_version !== $user->pilot_access_version) {
                return false;
            }

            $step = (new Google2FA)->verifyKeyNewer($locked->pilot_totp_secret, $code, $locked->pilot_totp_step ?? 0);
            if ($step === false) {
                return false;
            }

            $locked->forceFill(['pilot_totp_step' => $step])->save();

            return true;
        });
    }

    public function record(string $action, ?int $actor = null, ?int $target = null, ?string $role = null): void
    {
        DB::table('pilot_security_events')->insert([
            'action' => $action,
            'actor_user_id' => $actor,
            'target_user_id' => $target,
            'role' => $role,
            'created_at' => now(),
        ]);
    }
}
