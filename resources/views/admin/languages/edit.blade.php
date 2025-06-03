<!-- edit.blade.php for Languages -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Language</h1>

    <form action="{{ route('language.update', $language->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Language Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $language->name }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
