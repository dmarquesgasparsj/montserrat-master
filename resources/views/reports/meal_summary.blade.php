@extends('report')
@section('content')
<div class="meal-summary">
    <h2>{{ __('messages.meal_summary') }} · {{ $retreat->idnumber }} · {{ $retreat->title }}</h2>
    <table width="100%">
        <thead>
            <tr>
                <th>{{ __('messages.meal_date') }}</th>
                @foreach (array_keys(config('polanco.meal_times')) as $meal)
                    <th>{{ __('messages.meal_'.strtolower($meal)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $day => $meals)
                <tr data-day="{{ $day }}">
                    <td>{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</td>
                    @foreach ($meals as $meal => $count)
                        <td align="center" data-meal="{{ $meal }}">{{ $count }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($dietary)
        <h3>{{ __('messages.meal_dietary_notes') }}</h3>
        <ul>
            @foreach ($dietary as $note => $count)
                <li>{{ $note }} · {{ $count }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
