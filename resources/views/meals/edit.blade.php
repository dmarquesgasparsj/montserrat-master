@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>Edit meal</h1>
    </div>
    <div class="col-lg-12">
        {{ html()->form('PUT', route('meal.update', $meal))->open() }}
            {{ html()->hidden('id')->value($meal->id) }}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4">
                        {{ html()->label('Retreat', 'retreat_id') }}
                        {{ html()->select('retreat_id')->options($retreats)->value($meal->retreat_id)->class('form-control') }}
                    </div>
                    <div class="col-lg-4">
                        {{ html()->label('Date', 'meal_date') }}
                        {{ html()->date('meal_date', $meal->meal_date)->class('form-control') }}
                    </div>
                    <div class="col-lg-4">
                        {{ html()->label('Type', 'meal_type') }}
                        {{ html()->text('meal_type')->value($meal->meal_type)->class('form-control') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        {{ html()->label('Vegetarian', 'vegetarian_count') }}
                        {{ html()->number('vegetarian_count')->value($meal->vegetarian_count)->class('form-control') }}
                    </div>
                    <div class="col-lg-4">
                        {{ html()->label('Gluten Free', 'gluten_free_count') }}
                        {{ html()->number('gluten_free_count')->value($meal->gluten_free_count)->class('form-control') }}
                    </div>
                    <div class="col-lg-4">
                        {{ html()->label('Dairy Free', 'dairy_free_count') }}
                        {{ html()->number('dairy_free_count')->value($meal->dairy_free_count)->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-lg-12">
                    {{ html()->submit('Update meal')->class('btn btn-outline-dark') }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@stop
