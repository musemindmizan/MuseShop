<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Products</h1>
                <p class="text-sm text-gray-500">Manage your product catalog</p>
            </div>
            <a href="{{ route('admin.product.create') }}"
                class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <form method="GET" action="{{route('admin.products')}}" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Search product name...">
                    </div>

                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full md:w-48 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full md:w-40 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Draft</option>
                    </select>

                    @if ( request('search') || request('category_id') || request('status') !== null )
                        <a href="{{route('admin.products')}}" class="text-sm text-gray-500 hover:text-primary self-center">Clear</a>
                    @endif

                    <input type="submit" value="Apply" class="hidden">
                </form>

                <button
                    class="border border-gray-300 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-file-export"></i> Export
                </button>
            </div>
        </div>

        <div id="bulk-actions-bar" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4 flex items-center justify-between">
            <span class="text-sm text-blue-800"><span id="bulk-selected-count">0</span> product(s) selected</span>
            <button type="button" id="bulk-delete-btn"
                class="bg-red-600 hover:bg-red-500 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-trash"></i> Delete Selected
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">
                                <input type="checkbox" id="select-all-products" class="rounded border-gray-300 text-primary focus:ring-primary">
                            </th>
                            <th class="px-6 py-4">Product Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="product-checkbox rounded border-gray-300 text-primary focus:ring-primary" value="{{ $product->id }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($product->image)
                                            <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                class="w-12 h-12 rounded object-cover border" alt="Prod">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">ID: #{{ $product->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $product->category?->name ?? 'Uncategorized' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">${{ $product->sale_price ? $product->sale_price : $product->price }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->stock }}</td>
                                <td class="px-6 py-4">
                                    @if ($product->status)
                                        <span
                                            class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Published</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.product.edit', $product->id) }}"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-red-500 transition flex items-center justify-center"
                                            onclick="deleteProduct(this, '{{ $product->name }}', {{ $product->id }})" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-boxes-stacked text-4xl mb-3 text-gray-300"></i>
                                        <h3 class="text-lg font-medium text-gray-900">Products not available</h3>
                                        <p class="text-sm mt-1">You haven't added any products to your store yet.</p>
                                        <a href="{{ route('admin.product.create') }}"
                                            class="mt-4 text-primary hover:underline text-sm font-medium">
                                            Add your first product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        </div>

    </main>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Delete
                                    Product</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete <strong
                                            id="delete-product-name" class="text-gray-800">this product</strong>? All
                                        of its data will be permanently removed. This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" id="confirmDeleteBtn"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">Delete</button>
                        <button type="button" id="cancelDeleteBtn"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-product-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulk-delete-form" action="{{ route('admin.products.bulk-delete') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const productNameSpan = document.getElementById('delete-product-name');
        const deleteForm = document.getElementById('delete-product-form');
        const deleteUrlTemplate = "{{ route('admin.product.delete', ':id') }}";

        let productIdToDelete = null;

        function deleteProduct(buttonElement, productName, productId) {
            productIdToDelete = productId;

            productNameSpan.textContent = productName || "this product";

            deleteModal.classList.remove('hidden');
        }

        function closeModal() {
            deleteModal.classList.add('hidden');
            productIdToDelete = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        deleteModal.addEventListener('click', function(event) {
            if (event.target === this || event.target.classList.contains('bg-opacity-75')) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function() {
            if (productIdToDelete) {
                deleteForm.action = deleteUrlTemplate.replace(':id', productIdToDelete);
                deleteForm.submit();
            }
        });

        // Bulk select + delete
        const selectAllCheckbox = document.getElementById('select-all-products');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const bulkSelectedCount = document.getElementById('bulk-selected-count');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkActionsBar() {
            const checked = document.querySelectorAll('.product-checkbox:checked');

            if (checked.length > 0) {
                bulkActionsBar.classList.remove('hidden');
                bulkSelectedCount.textContent = checked.length;
            } else {
                bulkActionsBar.classList.add('hidden');
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = productCheckboxes.length > 0 && checked.length === productCheckboxes.length;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                productCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBulkActionsBar();
            });
        }

        productCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateBulkActionsBar);
        });

        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function () {
                const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(function (checkbox) {
                    return checkbox.value;
                });

                if (selectedIds.length === 0) return;

                if (!confirm('Are you sure you want to delete ' + selectedIds.length + ' selected product(s)? This cannot be undone.')) {
                    return;
                }

                bulkDeleteForm.querySelectorAll('input[name="ids[]"]').forEach(function (input) {
                    input.remove();
                });

                selectedIds.forEach(function (id) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    bulkDeleteForm.appendChild(input);
                });

                bulkDeleteForm.submit();
            });
        }
    </script>
</x-admin-layout>
