@extends('layouts.market', ['title' => 'Browse Listings - Declutter Kenya'])

@section('content')
    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900">Filters</h1>

            <form method="GET" action="{{ route('listings.index') }}" class="mt-4 space-y-4 text-sm">
                <div>
                    <label for="q" class="mb-1 block font-medium text-slate-700">Keyword</label>
                    <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" class="w-full rounded-lg border-slate-300" placeholder="Search title or description">
                </div>

                <div>
                    <label for="category" class="mb-1 block font-medium text-slate-700">Category</label>
                    <select id="category" name="category" class="w-full rounded-lg border-slate-300">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="county" class="mb-1 block font-medium text-slate-700">County</label>
                    <select id="county" name="county" class="w-full rounded-lg border-slate-300">
                        <option value="">All counties</option>
                        @foreach ($counties as $county)
                            <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="condition" class="mb-1 block font-medium text-slate-700">Condition</label>
                    <select id="condition" name="condition" class="w-full rounded-lg border-slate-300">
                        <option value="">Any condition</option>
                        @foreach (\App\Models\Item::CONDITIONS as $condition)
                            <option value="{{ $condition }}" @selected(($filters['condition'] ?? '') === $condition)>{{ str_replace('_', ' ', ucfirst($condition)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="min_price" class="mb-1 block font-medium text-slate-700">Min KES</label>
                        <input id="min_price" name="min_price" type="number" min="0" value="{{ $filters['min_price'] ?? '' }}" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label for="max_price" class="mb-1 block font-medium text-slate-700">Max KES</label>
                        <input id="max_price" name="max_price" type="number" min="0" value="{{ $filters['max_price'] ?? '' }}" class="w-full rounded-lg border-slate-300">
                    </div>
                </div>

                <div>
                    <label for="sort" class="mb-1 block font-medium text-slate-700">Sort</label>
                    <select id="sort" name="sort" class="w-full rounded-lg border-slate-300">
                        <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                        <option value="price_low_high" @selected(($filters['sort'] ?? '') === 'price_low_high')>Price: Low to High</option>
                        <option value="price_high_low" @selected(($filters['sort'] ?? '') === 'price_high_low')>Price: High to Low</option>
                    </select>
                </div>

                <div class="flex gap-2 pt-2">
                    <button class="rounded-lg bg-orange-600 px-4 py-2 font-semibold text-white hover:bg-orange-500">Apply</button>
                    <a href="{{ route('listings.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700">Reset</a>
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
