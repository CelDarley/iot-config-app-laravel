<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IoT Config App') }} - @yield('title', 'Configuração de Dispositivos')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <!-- Additional styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        @if(auth()->check())
            <!-- Navigation -->
            <nav class="bg-white shadow-lg border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <h1 class="text-xl font-bold text-primary-dark">📱 IoT Config App</h1>
                            </div>
                            <div class="hidden md:ml-6 md:flex md:space-x-8">
                                <a href="{{ route('home') }}" class="text-primary-dark hover:text-primary border-b-2 border-transparent hover:border-primary px-1 pt-1 text-sm font-medium">
                                    Home
                                </a>
                                <a href="{{ route('device.config') }}" class="text-gray-500 hover:text-primary border-b-2 border-transparent hover:border-primary px-1 pt-1 text-sm font-medium">
                                    Configurar Dispositivo
                                </a>
                                <a href="{{ route('about') }}" class="text-gray-500 hover:text-primary border-b-2 border-transparent hover:border-primary px-1 pt-1 text-sm font-medium">
                                    Sobre
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="text-sm text-gray-500">Olá, {{ auth()->user()->name ?? 'Usuário' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>

