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
                        ['id' => 1, 'name' => 'English', 'code' => 'en'],
                        ['id' => 2, 'name' => 'Portuguese', 'code' => 'pt'],
                        ['id' => 3, 'name' => 'Spanish', 'code' => 'es']
                    ];
                @endphp

                @foreach($languages as $language)
                    <tr>
                        <td>{{ $language['id'] }}</td>
                        <td>{{ $language['name'] }}</td>
                        <td>
                            <a href="{{ route('lang.switch', ['lang' => $language['code']]) }}" class="btn btn-primary">Select</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

    </table>
</div>
@endsection
