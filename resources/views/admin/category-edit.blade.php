<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Category</h1>
            <div class="flex gap-2">
                <a href="{{route('admin.categories')}}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
                <button
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">

            <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none">
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}"
                                class="w-full border px-4 py-2 rounded-lg bg-gray-50 outline-none text-gray-500">
                            @error('slug')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select id="parent_id" name="parent_id"
                            class="w-full border px-4 py-2 rounded-lg outline-none bg-white">
                            <option value="">None (Top Level)</option>
                            @foreach ($categories as $parentOption)
                                <option value="{{ $parentOption->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parentOption->id ? 'selected' : '' }}>
                                    {{ $parentOption->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row gap-8 items-start pt-4">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                            <div class="p-4 border rounded-lg bg-gray-50 w-full h-40 flex items-center justify-center">
                                <img src="{{asset('uploads/categories')}}/{{ $category->image }}" class="max-h-full max-w-full object-contain"
                                    alt="Current" onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                            </div>
                        </div>

                        <div class="w-full md:w-2/3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Image</label>

                            <div class="relative w-full h-40">
                                <label for="category-image"
                                    class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">

                                    <div id="upload-content" class="text-center z-10">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">Drop files here to update</p>
                                    </div>

                                    <img id="image-preview"
                                        class="hidden absolute inset-0 w-full h-full object-contain p-2 z-20 bg-white"
                                        src="" alt="New Category Image Preview">

                                    <input type="file" id="category-image" name="image" class="hidden"
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
                    </div>

                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="status" class="text-sm text-gray-700">Set as Active Category</label>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                        <a href="{{route('admin.categories')}}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 text-sm">Cancel</a>
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-md">Update
                            Category</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
    <script>
        (function () {
            const input = document.getElementById('category-image');
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

        (function () {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            let slugEditedManually = slugInput.value.length > 0;

            function slugify(text) {
                return text
                    .toString()
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            nameInput.addEventListener('input', function () {
                if (!slugEditedManually) {
                    slugInput.value = slugify(nameInput.value);
                }
            });

            slugInput.addEventListener('input', function () {
                slugEditedManually = slugInput.value.length > 0;
            });
        })();
    </script>
    @endpush
</x-admin-layout>
