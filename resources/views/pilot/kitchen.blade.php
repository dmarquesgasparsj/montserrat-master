@extends('pilot.layout')
@section('title', 'Cozinha')
@section('content')
<form method="get" action="{{ route('pilot.kitchen') }}" class="form-inline mb-4">
    <label for="date" class="mr-2">Dia</label>
    <input class="form-control mr-2" type="date" name="date" id="date" value="{{ $date }}">
    <button type="submit" class="btn btn-primary">Ver refeições</button>
</form>
<table class="table">
    <thead><tr><th>Refeição</th><th>Total previsto</th></tr></thead>
    <tbody>
        @foreach ($totals as $meal => $number)
            <tr><th scope="row">{{ __('messages.meal_'.strtolower($meal)) }}</th><td>{{ $number }}</td></tr>
        @endforeach
    </tbody>
</table>
@if ($diets->isNotEmpty())
    <h2>Contagens registadas para a cozinha</h2>
    <table class="table">
        <thead><tr><th>Refeição</th><th>Vegetariano</th><th>Sem glúten</th><th>Sem lacticínios</th></tr></thead>
        <tbody>
            @foreach ($diets as $diet)
                <tr>
                    <th scope="row">{{ $diet->meal_type }}</th>
                    <td>{{ $diet->vegetarian }}</td>
                    <td>{{ $diet->gluten_free }}</td>
                    <td>{{ $diet->dairy_free }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
