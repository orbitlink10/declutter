<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-slate-900">Update Homepage Content</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (! $storageReady)
                <div class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    Settings storage is unavailable. Run <code>php artisan migrate</code> so homepage content updates can be saved.
                </div>
            @endif

            @include('admin.partials.navigation')

            <form id="homepage-content-form" method="POST" action="{{ route('admin.settings.homepage.update') }}" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <h3 class="rounded-t-2xl bg-blue-600 px-5 py-3 text-lg font-semibold text-white">Homepage Content Management</h3>

                <div class="space-y-8 p-5">
                    <section class="space-y-5">
                        <div>
                            <label for="hero_title" class="mb-2 block text-sm font-semibold text-slate-700">Hero Header Title</label>
                            <input id="hero_title" name="hero_title" value="{{ old('hero_title', $content['hero_title']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                            @error('hero_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="hero_description" class="mb-2 block text-sm font-semibold text-slate-700">Hero Header Description</label>
                            <textarea id="hero_description" name="hero_description" rows="3" class="w-full rounded-lg border-slate-300 text-sm" required>{{ old('hero_description', $content['hero_description']) }}</textarea>
                            @error('hero_description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="hero_image" class="mb-2 block text-sm font-semibold text-slate-700">Hero Image (1280 x 720)</label>
                            <input id="hero_image" name="hero_image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-lg border-slate-300 text-sm">
                            <p class="mt-1 text-xs text-slate-500">Upload a new image to replace the current hero image.</p>
                            @error('hero_image') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        @if (! empty($content['hero_image_path']))
                            <div>
                                <p class="mb-2 text-sm font-semibold text-slate-700">Current Hero Image</p>
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($content['hero_image_path']) }}" alt="Homepage hero image" class="h-auto w-full max-w-xl rounded-xl border border-slate-200 object-cover">
                            </div>
                        @endif
                    </section>

                    <section class="grid gap-5 lg:grid-cols-2">
                        <div>
                            <label for="why_choose_title" class="mb-2 block text-sm font-semibold text-slate-700">Why Choose Title</label>
                            <input id="why_choose_title" name="why_choose_title" value="{{ old('why_choose_title', $content['why_choose_title']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                            @error('why_choose_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="products_section_title" class="mb-2 block text-sm font-semibold text-slate-700">Products Section Title</label>
                            <input id="products_section_title" name="products_section_title" value="{{ old('products_section_title', $content['products_section_title']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                            @error('products_section_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="why_choose_description" class="mb-2 block text-sm font-semibold text-slate-700">Why Choose Description</label>
                            <textarea id="why_choose_description" name="why_choose_description" rows="3" class="w-full rounded-lg border-slate-300 text-sm" required>{{ old('why_choose_description', $content['why_choose_description']) }}</textarea>
                            @error('why_choose_description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="home_page_content" class="mb-2 block text-sm font-semibold text-slate-700">Home Page Content</label>
                            <textarea id="home_page_content" name="home_page_content" rows="12" class="w-full rounded-lg border-slate-300 text-sm" required>{{ old('home_page_content', $content['home_page_content']) }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">You can add SEO text and HTML formatting (headings, paragraphs, links, lists).</p>
                            @error('home_page_content') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </section>

                    <section class="space-y-5 rounded-xl border border-slate-200 p-4">
                        <h4 class="text-base font-semibold text-slate-900">Homepage UI Labels</h4>

                        <div class="grid gap-5 lg:grid-cols-2">
                            <div>
                                <label for="primary_cta_label" class="mb-2 block text-sm font-semibold text-slate-700">Primary Button Label</label>
                                <input id="primary_cta_label" name="primary_cta_label" value="{{ old('primary_cta_label', $content['primary_cta_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('primary_cta_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="auth_secondary_cta_label" class="mb-2 block text-sm font-semibold text-slate-700">Signed-in Secondary Label</label>
                                <input id="auth_secondary_cta_label" name="auth_secondary_cta_label" value="{{ old('auth_secondary_cta_label', $content['auth_secondary_cta_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('auth_secondary_cta_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="guest_secondary_cta_label" class="mb-2 block text-sm font-semibold text-slate-700">Guest Secondary Label</label>
                                <input id="guest_secondary_cta_label" name="guest_secondary_cta_label" value="{{ old('guest_secondary_cta_label', $content['guest_secondary_cta_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('guest_secondary_cta_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="search_label" class="mb-2 block text-sm font-semibold text-slate-700">Search Label</label>
                                <input id="search_label" name="search_label" value="{{ old('search_label', $content['search_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('search_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="search_placeholder" class="mb-2 block text-sm font-semibold text-slate-700">Search Placeholder</label>
                                <input id="search_placeholder" name="search_placeholder" value="{{ old('search_placeholder', $content['search_placeholder']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('search_placeholder') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="category_filter_label" class="mb-2 block text-sm font-semibold text-slate-700">Category Filter Label</label>
                                <input id="category_filter_label" name="category_filter_label" value="{{ old('category_filter_label', $content['category_filter_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('category_filter_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="search_button_label" class="mb-2 block text-sm font-semibold text-slate-700">Search Button Label</label>
                                <input id="search_button_label" name="search_button_label" value="{{ old('search_button_label', $content['search_button_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('search_button_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="categories_section_title" class="mb-2 block text-sm font-semibold text-slate-700">Categories Section Title</label>
                                <input id="categories_section_title" name="categories_section_title" value="{{ old('categories_section_title', $content['categories_section_title']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('categories_section_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="categories_section_link_label" class="mb-2 block text-sm font-semibold text-slate-700">Categories Link Label</label>
                                <input id="categories_section_link_label" name="categories_section_link_label" value="{{ old('categories_section_link_label', $content['categories_section_link_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('categories_section_link_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="latest_section_title" class="mb-2 block text-sm font-semibold text-slate-700">Latest Section Title</label>
                                <input id="latest_section_title" name="latest_section_title" value="{{ old('latest_section_title', $content['latest_section_title']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('latest_section_title') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="latest_section_link_label" class="mb-2 block text-sm font-semibold text-slate-700">Latest Link Label</label>
                                <input id="latest_section_link_label" name="latest_section_link_label" value="{{ old('latest_section_link_label', $content['latest_section_link_label']) }}" class="w-full rounded-lg border-slate-300 text-sm" required>
                                @error('latest_section_link_label') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="lg:col-span-2">
                                <label for="categories_empty_state" class="mb-2 block text-sm font-semibold text-slate-700">Categories Empty Message</label>
                                <textarea id="categories_empty_state" name="categories_empty_state" rows="2" class="w-full rounded-lg border-slate-300 text-sm" required>{{ old('categories_empty_state', $content['categories_empty_state']) }}</textarea>
                                @error('categories_empty_state') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="lg:col-span-2">
                                <label for="latest_empty_state" class="mb-2 block text-sm font-semibold text-slate-700">Latest Empty Message</label>
                                <textarea id="latest_empty_state" name="latest_empty_state" rows="2" class="w-full rounded-lg border-slate-300 text-sm" required>{{ old('latest_empty_state', $content['latest_empty_state']) }}</textarea>
                                @error('latest_empty_state') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <div class="flex justify-end border-t border-slate-200 pt-4">
                        <button class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                            Save Homepage Content
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.tinymce) {
                return;
            }

            tinymce.init({
                selector: '#home_page_content',
                base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7',
                suffix: '.min',
                height: 420,
                menubar: 'file edit view insert format tools table',
                plugins: 'advlist autolink lists link image media table code fullscreen',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image media | code fullscreen',
                branding: false,
                promotion: false,
                resize: true
            });

            const form = document.getElementById('homepage-content-form');
            if (form) {
                form.addEventListener('submit', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
</x-app-layout>
