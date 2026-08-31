<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Hero Slider</h1>
                <p class="text-sm text-gray-500">Manage the homepage banner slides</p>
            </div>
            <a href="{{ route('admin.hero-slide.create') }}"
                class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add New Slide
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Image</th>
                            <th class="px-6 py-4">Title</th>
                            <th class="px-6 py-4">Button</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($heroSlides as $slide)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $slide->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <div class="w-16 h-12 bg-gray-50 rounded-lg flex items-center justify-center border overflow-hidden">
                                        <img src="{{ asset('uploads/hero-slides/' . $slide->image) }}"
                                            class="w-full h-full object-cover" alt="{{ $slide->title }}"
                                            onerror="this.src='https://placehold.co/64x48?text=Slide'">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">{{ $slide->title }}</span>
                                    @if ($slide->subtitle)
                                        <p class="text-xs text-gray-500">{{ $slide->subtitle }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $slide->button_text ?: '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($slide->status)
                                        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Active</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Deactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.hero-slide.edit', ['id' => $slide->id]) }}"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-red-500 transition flex items-center justify-center"
                                            onclick="deleteSlide(this, '{{ $slide->title }}', {{ $slide->id }})" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-images text-4xl mb-3 text-gray-300"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No hero slides yet</h3>
                                        <p class="text-sm mt-1">Add a slide to populate the homepage banner.</p>
                                        <a href="{{ route('admin.hero-slide.create') }}"
                                            class="mt-4 text-primary text-sm font-medium hover:underline">+ Add New Slide</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Delete Slide</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete "<span id="delete-slide-name" class="font-medium"></span>"? This cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>

    <form id="delete-slide-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const slideNameSpan = document.getElementById('delete-slide-name');
        const deleteForm = document.getElementById('delete-slide-form');
        const deleteUrlTemplate = "{{ route('admin.hero-slide.delete', ':id') }}";

        let slideIdToDelete = null;

        function deleteSlide(buttonElement, slideTitle, slideId) {
            slideIdToDelete = slideId;
            slideNameSpan.textContent = slideTitle || "this slide";
            deleteModal.classList.remove('hidden');
        }

        function closeModal() {
            deleteModal.classList.add('hidden');
            slideIdToDelete = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        deleteModal.addEventListener('click', function (event) {
            if (event.target === this) closeModal();
        });

        confirmBtn.addEventListener('click', function () {
            if (slideIdToDelete) {
                deleteForm.action = deleteUrlTemplate.replace(':id', slideIdToDelete);
                deleteForm.submit();
            }
        });
    </script>
</x-admin-layout>
