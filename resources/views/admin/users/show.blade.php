@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
           <h1>
               {{ __('messages.user_details_for') }} {{$user->name}} (<a href="mailto:{{$user->email}}">{{ $user->email }}</a>)
               <img src="{{$user->avatar}}" alt="Avatar" height="50px" width="50px">
            </h1>
    </div>
    <table class="table table-bordered table-striped table-hover table-responsive-md">
        <caption><h2>{{ __('messages.roles_and_permissions') }}</h2</caption>
        <th>{{ __('messages.role_name') }}</th>
        <th>{{ __('messages.permissions') }}</th>
            @foreach($user->roles as $role)
                <tr>
                    <td>
                        <a href = "{{ URL('admin/role/'.$role->id) }}">
                            {{ $role->name }}
                        </a>
                    </td>
                    <td>
                        @foreach($role->permissions as $permission)
                                @if ($loop->last)
                                    <a href = {{ URL('admin/permission/' . $permission->id) }}>
                                        {{ $permission->name }}
                                    </a>
                                @else
                                    <a href = {{ URL('admin/permission/' . $permission->id) }}>
                                        {{ $permission->name }}
                                    </a>,
                                @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
    </table>
</div>
@stop
