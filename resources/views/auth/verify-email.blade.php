<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-900">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">Verify Your Email Address</h2>
      </div>

      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
        <p class="text-gray-300 text-sm mb-4">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('message') == 'Verification link sent!')
            <div class="mb-4 text-sm font-medium text-green-500">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="flex items-center justify-between mt-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="text-indigo-400 hover:text-indigo-300 font-semibold text-sm">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-gray-300 text-sm">
                    Log Out
                </button>
            </form>
        </div>
      </div>
    </div>
</body>
</html>
