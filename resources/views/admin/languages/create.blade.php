<!-- create.blade.php for Languages -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Create Language') }}</h1>

    <form action="{{ route('language.change') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="language">{{ __('Language') }}</label>
            <select name="language" id="language" class="form-control" required>
                <option value="">{{ __('Select a Language') }}</option>
                <option value="en">{{ __('English') }}</option>
                <option value="es">{{ __('Spanish') }}</option>
                <option value="pt">{{ __('Portuguese') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Change Language') }}</button>
    </form>
</div>
@endsection