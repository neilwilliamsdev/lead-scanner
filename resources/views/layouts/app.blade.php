<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Lead Scanner' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('businesses.index') }}">Lead Scanner</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>