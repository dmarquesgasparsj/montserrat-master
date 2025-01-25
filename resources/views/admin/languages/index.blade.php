<!-- index.blade.php for Languages -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Languages</h1>

    <a href="{{ route('languages.create') }}" class="btn btn-primary mb-3">Add New Language</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>a
            </tr>
        </thead>
        <tbody>
            @foreach($languages as $language)
                <tr>
                    <td>{{ $language->id }}</td>
                    <td>{{ $language->name }}</td>
                    <td>
                        <a href="{{ route('languages.show', $language->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('languages.edit', $language->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('languages.destroy', $language->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
