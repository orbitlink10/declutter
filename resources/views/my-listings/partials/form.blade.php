@php
    $isEdit = $item->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('my.listings.update', $item) : route('my.listings.store') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div class="md:col-span-2">
            <x-input-label for="title" value="Title" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $item->title)" required />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div class="md:col-span-2">
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-orange-400 focus:ring-orange-400" required>{{ old('description', $item->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div>
            <x-input-label for="category_id" value="Category" />
            <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-orange-400 focus:ring-orange-400" required>
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $item->category_id) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
        </div>

        <div>
            <x-input-label for="condition" value="Condition" />
            <select id="condition" name="condition" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-orange-400 focus:ring-orange-400" required>
                @foreach (\App\Models\Item::CONDITIONS as $condition)
                    <option value="{{ $condition }}" @selected(old('condition', $item->condition ?? 'good') === $condition)>{{ str_replace('_', ' ', ucfirst($condition)) }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('condition')" />
        </div>

        <div>
            <x-input-label for="price" value="Price (KES)" />
            <x-text-input id="price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" :value="old('price', $item->price)" required />
            <x-input-error class="mt-2" :messages="$errors->get('price')" />
        </div>

        <div>
            <x-input-label for="status" value="Status" />
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-orange-400 focus:ring-orange-400" required>
                @foreach (\App\Models\Item::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $item->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>

        <div>
            <x-input-label for="county" value="County" />
            <x-text-input id="county" name="county" type="text" class="mt-1 block w-full" :value="old('county', $item->county)" required />
            <x-input-error class="mt-2" :messages="$errors->get('county')" />
        </div>

        <div>
            <x-input-label for="town" value="Town" />
            <x-text-input id="town" name="town" type="text" class="mt-1 block w-full" :value="old('town', $item->town)" required />
            <x-input-error class="mt-2" :messages="$errors->get('town')" />
        </div>

        <div>
            <x-input-label for="contact_phone" value="Contact Phone (optional)" />
            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $item->contact_phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
        </div>

        <div class="flex items-center gap-2 pt-6">
            <input id="negotiable" name="negotiable" type="checkbox" value="1" class="rounded border-slate-300 text-orange-600" @checked(old('negotiable', $item->negotiable))>
            <x-input-label for="negotiable" value="Price is negotiable" />
        </div>

        <div class="md:col-span-2">
            <x-input-label for="images-input" value="{{ $isEdit ? 'Upload New Images (optional)' : 'Item Images (1-6 required)' }}" />
            <input id="images-input" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple class="mt-1 block w-full rounded-md border border-slate-300 bg-white text-sm">
            <p class="mt-1 text-xs text-slate-500">JPG, PNG, WEBP up to 3MB each. Max 6 images total.</p>
            <x-input-error class="mt-2" :messages="$errors->get('images')" />
            <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
            <div id="upload-preview" class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-6"></div>
        </div>

        @if ($isEdit && $item->images->isNotEmpty())
            <div class="md:col-span-2">
                <h3 class="text-sm font-semibold text-slate-800">Current Images</h3>
                <div class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-6">
                    @foreach ($item->images as $image)
                        <label class="relative block overflow-hidden rounded-lg border border-slate-200">
                            <img src="{{ $image->thumb_url }}" alt="Current item image" class="h-24 w-full object-cover">
                            <span class="absolute bottom-1 left-1 rounded bg-white/90 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                <input type="checkbox" name="removed_image_ids[]" value="{{ $image->id }}" class="me-1 rounded border-slate-300 text-rose-600">
                                Remove
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('my.listings.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Back</a>
        <x-primary-button>{{ $isEdit ? 'Update Listing' : 'Create Listing' }}</x-primary-button>
    </div>
</form>

<script>
    const imageInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('upload-preview');

    if (imageInput && previewContainer) {
        imageInput.addEventListener('change', () => {
            previewContainer.innerHTML = '';

            Array.from(imageInput.files || []).slice(0, 6).forEach((file) => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'overflow-hidden rounded-lg border border-slate-200';

                    const img = document.createElement('img');
                    img.src = event.target?.result;
                    img.alt = file.name;
                    img.className = 'h-20 w-full object-cover';

                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        });
    }
</script>
