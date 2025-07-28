@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>
            {{ __('messages.locations_index_title') }}
            @can('create-location')
            <span class="options">
                <a href={{ action([\App\Http\Controllers\LocationController::class, 'create']) }}>
                    <img src="{{ URL::asset('images/create.png') }}" alt="{{ __('messages.add_location_button') }}" class="btn btn-light" title="{{ __('messages.add_location_button') }}">
                </a>
            </span>
            @endCan
        </h2>
    </div>
        <div class="col-md-2 col-lg-12">
            <select class="custom-select" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <option value="">{{ __('messages.filter_by_type_option') }}</option>
                <option value="{{url('admin/location')}}">{{ __('messages.all_locations_option') }}</option>
                @foreach($location_types as $key=>$type)
                <option value="{{url('admin/location/type/'.$key)}}">{{$type}}</option>
                @endForeach
            </select>
        </div>

    <div class="col-lg-12 my-3 table-responsive-md">
        @if ($locations->isEmpty())
        <div class="col-lg-12 text-center py-5">
            <p>{{ __('messages.no_locations_message') }}</p>
        </div>
        @else
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">{{ __('messages.name') }}</th>
                    <th scope="col">{{ __('messages.description') }}</th>
                    <th scope="col">{{ __('messages.type') }}</th>
                    <th scope="col">{{ __('messages.occupancy') }}</th>
                    <th scope="col">{{ __('messages.notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $location)
                <tr>
                    <td><a href="{{URL('admin/location/'.$location->id)}}">{{ $location->name }}</a></td>
                    <td>{{ $location->description }}</td>
                    <td>{{ $location->type }}</td>
                    <td>{{ $location->occupancy }}</td>
                    <td>{{ $location->notes }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        @endif
    </div>
</div>
@stop
