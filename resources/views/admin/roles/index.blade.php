@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>
            {{ __('messages.roles_index_title') }}
            @can('create-role')
                <span class="options">
                    <a href={{ action([\App\Http\Controllers\RoleController::class, 'create']) }}>
                        <img src="{{ URL::asset('images/create.png') }}" alt="{{ __('messages.add_item') }}" class="btn btn-light" title="{{ __('messages.add_item') }}">
                    </a>
                </span>
            @endCan
        </h2>
    </div>
    <div class="col-lg-12 my-3 table-responsive-md">
        @if ($roles->isEmpty())
            <div class="col-lg-12 text-center py-5">
                <p>{{ __('messages.no_roles_message') }}</p>
            </div>
        @else
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ __('messages.name') }}</th>
                        <th scope="col">{{ __('messages.display_name') }}</th>
                        <th scope="col">{{ __('messages.description') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td><a href="role/{{ $role->id}}">{{ $role->name }}</a></td>
                        <td>{{ $role->display_name }}</td>
                        <td>{{ $role->description }}</td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        @endif
    </div>
</div>
@stop