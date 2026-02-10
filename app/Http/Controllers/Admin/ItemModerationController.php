<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemModerationController extends Controller
{
    public function index(Request $request): View
    {
        $items = Item::query()
            ->with(['user', 'category', 'images'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', (string) $request->input('status')))
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = trim((string) $request->input('q'));
                $query->where(function ($innerQuery) use ($keyword): void {
                    $innerQuery
                        ->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $reports = Report::query()
            ->with(['item', 'user'])
            ->latest()
            ->paginate(15, ['*'], 'reports_page');

        return view('admin.items.index', [
            'items' => $items,
            'reports' => $reports,
        ]);
    }

    public function remove(Item $item): RedirectResponse
    {
        $item->update(['status' => 'removed']);

        return back()->with('status', 'Listing removed by admin moderation.');
    }

    public function updateReportStatus(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Report::STATUSES)],
        ]);

        $report->update(['status' => $validated['status']]);

        return back()->with('status', 'Report status updated.');
    }
}
