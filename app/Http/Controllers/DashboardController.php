<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $myListings = $user->items()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10);

        $favorites = Favorite::query()
            ->where('user_id', $user->id)
            ->with(['item.category', 'item.images', 'item.user'])
            ->latest()
            ->paginate(6, ['*'], 'favorites_page');

        $stats = [
            'active' => $user->items()->where('status', 'active')->count(),
            'sold' => $user->items()->where('status', 'sold')->count(),
            'views' => (int) $user->items()->sum('views_count'),
        ];

        return view('dashboard', [
            'myListings' => $myListings,
            'favorites' => $favorites,
            'stats' => $stats,
        ]);
    }
}
