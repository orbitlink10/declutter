<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Item $item): RedirectResponse
    {
        if ($item->status !== 'active') {
            return back()->withErrors(['favorite' => 'Only active listings can be saved.']);
        }

        if ($item->user_id === $request->user()->id) {
            return back()->withErrors(['favorite' => 'You cannot save your own listing.']);
        }

        $existing = $request->user()->favorites()->where('item_id', $item->id)->first();

        if ($existing) {
            $existing->delete();

            return back()->with('status', 'Listing removed from favorites.');
        }

        $request->user()->favorites()->create(['item_id' => $item->id]);

        return back()->with('status', 'Listing saved to favorites.');
    }
}
