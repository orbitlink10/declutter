@extends('layouts.market', ['title' => 'Browse Listings - Declutter Kenya'])

@section('content')
    @php
        $labelClass = 'mb-1.5 block text-sm font-semibold text-slate-700';
        $inputClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200';
        $selectClass = $inputClass.' appearance-none pr-11';
    @endphp
    <div class="grid gap-6 lg:grid-cols-[330px_1fr]">
        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h1 class="text-xl font-bold text-slate-900">Filters</h1>
                <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Clear all</a>
            </div>

            <form method="GET" action="{{ route('listings.index') }}" class="mt-5 space-y-5">
                <div>
                    <label for="q" class="{{ $labelClass }}">Keyword</label>
                    <input
                        id="q"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Search title or description"
                        class="{{ $inputClass }}"
                    >
                </div>

                <div>
                    <label for="category" class="{{ $labelClass }}">Category</label>
                    <div class="relative">
                        <select id="category" name="category" class="{{ $selectClass }}">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="county" class="{{ $labelClass }}">County</label>
                    <div class="relative">
                        <select id="county" name="county" class="{{ $selectClass }}">
                            <option value="">All counties</option>
                            @foreach ($counties as $county)
                                <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="condition" class="{{ $labelClass }}">Condition</label>
                    <div class="relative">
                        <select id="condition" name="condition" class="{{ $selectClass }}">
                            <option value="">Any condition</option>
                            @foreach (\App\Models\Item::CONDITIONS as $condition)
                                <option value="{{ $condition }}" @selected(($filters['condition'] ?? '') === $condition)>{{ str_replace('_', ' ', ucfirst($condition)) }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="min_price" class="{{ $labelClass }}">Min KES</label>
                        <input id="min_price" name="min_price" type="number" min="0" value="{{ $filters['min_price'] ?? '' }}" class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label for="max_price" class="{{ $labelClass }}">Max KES</label>
                        <input id="max_price" name="max_price" type="number" min="0" value="{{ $filters['max_price'] ?? '' }}" class="{{ $inputClass }}">
                    </div>
                </div>

                <div>
                    <label for="sort" class="{{ $labelClass }}">Sort</label>
                    <div class="relative">
                        <select id="sort" name="sort" class="{{ $selectClass }}">
                            <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                            <option value="price_low_high" @selected(($filters['sort'] ?? '') === 'price_low_high')>Price: Low to High</option>
                            <option value="price_high_low" @selected(($filters['sort'] ?? '') === 'price_high_low')>Price: High to Low</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button class="flex-1 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Apply Filters</button>
                    <a href="{{ route('listings.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </aside>

        <section>
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                <h2 class="text-lg font-bold text-slate-900">Available Listings</h2>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ number_format($items->total()) }} results</span>
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
