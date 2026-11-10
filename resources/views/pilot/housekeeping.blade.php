@extends('pilot.layout')
@section('title', 'Limpeza')
@section('content')
    <h2>Quartos a limpar</h2>
    @forelse ($rooms as $room)
        <form method="post" action="{{ route('pilot.room-cleaned', $room) }}" class="d-flex align-items-center mb-2">
            @csrf
            <span class="mr-3">{{ $room->location?->name }} · {{ $room->name }}</span>
            <button type="submit" class="btn btn-outline-primary">Marcar como limpo</button>
        </form>
    @empty
        <p>De momento não há quartos a limpar.</p>
    @endforelse
@endsection
