<!-- index.blade.php for Languages -->
@extends('template')
@section('content')

<div class="container">
    <h1>Languages</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Language</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                @php
                    $languages = [
                        ['id' => 1, 'name' => 'English'],
                        ['id' => 2, 'name' => 'Portuguese'],
                        ['id' => 3, 'name' => 'Spanish']
                    ];
                @endphp

                @foreach($languages as $language)
                    <tr>
                        <td>{{ $language['id'] }}</td>
                        <td>{{ $language['name'] }}</td>
                        <td>
                            <button type="submit" name="selected_language" value="{{ $language['id'] }}" class="btn btn-primary">Select</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

    </table>
</div>
@endsection
