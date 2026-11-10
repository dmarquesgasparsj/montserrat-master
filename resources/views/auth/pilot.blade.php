<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Casa da Torre · Ambiente de teste</title>
    <link rel="stylesheet" href="{{ mix('dist/bundle.css') }}">
</head>
<body>
    <main class="container mt-5" style="max-width: 28rem">
        <h1>Casa da Torre</h1>
        <p>Ambiente de teste com dados fictícios.</p>
        <form method="post" action="{{ route('pilot.login') }}">
            @csrf
            <div class="form-group">
                <label for="email">E-mail</label>
                <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="password">Palavra-passe</label>
                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <div class="form-group">
                <label for="code">Código de autenticação (administração)</label>
                <input class="form-control" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6">
            </div>
            @error('code')
                <p role="alert">{{ $message }}</p>
            @enderror
            @error('email')
                <p role="alert">{{ $message }}</p>
            @enderror
            @error('password')
                <p role="alert">{{ $message }}</p>
            @enderror
            <button class="btn btn-primary" type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>
