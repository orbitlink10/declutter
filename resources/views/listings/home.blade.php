@extends('layouts.market', ['title' => ($homepageContent['hero_title'] ?? 'Declutter Kenya')])

@php
    $seoContent = array_merge(\App\Support\HomepageContent::defaults(), $homepageContent ?? []);
@endphp

@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($seoContent['home_page_content'] ?: $seoContent['hero_description']), 160))

@section('content')
    @php
        $content = $seoContent;
    @endphp

    <section class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-12 text-white sm:px-12">
        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-orange-500/30 blur-2xl"></div>
        <div class="absolute -bottom-8 left-12 h-40 w-40 rounded-full bg-amber-400/30 blur-2xl"></div>

        <div class="relative grid gap-8 lg:grid-cols-2 lg:items-center">
            <div>
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl">{{ $content['hero_title'] }}</h1>
                <p class="mt-4 max-w-xl text-sm text-slate-200 sm:text-base">
                    {{ $content['hero_description'] }}
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('listings.index') }}" class="rounded-full bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-orange-400">
                        {{ $content['primary_cta_label'] }}
                    </a>
                    @auth
                        <a href="{{ route('my.listings.create') }}" class="rounded-full border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                            {{ $content['auth_secondary_cta_label'] }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-full border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                            {{ $content['guest_secondary_cta_label'] }}
                        </a>
                    @endauth
                </div>

                @if (! empty($content['hero_image_path']))
                    <div class="mt-6 overflow-hidden rounded-2xl border border-white/20">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($content['hero_image_path']) }}"
                            alt="{{ $content['hero_title'] }}"
                            class="h-auto w-full object-cover"
                            loading="lazy"
                        >
                    </div>
                @endif
            </div>

            <form action="{{ route('listings.index') }}" method="GET" class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm">
                <label for="home-search" class="text-sm font-semibold text-white">{{ $content['search_label'] }}</label>
                <input
                    id="home-search"
                    name="q"
                    type="text"
                    placeholder="{{ $content['search_placeholder'] }}"
                    class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/70 focus:border-orange-300 focus:ring-orange-300"
                >

                <label for="home-category" class="mt-4 block text-sm font-semibold text-white">{{ $content['category_filter_label'] }}</label>
                <select id="home-category" name="category" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white focus:border-orange-300 focus:ring-orange-300">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <button class="mt-4 w-full rounded-xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white hover:bg-orange-400">
                    {{ $content['search_button_label'] }}
                </button>
            </form>
        </div>
    </section>

    <section class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-bold text-slate-900">{{ $content['why_choose_title'] }}</h2>
        <p class="mt-3 text-base text-slate-700">{{ $content['why_choose_description'] }}</p>
    </section>

    <section class="mt-10">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900">{{ $content['categories_section_title'] }}</h2>
            <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-600">{{ $content['categories_section_link_label'] }}</a>
        </div>

        @if ($categories->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                {{ $content['categories_empty_state'] }}
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
            <h2 class="text-xl font-bold text-slate-900">{{ $content['latest_section_title'] }}</h2>
            <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-600">{{ $content['latest_section_link_label'] }}</a>
        </div>

        @if ($latestItems->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                {{ $content['latest_empty_state'] }}
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($latestItems as $item)
                    <x-listing-card :item="$item" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="mt-10 rounded-3xl border border-amber-100 bg-gradient-to-br from-amber-50/60 via-orange-50/40 to-white p-4 sm:p-8">
        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-10">
            <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">{{ $content['products_section_title'] }}</h2>

            <div class="mt-6 border-t border-slate-200 pt-6 text-slate-700 leading-relaxed
                [&_h1]:text-4xl [&_h1]:font-black [&_h1]:leading-tight [&_h1]:text-blue-900
                [&_h2]:mt-8 [&_h2]:text-3xl [&_h2]:font-extrabold [&_h2]:leading-tight [&_h2]:text-blue-900
                [&_h3]:mt-6 [&_h3]:text-2xl [&_h3]:font-bold [&_h3]:text-slate-900
                [&_p]:mt-4 [&_p]:text-xl [&_p]:leading-relaxed [&_p]:text-slate-700
                [&_ul]:mt-4 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:ps-6
                [&_ol]:mt-4 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:ps-6
                [&_a]:font-semibold [&_a]:text-blue-700 hover:[&_a]:text-blue-600 [&_a]:underline
                [&_hr]:my-8 [&_hr]:border-slate-200">
                {!! $content['home_page_content'] !!}
            </div>
        </div>
    </section>
@endsection
