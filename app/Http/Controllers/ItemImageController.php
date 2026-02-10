<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemImageUploadRequest;
use App\Models\Item;
use App\Models\ItemImage;
use App\Services\ItemImageService;
use Illuminate\Http\RedirectResponse;

class ItemImageController extends Controller
{
    public function store(ItemImageUploadRequest $request, Item $item, ItemImageService $imageService): RedirectResponse
    {
        $this->authorize('update', $item);

        $incomingImages = $request->validated('images');
        $imageCount = $item->images()->count();

        if (($imageCount + count($incomingImages)) > 6) {
            return back()->withErrors(['images' => 'An item can only have up to 6 images.']);
        }

        $imageService->storeImages($item, $incomingImages);

        return back()->with('status', 'Images uploaded.');
    }

    public function destroy(Item $item, ItemImage $image, ItemImageService $imageService): RedirectResponse
    {
        $this->authorize('update', $item);

        if ($image->item_id !== $item->id) {
            abort(404);
        }

        if ($item->images()->count() <= 1) {
            return back()->withErrors(['images' => 'A listing must have at least 1 image.']);
        }

        $imageService->deleteImage($image);

        return back()->with('status', 'Image removed.');
    }
}
