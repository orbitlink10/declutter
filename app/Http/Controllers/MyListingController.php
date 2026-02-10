<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Services\ItemImageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MyListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Item::class, 'item');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $items = $request->user()
            ->items()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(12);

        return view('my-listings.index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Item::class);

        return view('my-listings.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'item' => new Item([
                'negotiable' => false,
                'status' => 'draft',
                'county' => $request->user()->county,
                'town' => $request->user()->town,
                'contact_phone' => $request->user()->phone,
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request, ItemImageService $imageService): RedirectResponse
    {
        $validated = $request->validated();

        $item = Item::create([
            ...Arr::except($validated, ['images']),
            'user_id' => $request->user()->id,
            'contact_phone' => $validated['contact_phone'] ?? $request->user()->phone,
        ]);

        $imageService->storeImages($item, $validated['images']);

        return redirect()
            ->route('my.listings.edit', $item)
            ->with('status', 'Listing created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item): View
    {
        $item->load('images');

        return view('my-listings.edit', [
            'item' => $item,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item, ItemImageService $imageService): RedirectResponse
    {
        $validated = $request->validated();

        $imagesMarkedForDelete = collect($validated['removed_image_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->values();

        $incomingImages = $validated['images'] ?? [];
        $currentCount = $item->images()->count();
        $deletableImages = $item->images()->whereIn('id', $imagesMarkedForDelete)->get();
        $finalCount = $currentCount - $deletableImages->count() + count($incomingImages);

        if ($finalCount < 1) {
            return back()
                ->withErrors(['images' => 'A listing must have at least 1 image.'])
                ->withInput();
        }

        if ($finalCount > 6) {
            return back()
                ->withErrors(['images' => 'An item can only have up to 6 images.'])
                ->withInput();
        }

        foreach ($deletableImages as $image) {
            $imageService->deleteImage($image);
        }

        if ($incomingImages) {
            $imageService->storeImages($item, $incomingImages);
        }

        $item->update([
            ...Arr::except($validated, ['images', 'removed_image_ids']),
            'contact_phone' => $validated['contact_phone'] ?? $request->user()->phone,
        ]);

        return redirect()
            ->route('my.listings.edit', $item)
            ->with('status', 'Listing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item, ItemImageService $imageService): RedirectResponse
    {
        $imageService->deleteAllForItem($item);
        $item->delete();

        return redirect()
            ->route('my.listings.index')
            ->with('status', 'Listing deleted.');
    }
}
