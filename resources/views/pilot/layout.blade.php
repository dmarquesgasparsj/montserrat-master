<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('dist/bundle.css') }}">
</head>
<body>
    <main class="container mt-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>@yield('title')</h1>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary" type="submit">Sair</button>
            </form>
        </header>
        @yield('content')
    </main>
</body>
</html>
