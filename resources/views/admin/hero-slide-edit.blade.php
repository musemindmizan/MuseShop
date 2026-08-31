<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Hero Slide</h1>
            <div class="flex gap-2">
                <a href="{{ route('admin.hero-slides') }}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
                <form action="{{ route('admin.hero-slide.delete', $heroSlide->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this slide? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('admin.hero-slide.update', $heroSlide->id) }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" value="{{ old('title', $heroSlide->title) }}" required
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('title')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $heroSlide->subtitle) }}"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('subtitle')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" name="button_text" value="{{ old('button_text', $heroSlide->button_text) }}"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('button_text')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                            <input type="text" name="button_link" value="{{ old('button_link', $heroSlide->button_link) }}"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('button_link')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-8 items-start pt-4">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                            <div class="h-40 w-full border rounded-lg bg-white flex items-center justify-center p-2 overflow-hidden">
                                <img src="{{ asset('uploads/hero-slides/' . $heroSlide->image) }}" class="max-h-full max-w-full object-contain"
                                    alt="{{ $heroSlide->title }}">
                            </div>
                        </div>

                        <div class="w-full md:w-2/3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Change Image</label>
                            <div class="relative w-full h-40">
                                <label for="slide-image"
                                    class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                                    <div id="upload-content" class="text-center z-10">
                                        <i class="fa-solid fa-image text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-sm text-gray-500">Upload new image</p>
                                    </div>
                                    <img id="image-preview"
                                        class="hidden absolute inset-0 w-full h-full object-contain p-2 z-20 bg-white"
                                        src="" alt="New Slide Preview">
                                    <input type="file" id="slide-image" name="image" class="hidden"
                                        accept="image/png, image/jpeg, image/jpg, image/webp" />
                                </label>
                                <button type="button" id="remove-image-btn"
                                    class="hidden absolute top-2 right-2 z-30 bg-white text-red-500 hover:text-white hover:bg-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-gray-200 transition-colors focus:outline-none"
                                    title="Remove new image">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            @error('image')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $heroSlide->sort_order) }}" min="0"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('sort_order')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="status" id="status" value="1" {{ old('status', $heroSlide->status) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                            <label for="status" class="text-sm text-gray-700">Show on homepage</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('admin.hero-slides') }}"
                            class="px-6 py-2 border rounded-lg hover:bg-gray-50 text-sm transition">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-md">Save
                            Changes</button>
                    </div>
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
