<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Support\HomepageContent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function home(): View
    {
        $latestItems = Item::query()
            ->with(['category', 'user', 'images'])
            ->active()
            ->latestFirst()
            ->limit(8)
            ->get();

        $categories = Category::query()
            ->withCount([
                'items as active_items_count' => fn (Builder $query) => $query->where('status', 'active'),
            ])
            ->orderBy('id')
            ->get();

        return view('listings.home', [
            'latestItems' => $latestItems,
            'categories' => $categories,
            'homepageContent' => HomepageContent::all(),
        ]);
    }

    public function index(Request $request): View
    {
        $query = Item::query()
            ->with(['category', 'user', 'images'])
            ->active();

        $this->applyFilters($query, $request);

        $items = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();
        $counties = Item::query()->active()->select('county')->distinct()->orderBy('county')->pluck('county');

        return view('listings.index', [
            'items' => $items,
            'categories' => $categories,
            'counties' => $counties,
            'filters' => $request->all(),
        ]);
    }

    public function show(Request $request, Item $item): View
    {
        $item->load(['category', 'user', 'images']);

        $canManage = $request->user() && ($request->user()->is_admin || $request->user()->id === $item->user_id);

        if ($item->status === 'draft' && ! $canManage) {
            abort(404);
        }

        if ($item->status === 'active' && ! $canManage) {
            $item->increment('views_count');
        }

        $isFavorited = false;
        $hasReported = false;

        if ($request->user()) {
            $isFavorited = $request->user()->favorites()->where('item_id', $item->id)->exists();
            $hasReported = $request->user()->reports()->where('item_id', $item->id)->exists();
        }

        $relatedItems = Item::query()
            ->with(['images', 'category', 'user'])
            ->active()
            ->where('id', '!=', $item->id)
            ->where('category_id', $item->category_id)
            ->latestFirst()
            ->limit(4)
            ->get();

        return view('listings.show', [
            'item' => $item,
            'relatedItems' => $relatedItems,
            'isFavorited' => $isFavorited,
            'hasReported' => $hasReported,
        ]);
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $keyword = trim((string) $request->string('q'));

        if ($keyword !== '') {
            $query->where(function (Builder $innerQuery) use ($keyword): void {
                $innerQuery
                    ->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $categorySlug = (string) $request->input('category');

            $query->whereHas('category', function (Builder $categoryQuery) use ($categorySlug): void {
                $categoryQuery->where('slug', $categorySlug);
            });
        }

        if ($request->filled('county')) {
            $query->where('county', (string) $request->input('county'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', (string) $request->input('condition'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        match ((string) $request->input('sort', 'newest')) {
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            default => $query->latestFirst(),
        };
    }
}
