<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Lead Scanner' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-900">
    <header class="border-b border-gray-200 bg-white">
        <nav class="mx-auto flex max-w-7xl items-center gap-6 px-6 py-4">
            <a
                href="{{ route('businesses.index') }}"
                class="text-lg font-semibold text-gray-900 hover:text-blue-600"
            >
                Lead Scanner
            </a>

            <a
                href="{{ route('businesses.index') }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900"
            >
                Businesses
            </a>

            <a
                href="{{ route('candidates.index') }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900"
            >
                Candidates
            </a>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-8">
        @yield('content')
    </main>
</body>
</html>