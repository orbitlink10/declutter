<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Admin Moderation</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            @include('admin.partials.navigation')

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-slate-900">All Items</h3>

                <form method="GET" class="mb-4 grid gap-3 sm:grid-cols-3">
                    <input name="q" value="{{ request('q') }}" placeholder="Search title/description" class="rounded-lg border-slate-300 text-sm">
                    <select name="status" class="rounded-lg border-slate-300 text-sm">
                        <option value="">All statuses</option>
                        @foreach (\App\Models\Item::STATUSES as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="py-3 pe-3">Item</th>
                                <th class="py-3 pe-3">Owner</th>
                                <th class="py-3 pe-3">Status</th>
                                <th class="py-3 pe-3">Price</th>
                                <th class="py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pe-3">
                                        <p class="font-medium text-slate-900">{{ $item->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->category?->name }}</p>
                                    </td>
                                    <td class="py-3 pe-3 text-xs text-slate-600">{{ $item->user?->name }}</td>
                                    <td class="py-3 pe-3"><span class="rounded-full bg-slate-100 px-2 py-1 text-xs">{{ ucfirst($item->status) }}</span></td>
                                    <td class="py-3 pe-3">KES {{ number_format((float) $item->price, 0) }}</td>
                                    <td class="py-3 text-right">
                                        @if ($item->status !== 'removed')
                                            <form method="POST" action="{{ route('admin.items.remove', $item) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-xs font-semibold text-rose-700 hover:text-rose-600">Set Removed</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Already removed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $items->links() }}</div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-slate-900">Reported Items</h3>

                @if ($reports->isEmpty())
                    <p class="rounded-lg border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">No reports yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($reports as $report)
                            <article class="rounded-xl border border-slate-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $report->item?->title ?? 'Deleted item' }}</p>
                                        <p class="text-xs text-slate-500">
                                            Reported by {{ $report->user?->name ?? 'Unknown' }} | Reason: {{ ucfirst($report->reason) }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs">{{ ucfirst($report->status) }}</span>
                                </div>

                                @if ($report->details)
                                    <p class="mt-2 text-sm text-slate-700">{{ $report->details }}</p>
                                @endif

                                <form method="POST" action="{{ route('admin.reports.status', $report) }}" class="mt-3 flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border-slate-300 text-sm">
                                        @foreach (\App\Models\Report::STATUSES as $status)
                                            <option value="{{ $status }}" @selected($report->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Update</button>
                                </form>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-4">{{ $reports->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
