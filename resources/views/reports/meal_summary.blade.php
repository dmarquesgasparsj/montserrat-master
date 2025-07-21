@extends('report')
@section('content')
<div class="meal-summary">
    <h2>Meal Summary for Retreat #{{ $retreat->idnumber }} - {{ $retreat->title }}</h2>
    <table width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Snack</th>
                <th>Dinner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($days as $day => $meals)
            <tr>
                <td>{{ \Carbon\Carbon::parse($day)->format('F j, Y') }}</td>
                <td align="center">{{ $meals['Breakfast'] }}</td>
                <td align="center">{{ $meals['Lunch'] }}</td>
                <td align="center">{{ $meals['Snack'] }}</td>
                <td align="center">{{ $meals['Dinner'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($dietary))
    <h3>Dietary Preferences</h3>
    <ul>
        @foreach($dietary as $pref => $count)
        <li>{{ $pref }} - {{ $count }}</li>
        @endforeach
    </ul>
    @endif
</div>
@endsection
