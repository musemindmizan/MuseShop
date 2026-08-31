<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Product Reviews</h1>
                <p class="text-sm text-gray-500">Moderate ratings and feedback from customers</p>
            </div>
            <button type="button" id="bulk-approve-btn"
                class="hidden border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition items-center gap-2 shadow-sm">
                <i class="fa-solid fa-check-double"></i> Approve Selected (<span id="bulk-selected-count">0</span>)
            </button>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <form method="GET" action="{{ route('admin.reviews') }}"
                    class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Search content or product...">
                    </div>

                    <select name="rating" onchange="this.form.submit()"
                        class="w-full md:w-48 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">All Ratings</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full md:w-48 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>

                    @if (request('search') || request('rating') || request('status'))
                        <a href="{{ route('admin.reviews') }}" class="text-sm text-gray-500 hover:text-primary self-center">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" id="select-all-reviews" class="rounded border-gray-300 text-primary focus:ring-primary">
                            </th>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Reviewer</th>
                            <th class="px-6 py-4">Rating</th>
                            <th class="px-6 py-4 w-1/3">Review</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($reviews as $review)
                            <tr class="hover:bg-gray-50 transition {{ $review->status === 'pending' ? 'bg-yellow-50/30' : '' }}">
                                <td class="px-6 py-4 align-top">
                                    <input type="checkbox" class="review-checkbox rounded border-gray-300 text-primary focus:ring-primary mt-1" value="{{ $review->id }}">
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $review->product->image ? asset('uploads/products/' . $review->product->image) : asset('assets/images/product/product-01.jpg') }}"
                                            class="w-12 h-12 rounded object-cover border" alt="{{ $review->product->name }}">
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm truncate w-32">{{ $review->product->name }}</p>
                                            <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="text-xs text-primary hover:underline">View Product</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">{{ number_format($review->rating, 1) }}</span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <p class="text-sm text-gray-600 whitespace-normal line-clamp-2">
                                        {{ $review->comment }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'published' => 'bg-green-100 text-green-700',
                                            'spam' => 'bg-gray-100 text-gray-600',
                                        ];
                                    @endphp
                                    <span class="{{ $statusColors[$review->status] ?? 'bg-gray-100 text-gray-600' }} px-2.5 py-1 rounded-full text-xs font-semibold capitalize">{{ $review->status }}</span>
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($review->status === 'pending')
                                            <form action="{{ route('admin.review.approve', $review->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-green-100 text-green-600 transition flex items-center justify-center" title="Approve">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.review.unpublish', $review->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-yellow-100 text-yellow-600 transition flex items-center justify-center" title="Unpublish">
                                                    <i class="fa-solid fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="w-8 h-8 rounded-full hover:bg-gray-100 text-red-500 transition flex items-center justify-center"
                                            onclick="deleteReview(this, '{{ $review->user->name }}', {{ $review->id }})" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        </div>

    </main>

    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="relative bg-white rounded-xl shadow-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Delete Review</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete the review from <span id="delete-review-name" class="font-semibold"></span>? This cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

    <form id="delete-review-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulk-approve-form" action="{{ route('admin.reviews.bulk-approve') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const reviewNameSpan = document.getElementById('delete-review-name');
        const deleteForm = document.getElementById('delete-review-form');
        const deleteUrlTemplate = "{{ route('admin.review.delete', ':id') }}";

        let reviewIdToDelete = null;

        function deleteReview(buttonElement, reviewerName, reviewId) {
            reviewIdToDelete = reviewId;

            reviewNameSpan.textContent = reviewerName || "this reviewer";

            deleteModal.classList.remove('hidden');
        }

        function closeModal() {
            deleteModal.classList.add('hidden');
            reviewIdToDelete = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        deleteModal.addEventListener('click', function (event) {
            if (event.target === this || event.target.classList.contains('bg-opacity-75')) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (reviewIdToDelete) {
                deleteForm.action = deleteUrlTemplate.replace(':id', reviewIdToDelete);
                deleteForm.submit();
            }
        });

        const selectAllCheckbox = document.getElementById('select-all-reviews');
        const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
        const bulkApproveBtn = document.getElementById('bulk-approve-btn');
        const bulkSelectedCount = document.getElementById('bulk-selected-count');
        const bulkApproveForm = document.getElementById('bulk-approve-form');

        function updateBulkApproveBar() {
            const checked = document.querySelectorAll('.review-checkbox:checked');

            if (checked.length > 0) {
                bulkApproveBtn.classList.remove('hidden');
                bulkApproveBtn.classList.add('flex');
                bulkSelectedCount.textContent = checked.length;
            } else {
                bulkApproveBtn.classList.add('hidden');
                bulkApproveBtn.classList.remove('flex');
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = reviewCheckboxes.length > 0 && checked.length === reviewCheckboxes.length;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                reviewCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });

                updateBulkApproveBar();
            });
        }

        reviewCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateBulkApproveBar);
        });

        bulkApproveBtn.addEventListener('click', function () {
            const selectedIds = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(function (checkbox) {
                return checkbox.value;
            });

            if (selectedIds.length === 0) return;

            bulkApproveForm.querySelectorAll('input[name="ids[]"]').forEach(function (input) {
                input.remove();
            });

            selectedIds.forEach(function (id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                bulkApproveForm.appendChild(input);
            });

            bulkApproveForm.submit();
        });
    </script>
</x-admin-layout>
