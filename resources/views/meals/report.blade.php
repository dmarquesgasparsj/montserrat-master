@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>Meals for {{ $retreat->title }}</h2>
    </div>
    <div class="col-lg-12 my-3 table-responsive-md">
        @if ($meals->isEmpty())
            <div class="col-lg-12 text-center py-5">
                <p>No meals recorded.</p>
            </div>
        @else
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Type</th>
                        <th scope="col">Vegetarian</th>
                        <th scope="col">Gluten Free</th>
                        <th scope="col">Dairy Free</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meals as $meal)
                    <tr>
                        <td>{{ $meal->meal_date->format('Y-m-d') }}</td>
                        <td>{{ $meal->meal_type }}</td>
                        <td>{{ $meal->vegetarian_count }}</td>
                        <td>{{ $meal->gluten_free_count }}</td>
                        <td>{{ $meal->dairy_free_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@stop
