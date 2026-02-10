<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request, Item $item): RedirectResponse
    {
        if ($item->user_id === $request->user()->id) {
            return back()->withErrors(['report' => 'You cannot report your own listing.']);
        }

        $request->user()->reports()->updateOrCreate(
            ['item_id' => $item->id],
            [
                'reason' => $request->validated('reason'),
                'details' => $request->validated('details'),
                'status' => 'pending',
            ]
        );

        return back()->with('status', 'Listing reported. Thank you.');
    }
}
