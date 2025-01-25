<!-- create.blade.php for Languages -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Language</h1>

    <form action="{{ route('languages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Language</label>
            <select name="name" id="name" class="form-control" required>
                <option value="">Select a Language</option>
                <option value="English">English</option>
                <option value="Spanish">Spanish</option>
                <option value="Portuguese">Portuguese</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection