<?php

namespace App\Console\Commands;

use App\Services\PilotAccounts;
use Illuminate\Console\Command;
use InvalidArgumentException;
use PragmaRX\Google2FA\Google2FA;

class PilotUser extends Command
{
    protected $signature = 'pilot:user {email} {--role=} {--revoke}';
    protected $description = 'Provision or revoke a named account for the Soutelo pilot';

    public function handle(PilotAccounts $accounts): int
    {
        if (! config('pilot.enabled')) {
            $this->error('Ativa o modo piloto para gerir estas contas.');

            return self::FAILURE;
        }

        $email = strtolower(trim($this->argument('email')));
        if ($this->option('revoke')) {
            $accounts->revoke($email);
            $this->info('Conta revogada e sessões anteriores invalidadas.');

            return self::SUCCESS;
        }

        $role = $this->option('role');
        if (! in_array($role, PilotAccounts::ROLES, true)) {
            $this->error('Escolhe admin, reception, kitchen ou housekeeping.');

            return self::FAILURE;
        }

        $interactive = $this->input->isInteractive();
        $password = $interactive ? $this->secret('Palavra-passe (14 a 72 caracteres)') : getenv('SOUTELO_PILOT_ACCOUNT_PASSWORD');
        $secret = $role === 'admin' ? ($interactive ? (new Google2FA)->generateSecretKey() : getenv('SOUTELO_PILOT_TOTP_SECRET')) : null;

        try {
            $accounts->set($email, (string) $password, $role, $secret === false ? null : $secret);
        } catch (InvalidArgumentException $error) {
            $this->error($error->getMessage());

            return self::FAILURE;
        }

        $this->info('Conta configurada e sessões anteriores invalidadas.');
        if ($interactive && $role === 'admin') {
            $this->line('Adiciona esta chave à aplicação de autenticação como chave baseada no tempo. Guarda-a em segurança.');
            $this->line($secret);
        }

        return self::SUCCESS;
    }
}
