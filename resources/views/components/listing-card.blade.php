@props(['item'])

@php
    $primaryImage = $item->primaryImage;
@endphp

<article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
    <a href="{{ route('listings.show', $item->slug) }}" class="block">
        <div class="aspect-[4/3] w-full overflow-hidden bg-slate-100">
            <img
                src="{{ $primaryImage ? $primaryImage->thumb_url : asset('images/listing-placeholder.svg') }}"
                alt="{{ $item->title }}"
                class="h-full w-full object-cover"
            >
        </div>
    </a>

    <div class="space-y-2 p-4">
        <div class="flex items-start justify-between gap-3">
            <a href="{{ route('listings.show', $item->slug) }}" class="line-clamp-2 text-sm font-semibold text-slate-900 hover:text-orange-700">
                {{ $item->title }}
            </a>
            <span class="text-sm font-bold text-orange-700">KES {{ number_format((float) $item->price, 0) }}</span>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-500">
            <span>{{ $item->county }}, {{ $item->town }}</span>
            <span>{{ $item->created_at->diffForHumans() }}</span>
        </div>

        <div class="flex items-center justify-between text-xs">
            <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-700">{{ str_replace('_', ' ', ucfirst($item->condition)) }}</span>
            @if ($item->negotiable)
                <span class="rounded-full bg-emerald-100 px-2 py-1 text-emerald-700">Negotiable</span>
            @endif
        </div>
    </div>
</article>
