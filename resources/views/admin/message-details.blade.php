<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.messages') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Back to Messages
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Message from {{ $message->name }}</h1>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-3xl">
            <div class="grid md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Name</p>
                    <p class="text-gray-800">{{ $message->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                    <p class="text-gray-800"><a href="mailto:{{ $message->email }}" class="text-primary hover:underline">{{ $message->email }}</a></p>
                </div>
                @if ($message->phone)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Phone</p>
                        <p class="text-gray-800">{{ $message->phone }}</p>
                    </div>
                @endif
                @if ($message->subject)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Subject</p>
                        <p class="text-gray-800">{{ $message->subject }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Received</p>
                    <p class="text-gray-800">{{ $message->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Message</p>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
            </div>

            <div class="flex justify-end pt-6 mt-6 border-t">
                <a href="mailto:{{ $message->email }}" class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                    <i class="fa-solid fa-reply"></i> Reply by Email
                </a>
            </div>
        </div>

    </main>
</x-admin-layout>
