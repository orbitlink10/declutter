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
            <h2 class="text-xl font-bold text-slate-900">Browse Categories</h2>
            <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-600">See all listings</a>
        </div>

        @if ($categories->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                Categories will appear here once added.
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                @foreach ($categories as $category)
                    <a
                        href="{{ route('listings.index', ['category' => $category->slug]) }}"
                        class="group flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 last:border-b-0 hover:bg-slate-50"
                    >
                        <div class="flex items-center gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-lg font-bold text-slate-600">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </span>

                            <div>
                                <p class="text-xl font-semibold leading-tight text-slate-800">{{ $category->name }}</p>
                                <p class="mt-1 text-sm font-medium text-slate-500">{{ number_format($category->active_items_count) }} ads</p>
                            </div>
                        </div>

                        <span class="text-3xl font-light leading-none text-slate-400 transition group-hover:text-orange-500" aria-hidden="true">&rsaquo;</span>
                    </a>
                @endforeach
            </div>
        @endif
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
