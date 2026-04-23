<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema de Reportes')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    {{-- CSS --}}
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f2f2f2;
        }

        main {
            min-height: calc(100vh - 140px);
            padding: 20px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Contenido dinámico --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')
</body>
    @stack('scripts')
</html>
