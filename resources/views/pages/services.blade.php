@extends('layouts.market', ['title' => 'Services - Declutter Kenya'])

@section('meta_description', 'Explore Declutter Kenya services for listing products, reaching buyers, and managing your marketplace activity.')

@section('content')
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-3xl font-black tracking-tight text-slate-900">Services</h1>
        <p class="mt-4 text-base leading-relaxed text-slate-700">
            We provide a streamlined platform for creating listings, uploading item photos, managing inquiries, and selling to nearby buyers.
        </p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <article class="rounded-xl border border-slate-200 p-4">
                <h2 class="text-lg font-bold text-slate-900">Seller Dashboard</h2>
                <p class="mt-2 text-sm text-slate-700">Track active, sold, and draft listings in one place.</p>
            </article>
            <article class="rounded-xl border border-slate-200 p-4">
                <h2 class="text-lg font-bold text-slate-900">Listing Management</h2>
                <p class="mt-2 text-sm text-slate-700">Create, edit, and update item details with photos and pricing.</p>
            </article>
            <article class="rounded-xl border border-slate-200 p-4">
                <h2 class="text-lg font-bold text-slate-900">Favorites</h2>
                <p class="mt-2 text-sm text-slate-700">Save interesting products and return to them later.</p>
            </article>
            <article class="rounded-xl border border-slate-200 p-4">
                <h2 class="text-lg font-bold text-slate-900">Moderation</h2>
                <p class="mt-2 text-sm text-slate-700">Admin review workflows help keep listings trustworthy.</p>
            </article>
        </div>
    </section>
@endsection
