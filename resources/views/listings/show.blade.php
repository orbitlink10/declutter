@extends('layouts.market', ['title' => $item->title.' - Declutter Kenya'])

@section('content')
    @php
        $unavailable = in_array($item->status, ['sold', 'removed'], true);
        $galleryImages = $item->images;
        $primaryImage = $item->primaryImage;
    @endphp

    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <section>
            @if ($unavailable)
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    This listing is not available right now ({{ ucfirst($item->status) }}).
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="aspect-[4/3] w-full bg-slate-100">
                    <img
                        id="main-image"
                        src="{{ $primaryImage ? $primaryImage->url : asset('images/listing-placeholder.svg') }}"
                        alt="{{ $item->title }}"
                        class="h-full w-full object-cover"
                    >
                </div>

                @if ($galleryImages->isNotEmpty())
                    <div class="grid grid-cols-5 gap-2 border-t border-slate-200 p-3">
                        @foreach ($galleryImages as $galleryImage)
                            <button
                                type="button"
                                data-image-button
                                data-image-src="{{ $galleryImage->url }}"
                                class="overflow-hidden rounded-lg border border-slate-200 bg-white"
                            >
                                <img src="{{ $galleryImage->thumb_url }}" alt="{{ $item->title }} photo" class="h-16 w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $item->title }}</h1>
                        <p class="mt-1 text-sm text-slate-500">Posted {{ $item->created_at->format('d M Y') }} in {{ $item->county }}, {{ $item->town }}</p>
                    </div>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">{{ ucfirst($item->status) }}</span>
                </div>

                <div class="mb-5 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full bg-slate-100 px-3 py-1">{{ $item->category->name }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1">{{ str_replace('_', ' ', ucfirst($item->condition)) }}</span>
                    @if ($item->negotiable)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">Negotiable</span>
                    @endif
                </div>

                <div class="prose max-w-none text-sm leading-7 text-slate-700">
                    {!! nl2br(e($item->description)) !!}
                </div>
            </div>

            @if ($relatedItems->isNotEmpty())
                <div class="mt-8">
                    <h2 class="mb-4 text-lg font-bold text-slate-900">Related Listings</h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($relatedItems as $relatedItem)
                            <x-listing-card :item="$relatedItem" />
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <aside class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Price</p>
                <p class="text-3xl font-black text-orange-700">KES {{ number_format((float) $item->price, 0) }}</p>
                @if ($item->negotiable)
                    <p class="mt-1 text-xs text-emerald-600">Seller accepts negotiation</p>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900">Seller</h3>
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ $item->user->profile_photo_url }}" class="h-12 w-12 rounded-full object-cover" alt="{{ $item->user->name }}">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $item->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $item->county }}, {{ $item->town }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900">Contact Seller</h3>
                <p class="mt-2 text-sm text-slate-600">Phone number</p>
                <p id="seller-phone" class="text-lg font-bold text-slate-900">{{ $item->contact_phone ?: 'Not provided' }}</p>
                <button
                    id="copy-phone"
                    type="button"
                    class="mt-3 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100"
                    @disabled(blank($item->contact_phone))
                >
                    Copy Phone Number
                </button>
            </div>

            @auth
                <div class="space-y-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <form action="{{ route('favorites.toggle', $item->slug) }}" method="POST">
                        @csrf
                        <button class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700" @disabled($item->status !== 'active')>
                            {{ $isFavorited ? 'Remove from Favorites' : 'Save Listing' }}
                        </button>
                    </form>

                    @if (auth()->id() !== $item->user_id)
                        @if ($hasReported)
                            <p class="rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">You have already reported this listing.</p>
                        @else
                            <form action="{{ route('reports.store', $item->slug) }}" method="POST" class="space-y-2">
                                @csrf
                                <label for="reason" class="text-xs font-semibold text-slate-700">Report listing</label>
                                <select id="reason" name="reason" class="w-full rounded-lg border-slate-300 text-sm">
                                    @foreach (\App\Models\Report::REASONS as $reason)
                                        <option value="{{ $reason }}">{{ ucfirst($reason) }}</option>
                                    @endforeach
                                </select>
                                <textarea name="details" rows="3" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Optional details"></textarea>
                                <button class="w-full rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50">
                                    Submit Report
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            @endauth
        </aside>
    </div>

    <script>
        document.querySelectorAll('[data-image-button]').forEach((button) => {
            button.addEventListener('click', () => {
                const src = button.getAttribute('data-image-src');
                const mainImage = document.getElementById('main-image');
                if (src && mainImage) {
                    mainImage.src = src;
                }
            });
        });

        const copyPhoneButton = document.getElementById('copy-phone');
        if (copyPhoneButton) {
            copyPhoneButton.addEventListener('click', async () => {
                const phone = document.getElementById('seller-phone')?.textContent?.trim();
                if (!phone) return;

                await navigator.clipboard.writeText(phone);
                copyPhoneButton.textContent = 'Copied';
                setTimeout(() => {
                    copyPhoneButton.textContent = 'Copy Phone Number';
                }, 1400);
            });
        }
    </script>
@endsection
