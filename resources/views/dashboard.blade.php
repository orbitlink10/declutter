<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Listings</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ $stats['active'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sold Listings</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ $stats['sold'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Views</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ number_format($stats['views']) }}</p>
                </div>
            </div>

            @if (auth()->user()->is_admin)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-lg font-bold text-slate-900">Admin Tools</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.settings.homepage.edit') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            Homepage Content
                        </a>
                        <a href="{{ route('admin.items.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Item Moderation
                        </a>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">My Listings</h3>
                    <a href="{{ route('my.listings.create') }}" class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-500">Create Listing</a>
                </div>

                @if ($myListings->isEmpty())
                    <p class="rounded-lg border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
                        You have not created any listings yet.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="py-3 pe-3">Listing</th>
                                    <th class="py-3 pe-3">Status</th>
                                    <th class="py-3 pe-3">Price</th>
                                    <th class="py-3 pe-3">Views</th>
                                    <th class="py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myListings as $item)
                                    <tr class="border-b border-slate-100">
                                        <td class="py-3 pe-3">
                                            <p class="font-medium text-slate-900">{{ $item->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $item->county }}, {{ $item->town }}</p>
                                        </td>
                                        <td class="py-3 pe-3">
                                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700">{{ ucfirst($item->status) }}</span>
                                        </td>
                                        <td class="py-3 pe-3">KES {{ number_format((float) $item->price, 0) }}</td>
                                        <td class="py-3 pe-3">{{ number_format((int) $item->views_count) }}</td>
                                        <td class="py-3 text-right">
                                            <a href="{{ route('my.listings.edit', $item) }}" class="text-xs font-semibold text-orange-700 hover:text-orange-600">Manage</a>
                                            <span class="mx-1 text-slate-300">|</span>
                                            <a href="{{ route('listings.show', $item->slug) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-800">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $myListings->links() }}</div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-slate-900">Saved Listings</h3>

                @if ($favorites->isEmpty())
                    <p class="rounded-lg border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
                        No saved listings yet.
                    </p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($favorites as $favorite)
                            @if ($favorite->item)
                                <x-listing-card :item="$favorite->item" />
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $favorites->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
