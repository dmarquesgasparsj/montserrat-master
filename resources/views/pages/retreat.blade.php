@extends('template')
@section('content')
<h1>{{ __('messages.retreat_title') }}</h1>
<p>{{ __('messages.retreat_description') }}</p>
<p>
    <a href={{ action([\App\Http\Controllers\RetreatController::class, 'create']) }}>{{ html()->img(asset('images/create.png'), __('messages.retreat_create_alt'))->attribute('title', __('messages.retreat_create_alt')) }}</a></li>
    <a href={{ action([\App\Http\Controllers\RetreatController::class, 'index']) }}>{{ html()->img(asset('images/index.png'), __('messages.retreat_index_alt'))->attribute('title', __('messages.retreat_index_alt')) }}</a></li>
</p>

@stop
