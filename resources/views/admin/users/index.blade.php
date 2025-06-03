@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        <h2>
            {{ __('messages.users_index_title') }}
        </h2>
        <p class="lead">{{$users->total()}} {{ __('messages.records') }}</p>

    </div>
    <div class="col-lg-12 my-3 table-responsive-md">
        @if ($users->isEmpty())
            <div class="col-lg-12 text-center py-5">
                <p>{{ __('messages.no_users_message') }}</p>
            </div>
        @else

            <table class="table table-striped table-bordered table-hover">
                {{ $users->links() }}
                <thead>
                    <tr>
                        <th scope="col">{{ __('messages.avatar') }}</th>
                        <th scope="col">{{ __('messages.name') }}</th>
                        <th scope="col">{{ __('messages.email') }}</th>
                        <th scope="col">{{ __('messages.roles') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><img src="{{ $user->avatar }}" alt="Avatar" height="50px" width="50px"></td>
                        <td>
                            <a href = "{{ URL('admin/user/' . $user->id) }}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <ul>
                                @foreach($user->roles as $role)
                                    <li>
                                        <a href = "{{ URL('admin/role/'.$role->id) }}">
                                            {{ $role->name }}
                                        </a>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
    </div>
</div>
@stop
