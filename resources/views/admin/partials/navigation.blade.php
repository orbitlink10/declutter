<div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
    <div class="flex flex-wrap gap-2">
        <a
            href="{{ route('admin.settings.homepage.edit') }}"
            class="{{ request()->routeIs('admin.settings.homepage.*') ? 'bg-slate-900 text-white' : 'border border-slate-300 text-slate-700 hover:bg-slate-100' }} rounded-lg px-4 py-2 text-sm font-semibold transition"
        >
            Homepage Content
        </a>
        <a
            href="{{ route('admin.items.index') }}"
            class="{{ request()->routeIs('admin.items.*') || request()->routeIs('admin.reports.*') ? 'bg-slate-900 text-white' : 'border border-slate-300 text-slate-700 hover:bg-slate-100' }} rounded-lg px-4 py-2 text-sm font-semibold transition"
        >
            Item Moderation
        </a>
    </div>
</div>
