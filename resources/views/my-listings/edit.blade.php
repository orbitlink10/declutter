<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold text-slate-900">Manage Listing</h2>
            <a href="{{ route('listings.show', $item->slug) }}" class="text-sm font-semibold text-orange-700 hover:text-orange-600">View Public Page</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @include('my-listings.partials.form')
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900">Quick Add Images</h3>
                <form method="POST" action="{{ route('my.listings.images.store', $item) }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                    @csrf
                    <input name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="block w-full rounded-md border border-slate-300 bg-white text-sm">
                    <button class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Upload More Images</button>
                </form>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-rose-700">Delete Listing</h3>
                <p class="mt-1 text-sm text-slate-600">This permanently removes the listing and all uploaded images.</p>
                <form method="POST" action="{{ route('my.listings.destroy', $item) }}" class="mt-4" onsubmit="return confirm('Delete this listing permanently?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-500">Delete Listing</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
