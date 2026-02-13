<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomepageContentRequest;
use App\Support\HomepageContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class HomepageContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.homepage', [
            'content' => HomepageContent::all(),
            'storageReady' => HomepageContent::storageReady(),
        ]);
    }

    public function update(UpdateHomepageContentRequest $request): RedirectResponse
    {
        if (! HomepageContent::storageReady()) {
            return redirect()
                ->route('admin.settings.homepage.edit')
                ->with('error', 'Homepage content storage is not ready. Run migrations, then try again.');
        }

        $values = $request->validated();
        unset($values['hero_image']);
        $currentContent = HomepageContent::all();

        if ($request->hasFile('hero_image')) {
            $newImagePath = $request->file('hero_image')->store('homepage', 'public');

            if (! empty($currentContent['hero_image_path']) && Storage::disk('public')->exists($currentContent['hero_image_path'])) {
                Storage::disk('public')->delete($currentContent['hero_image_path']);
            }

            $values['hero_image_path'] = $newImagePath;
        }

        if (! HomepageContent::update($values)) {
            return redirect()
                ->route('admin.settings.homepage.edit')
                ->with('error', 'Could not save homepage content. Please check your database connection and try again.');
        }

        return redirect()
            ->route('admin.settings.homepage.edit')
            ->with('status', 'Homepage content updated successfully.');
    }
}
