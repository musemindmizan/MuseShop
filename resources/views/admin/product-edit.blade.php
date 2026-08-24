<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
            <div class="flex gap-2">
                <a href="{{route('admin.products')}}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back to Products
                </a>

                <form action="{{ route('admin.product.delete', $product->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this product? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Basic Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                                <input type="text" id="product-name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Modern Sofa"
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm"
                                    required>
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                <input type="text" id="product-slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="e.g. modern-sofa"
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm bg-gray-50">
                                @error('slug')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                                <textarea id="short_description" name="short_description" rows="3" maxlength="255" placeholder="Brief summary..."
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" name="description" rows="18" placeholder="Detailed description..."
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Pricing & Inventory</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Regular Price ($) *</label>
                                <input type="number" step="0.01" min="0" id="regular_price" name="regular_price" value="{{ old('regular_price', $product->price) }}" placeholder="0.00" required
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm">
                                @error('regular_price')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price ($)</label>
                                <input type="number" step="0.01" min="0" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00"
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm">
                                @error('sale_price')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                                <input type="text" id="SKU" name="SKU" value="{{ old('SKU', $product->SKU) }}" placeholder="Product SKU" required
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm">
                                @error('SKU')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                <input type="number" min="0" id="quantity" name="quantity" value="{{ old('quantity', $product->stock) }}" placeholder="Total items" required
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm">
                                @error('quantity')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Publish</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <select id="status" name="status"
                                    class="border rounded text-sm px-2 py-1 bg-white focus:outline-none">
                                    <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Draft</option>
                                    <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-primary focus:ring-primary">
                                <label for="featured" class="text-sm text-gray-700 cursor-pointer">This is a featured
                                    product</label>
                            </div>
                            <button type="submit"
                                class="w-full bg-primary hover:bg-blue-600 text-white py-2 rounded-lg text-sm font-medium transition mt-4 shadow">Update
                                Product</button>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Organization</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select id="category_id" name="category_id"
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm bg-white">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                <select id="brand_id" name="brand_id"
                                    class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:border-primary text-sm bg-white">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Product Image (Main)</h3>

                        @if ($product->image)
                            <div class="mb-4 h-40 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden p-2">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" class="max-w-full max-h-full object-contain" alt="Current image">
                            </div>
                        @endif

                        <label for="product-image" id="single-upload-label"
                            class="block border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Click to upload new image</p>
                            <input type="file" id="product-image" name="image" class="hidden"
                                accept="image/png, image/jpeg, image/jpg, image/webp">
                        </label>

                        <div id="single-preview-container"
                            class="hidden mt-4 relative h-48 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden group shadow-sm">
                            <img id="single-image-preview" src=""
                                class="max-w-full max-h-full object-contain">
                            <button type="button" id="remove-single-image"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-md w-7 h-7 flex items-center justify-center text-sm shadow-md hover:bg-red-600 transition focus:outline-none">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        @error('image')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Product Gallery Images</h3>

                        @php $currentGallery = json_decode($product->gallery ?? '[]', true) ?? []; @endphp
                        @if (count($currentGallery))
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                @foreach ($currentGallery as $galleryImage)
                                    <div class="h-24 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden">
                                        <img src="{{ asset('uploads/products/' . $galleryImage) }}" class="max-w-full max-h-full object-contain" alt="Gallery image">
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mb-3">Uploading new gallery images below will replace all of the above.</p>
                        @endif

                        <label for="product-images"
                            class="block border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Click to upload multiple gallery images</p>
                            <input type="file" id="product-images" name="images[]" class="hidden" multiple
                                accept="image/png, image/jpeg, image/jpg, image/webp">
                        </label>

                        <div id="gallery-preview-container" class="grid grid-cols-3 gap-3 mt-4">
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </main>

    @push('scripts')
    <script>
        (function () {
            const input = document.getElementById('product-image');
            const uploadLabel = document.getElementById('single-upload-label');
            const previewContainer = document.getElementById('single-preview-container');
            const preview = document.getElementById('single-image-preview');
            const removeBtn = document.getElementById('remove-single-image');

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadLabel.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });

            removeBtn.addEventListener('click', function () {
                input.value = '';
                preview.src = '';
                previewContainer.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
            });
        })();

        (function () {
            const input = document.getElementById('product-images');
            const previewContainer = document.getElementById('gallery-preview-container');
            let currentFiles = [];

            function render() {
                previewContainer.innerHTML = '';

                currentFiles.forEach(function (file, index) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative h-24 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden group shadow-sm';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'max-w-full max-h-full object-contain';
                        wrapper.appendChild(img);

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-md w-6 h-6 flex items-center justify-center text-xs shadow-md hover:bg-red-600 transition focus:outline-none';
                        removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                        removeBtn.addEventListener('click', function () {
                            currentFiles.splice(index, 1);
                            syncInput();
                            render();
                        });
                        wrapper.appendChild(removeBtn);

                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function syncInput() {
                const dataTransfer = new DataTransfer();
                currentFiles.forEach(function (file) {
                    dataTransfer.items.add(file);
                });
                input.files = dataTransfer.files;
            }

            input.addEventListener('change', function () {
                currentFiles = Array.from(input.files);
                render();
            });
        })();

        (function () {
            const nameInput = document.getElementById('product-name');
            const slugInput = document.getElementById('product-slug');
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
