<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    <title>@yield('title', 'My App')</title>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white shadow p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800"><a href="/">My Laravel App</a></h1>
        <nav>
            <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline mr-4">Tasks</a>
        </nav>
    </div>
</header>

<main class="flex-grow container mx-auto p-4 sm:p-6 lg:p-10">
    @yield('content')
</main>

<footer class="bg-white shadow p-4 text-center text-sm text-gray-500">
    &copy; {{ date('Y') }} My Laravel App
</footer>

</body>
</html>
