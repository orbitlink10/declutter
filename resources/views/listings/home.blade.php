@extends('layouts.market', ['title' => 'Declutter Kenya'])

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-12 text-white sm:px-12">
        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-orange-500/30 blur-2xl"></div>
        <div class="absolute -bottom-8 left-12 h-40 w-40 rounded-full bg-amber-400/30 blur-2xl"></div>

        <div class="relative grid gap-8 lg:grid-cols-2 lg:items-center">
            <div>
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Declutter your space. Sell to your community.</h1>
                <p class="mt-4 max-w-xl text-sm text-slate-200 sm:text-base">
                    Declutter Kenya helps you list household items quickly and connect with nearby buyers.
                    Share what you no longer use and give it a second life.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('listings.index') }}" class="rounded-full bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-orange-400">
                        Browse Listings
                    </a>
                    @auth
                        <a href="{{ route('my.listings.create') }}" class="rounded-full border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                            Post an Item
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-full border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                            Create Account
                        </a>
                    @endauth
                </div>
            </div>

            <form action="{{ route('listings.index') }}" method="GET" class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                <label for="home-search" class="text-sm font-semibold text-white">Search by keyword</label>
                <input
                    id="home-search"
                    name="q"
                    type="text"
                    placeholder="e.g. sofa, fridge, bike"
                    class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/70 focus:border-orange-300 focus:ring-orange-300"
                >

                <label for="home-category" class="mt-4 block text-sm font-semibold text-white">Category</label>
                <select id="home-category" name="category" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white focus:border-orange-300 focus:ring-orange-300">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <button class="mt-4 w-full rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-400">
                    Search Listings
                </button>
            </form>
        </div>
    </section>

    <section class="mt-10">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900">Latest Active Listings</h2>
            <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-600">View all</a>
        </div>

        @if ($latestItems->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                No active listings yet.
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($latestItems as $item)
                    <x-listing-card :item="$item" />
                @endforeach
            </div>
        @endif
    </section>
@endsection
