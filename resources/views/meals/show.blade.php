@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>Meal details</h2>
    </div>
    <div class="col-lg-12 my-3">
        <table class="table table-bordered">
            <tr>
                <th>Retreat</th>
                <td>{{ $meal->retreat->title ?? '' }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $meal->meal_date->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $meal->meal_type }}</td>
            </tr>
            <tr>
                <th>Vegetarian</th>
                <td>{{ $meal->vegetarian_count }}</td>
            </tr>
            <tr>
                <th>Gluten Free</th>
                <td>{{ $meal->gluten_free_count }}</td>
            </tr>
            <tr>
                <th>Dairy Free</th>
                <td>{{ $meal->dairy_free_count }}</td>
            </tr>
        </table>
    </div>
</div>
@stop
