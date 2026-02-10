<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">My Listings</h2>
            <a href="{{ route('my.listings.create') }}" class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-500">Create Listing</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            @if ($items->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">
                    You do not have any listings yet.
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($items as $item)
                        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="mb-3 aspect-[4/3] overflow-hidden rounded-xl bg-slate-100">
                                <img src="{{ $item->primaryImage ? $item->primaryImage->thumb_url : asset('images/listing-placeholder.svg') }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                            </div>
                            <h3 class="line-clamp-2 text-sm font-semibold text-slate-900">{{ $item->title }}</h3>
                            <div class="mt-1 flex items-center justify-between text-xs text-slate-500">
                                <span>{{ ucfirst($item->status) }}</span>
                                <span>KES {{ number_format((float) $item->price, 0) }}</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between text-xs">
                                <a href="{{ route('my.listings.edit', $item) }}" class="font-semibold text-orange-700 hover:text-orange-600">Manage</a>
                                <a href="{{ route('listings.show', $item->slug) }}" class="font-semibold text-slate-600 hover:text-slate-800">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">{{ $items->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
