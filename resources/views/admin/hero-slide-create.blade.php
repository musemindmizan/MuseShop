<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add New Hero Slide</h1>
            <a href="{{ route('admin.hero-slides') }}"
                class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Hero Slider
            </a>
        </div>
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('admin.hero-slide.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="e.g. New Stylish Decor Furniture"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('title')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                            placeholder="e.g. Unique Furniture Style Design for Your Family"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('subtitle')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text') }}" placeholder="e.g. Shop Now"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('button_text')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                        <input type="text" name="button_link" value="{{ old('button_link', route('shop.index')) }}"
                            placeholder="https://..."
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('button_link')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slide Image *</label>
                    <div class="relative flex items-center justify-center w-full h-52">
                        <label for="slide-image"
                            class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                            <div id="upload-content" class="flex flex-col items-center justify-center pt-5 pb-6 z-10">
                                <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Upload slide image (PNG/JPG)</p>
                            </div>
                            <img id="image-preview"
                                class="hidden absolute inset-0 w-full h-full object-contain p-2 z-20 bg-white"
                                src="" alt="Slide Preview">
                            <input id="slide-image" name="image" type="file" class="hidden" required
                                accept="image/png, image/jpeg, image/jpg, image/webp" />
                        </label>
                        <button type="button" id="remove-image-btn"
                            class="hidden absolute top-2 right-2 z-30 bg-white text-red-500 hover:text-white hover:bg-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-gray-200 transition-colors focus:outline-none"
                            title="Remove image">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @error('image')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-gray-400 mt-1">Lower numbers show first.</p>
                        @error('sort_order')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="status" class="text-sm text-gray-700">Show on homepage</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.hero-slides') }}"
                        class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition text-sm">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm">Save
                        Slide</button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
        <script>
            (function () {
                const input = document.getElementById('slide-image');
                const preview = document.getElementById('image-preview');
                const uploadContent = document.getElementById('upload-content');
                const removeBtn = document.getElementById('remove-image-btn');

                input.addEventListener('change', function () {
                    const file = input.files && input.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        uploadContent.classList.add('hidden');
                        removeBtn.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });

                removeBtn.addEventListener('click', function () {
                    input.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                    uploadContent.classList.remove('hidden');
                    removeBtn.classList.add('hidden');
                });
            })();
        </script>
    @endpush
</x-admin-layout>
