<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Contact Messages</h1>
            <p class="text-sm text-gray-500">Messages submitted from the storefront Contact page</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">From</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Message</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($messages as $message)
                            <tr class="hover:bg-gray-50 transition {{ is_null($message->read_at) ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-4 align-top">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $message->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->email }}</p>
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-gray-700">{{ $message->subject ?: '—' }}</td>
                                <td class="px-6 py-4 align-top max-w-md">
                                    <p class="text-sm text-gray-600 truncate">{{ $message->message }}</p>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    @if (is_null($message->read_at))
                                        <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">Unread</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-semibold">Read</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-gray-500">{{ $message->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.message.show', $message->id) }}"
                                            class="w-8 h-8 rounded-full hover:bg-blue-100 text-primary transition flex items-center justify-center" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button type="button"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-red-500 transition flex items-center justify-center"
                                            onclick="deleteMessage(this, '{{ $message->name }}', {{ $message->id }})" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">No messages yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        </div>

    </main>

    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="relative bg-white rounded-xl shadow-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Delete Message</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete the message from <span id="delete-message-name" class="font-semibold"></span>? This cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

    <form id="delete-message-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const messageNameSpan = document.getElementById('delete-message-name');
        const deleteForm = document.getElementById('delete-message-form');
        const deleteUrlTemplate = "{{ route('admin.message.delete', ':id') }}";

        let messageIdToDelete = null;

        function deleteMessage(buttonElement, senderName, messageId) {
            messageIdToDelete = messageId;

            messageNameSpan.textContent = senderName || "this sender";

            deleteModal.classList.remove('hidden');
        }

        function closeModal() {
            deleteModal.classList.add('hidden');
            messageIdToDelete = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        deleteModal.addEventListener('click', function (event) {
            if (event.target === this || event.target.classList.contains('bg-opacity-75')) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (messageIdToDelete) {
                deleteForm.action = deleteUrlTemplate.replace(':id', messageIdToDelete);
                deleteForm.submit();
            }
        });
    </script>
</x-admin-layout>
