<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Forbidden</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f9fafb; }
        .card { text-align: center; padding: 2rem; max-width: 28rem; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; }
        p { color: #6b7280; margin-bottom: 1.5rem; }
        a { color: #2563eb; text-decoration: none; font-weight: 500; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h1>403 – Forbidden</h1>
        <p>{{ $exception?->getMessage() ?: 'You do not have permission to access this page.' }}</p>
        @auth
            @if(request()->is('admin*'))
                <a href="{{ route('admin.index') }}">Back to dashboard</a>
            @else
                <a href="{{ url('/') }}">Go to homepage</a>
            @endif
        @else
            <a href="{{ route('admin.login') }}">Log in</a>
        @endauth
    </div>
</body>
</html>
