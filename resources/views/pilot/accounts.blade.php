@extends('pilot.layout')
@section('title', 'Contas e registo de segurança')
@section('content')
@php
    $profiles = ['reception' => 'Receção', 'kitchen' => 'Cozinha', 'housekeeping' => 'Limpeza'];
    $actions = [
        'account_provisioned' => 'Conta configurada',
        'account_revoked' => 'Conta revogada',
        'staff_role_changed' => 'Perfil alterado',
        'reservations_exported' => 'Reservas exportadas',
    ];
@endphp
<p><a href="{{ route('pilot.home') }}">Voltar à aplicação</a></p>
<table class="table">
    <thead><tr><th>Conta</th><th>Perfil</th></tr></thead>
    <tbody>
        @foreach ($users as $user)
            @php $role = \App\Services\PilotAccounts::role($user); @endphp
            <tr>
                <td>{{ $user->email }}</td>
                <td>
                    @if (isset($profiles[$role]))
                        <form method="post" action="{{ route('pilot.staff-role', $user) }}" data-account-email="{{ $user->email }}">
                            @csrf
                            <select name="role" aria-label="Perfil de {{ $user->email }}">
                                @foreach ($profiles as $value => $label)
                                    <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="submit">Guardar</button>
                        </form>
                    @else
                        {{ $role === 'admin' ? 'Administração' : 'Revogada' }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<h2>Últimas operações</h2>
<table class="table">
    <thead><tr><th>Data</th><th>Operação</th><th>Autor</th><th>Conta afetada</th><th>Perfil</th></tr></thead>
    <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{ $event->created_at }}</td>
                <td>{{ $actions[$event->action] ?? $event->action }}</td>
                <td>{{ $event->actor_email ?? 'Operador do servidor' }}</td>
                <td>{{ $event->target_email ?? '—' }}</td>
                <td>{{ $profiles[$event->role] ?? $event->role ?? '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
