<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h1>
                <p class="text-sm text-gray-500">Customer since {{ $customer->created_at->format('M d, Y') }}</p>
            </div>
            <a href="{{ route('admin.customers') }}"
                class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500 mb-1">Email</p>
                <p class="font-bold text-gray-800">{{ $customer->email }}</p>
                <div class="flex gap-2 mt-2">
                    @if ($customer->email_verified_at)
                        <span class="inline-block bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Verified</span>
                    @else
                        <span class="inline-block bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-semibold">Unverified</span>
                    @endif
                    @if ($customer->status)
                        <span class="inline-block bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Active</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Blocked</span>
                    @endif
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500 mb-1">Total Orders</p>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500 mb-1">Total Spent</p>
                <p class="text-2xl font-bold text-primary">${{ number_format($customer->orders()->sum('total'), 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-gray-800">Order History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipped' => 'bg-indigo-100 text-indigo-700',
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-700">{{ $order->order_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} px-2.5 py-1 rounded-full text-xs font-semibold capitalize">{{ $order->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('order.show', $order->id) }}"
                                        class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500 transition inline-flex items-center justify-center"
                                        title="View Order">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    This customer hasn't placed any orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        </div>

    </main>
</x-admin-layout>
