<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
                <p class="text-sm text-gray-500">Manage and track customer orders</p>
            </div>
            <a href="{{ route('order.export', request()->query()) }}"
                class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-file-export"></i> Export All
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <form method="GET" action="{{ route('order.index') }}" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Order ID or Customer Name...">
                    </div>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full md:w-48 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">Order Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                        class="w-full md:w-48 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary text-gray-500">

                    @if ( request('search') || request('status') || request('date') )
                        <a href="{{ route('order.index') }}" class="text-sm text-gray-500 hover:text-primary self-center">Clear</a>
                    @endif

                    <input type="submit" value="Apply" class="hidden">
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-700">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($order->name, 0, 1)) }}</div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $order->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-green-50 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">{{ $order->payment_method === 'cod' ? 'COD' : $order->payment_method }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'shipped' => 'bg-indigo-100 text-indigo-700',
                                            'delivered' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span
                                        class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} px-2.5 py-1 rounded-full text-xs font-semibold capitalize">{{ $order->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('order.show', $order->id) }}"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500 transition flex items-center justify-center"
                                            title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('order.invoice', $order->id) }}"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500 transition flex items-center justify-center"
                                            title="Download Invoice">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-boxes-stacked text-4xl mb-3 text-gray-300"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No orders yet</h3>
                                        <p class="text-sm mt-1">Orders placed by customers will show up here.</p>
                                    </div>
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
