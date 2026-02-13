<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Declutter Kenya') }}</title>
    <meta name="description" content="@yield('meta_description', 'Declutter Kenya marketplace for local buying and selling.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="bg-gradient-to-r from-amber-700 via-orange-600 to-rose-700 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight">Declutter Kenya</a>
            <div class="flex items-center gap-4 text-sm font-medium">
                <a href="{{ route('listings.index') }}" class="hover:text-amber-100">Browse Listings</a>
                @auth
                    <a href="{{ route('my.listings.create') }}" class="rounded-full bg-white px-4 py-1.5 text-orange-700">Create Listing</a>
                    <a href="{{ route('dashboard') }}" class="hover:text-amber-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-amber-100">Login</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-white px-4 py-1.5 text-orange-700">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-1 list-disc ps-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
