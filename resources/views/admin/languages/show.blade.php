<!-- show.blade.php for Languages -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Language Details</h1>

    <div class="card">
        <div class="card-header">
            <h2>{{ $language->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $language->id }}</p>
            <p><strong>Name:</strong> {{ $language->name }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('languages.edit', $language->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('languages.destroy', $language->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
            <a href="{{ route('languages.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection