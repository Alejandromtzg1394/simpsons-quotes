<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpsons Quotes App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .simpsons-yellow { background-color: #FED90F; }
        .simpsons-blue { background-color: #70D1FE; }
        .simpsons-red { color: #D40C1A; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="simpsons-yellow p-4 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-center simpsons-red">
                🍩 Simpsons Quotes App
            </h1>
            <nav class="mt-4 flex justify-center space-x-6">
                <a href="{{ route('home') }}" class="font-semibold hover:text-red-600">Inicio</a>
                <a href="{{ route('search') }}" class="font-semibold hover:text-red-600">Buscar</a>
                <a href="{{ route('favorites') }}" class="font-semibold hover:text-red-600">Favoritos</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="simpsons-blue p-4 mt-8 text-center">
        <p>Hecho con ❤️ y Laravel 11</p>
    </footer>
</body>
</html>