<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center justify-center min-h-screen font-sans">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-8 text-gray-900 dark:text-white">Laravel Multi-Auth Starter</h1>
            
            <div class="flex justify-center space-x-6">
                <!-- User Section -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 w-64">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">User Panel</h2>
                    <div class="flex flex-col space-y-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-50 transition dark:bg-transparent dark:hover:bg-indigo-900/30">Register</a>
                        @endauth
                    </div>
                </div>

                <!-- Admin Section -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 w-64">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Admin Panel</h2>
                    <div class="flex flex-col space-y-3">
                        @auth('admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition dark:bg-gray-700 dark:hover:bg-gray-600">Dashboard</a>
                        @else
                            <a href="{{ route('admin.login') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition dark:bg-gray-700 dark:hover:bg-gray-600">Admin Login</a>
                            <span class="text-xs text-gray-500 mt-2">Default: admin@admin.com / password</span>
                        @endauth
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-sm text-gray-500">
                <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
            </div>
        </div>
    </body>
</html>
