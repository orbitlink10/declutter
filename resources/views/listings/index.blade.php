@extends('layouts.market', ['title' => 'Browse Listings - Declutter Kenya'])

@section('content')
    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
        <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-7 shadow-[0_16px_30px_-20px_rgba(15,23,42,0.5)]">
            <h1 class="text-[2.1rem] font-black tracking-tight text-slate-900">Filters</h1>

            <form method="GET" action="{{ route('listings.index') }}" class="mt-7 space-y-6">
                <div>
                    <label for="q" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Keyword</label>
                    <input
                        id="q"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Search title or description"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-[1.1rem] text-slate-800 placeholder:text-slate-400 focus:border-slate-400 focus:ring-slate-300"
                    >
                </div>

                <div>
                    <label for="category" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Category</label>
                    <div class="relative">
                        <select id="category" name="category" class="w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-3.5 pr-12 text-[1.1rem] text-slate-900 focus:border-slate-400 focus:ring-slate-300">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-6 w-6 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="county" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">County</label>
                    <div class="relative">
                        <select id="county" name="county" class="w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-3.5 pr-12 text-[1.1rem] text-slate-900 focus:border-slate-400 focus:ring-slate-300">
                            <option value="">All counties</option>
                            @foreach ($counties as $county)
                                <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-6 w-6 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="condition" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Condition</label>
                    <div class="relative">
                        <select id="condition" name="condition" class="w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-3.5 pr-12 text-[1.1rem] text-slate-900 focus:border-slate-400 focus:ring-slate-300">
                            <option value="">Any condition</option>
                            @foreach (\App\Models\Item::CONDITIONS as $condition)
                                <option value="{{ $condition }}" @selected(($filters['condition'] ?? '') === $condition)>{{ str_replace('_', ' ', ucfirst($condition)) }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-6 w-6 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="min_price" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Min KES</label>
                        <input id="min_price" name="min_price" type="number" min="0" value="{{ $filters['min_price'] ?? '' }}" class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-[1.1rem] text-slate-800 placeholder:text-slate-400 focus:border-slate-400 focus:ring-slate-300">
                    </div>
                    <div>
                        <label for="max_price" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Max KES</label>
                        <input id="max_price" name="max_price" type="number" min="0" value="{{ $filters['max_price'] ?? '' }}" class="w-full rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-[1.1rem] text-slate-800 placeholder:text-slate-400 focus:border-slate-400 focus:ring-slate-300">
                    </div>
                </div>

                <div>
                    <label for="sort" class="mb-2 block text-[1.05rem] font-semibold text-slate-700">Sort</label>
                    <div class="relative">
                        <select id="sort" name="sort" class="w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-3.5 pr-12 text-[1.1rem] text-slate-900 focus:border-slate-400 focus:ring-slate-300">
                            <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                            <option value="price_low_high" @selected(($filters['sort'] ?? '') === 'price_low_high')>Price: Low to High</option>
                            <option value="price_high_low" @selected(($filters['sort'] ?? '') === 'price_high_low')>Price: High to Low</option>
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-6 w-6 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Apply</button>
                    <a href="{{ route('listings.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </aside>

        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Available Listings</h2>
                <span class="text-sm text-slate-500">{{ $items->total() }} result(s)</span>
            </div>

            @if ($items->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
                    No active listings matched your filters.
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($items as $item)
                        <x-listing-card :item="$item" />
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $items->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
