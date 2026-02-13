@php
    $pageContent = array_merge(\App\Support\HomepageContent::defaults(), $content ?? []);
@endphp

@extends('layouts.market', ['title' => $pageContent['services_page_title'].' - Declutter Kenya'])

@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($pageContent['services_page_content']), 160))

@section('content')
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-3xl font-black tracking-tight text-slate-900">{{ $pageContent['services_page_title'] }}</h1>
        <div class="seo-rich-content mt-5 border-t border-slate-200 pt-5">
            {!! $pageContent['services_page_content'] !!}
        </div>
    </section>
@endsection
