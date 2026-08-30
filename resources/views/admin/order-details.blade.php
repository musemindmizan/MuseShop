<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Order {{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('order.invoice', $order->id) }}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i class="fa-solid fa-download"></i> Download Invoice
                </a>
                <a href="{{ route('order.index') }}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h3 class="font-bold text-gray-800">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">Quantity</th>
                                    <th class="px-6 py-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ asset('uploads/products/' . $item->product->image) }}"
                                                        class="w-10 h-10 rounded object-cover border" alt="{{ $item->product_name }}">
                                                @endif
                                                <div>
                                                    @if ($item->product)
                                                        <a href="{{ route('product.show', $item->product->slug) }}" class="font-medium text-gray-800 hover:text-primary text-sm">{{ $item->product_name }}</a>
                                                    @else
                                                        <span class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</span>
                                                        <span class="text-xs text-gray-400 block">(product no longer exists)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-800">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total</td>
                                    <td class="px-6 py-4 font-bold text-primary">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-2">Order Notes</h3>
                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Update Status</h3>
                    <form action="{{ route('order.update-status', $order->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'processing' => 'bg-blue-100 text-blue-700',
                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                'delivered' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-block {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} px-2.5 py-1 rounded-full text-xs font-semibold capitalize mb-2">{{ $order->status }}</span>
                        <select name="status" class="w-full border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit"
                            class="w-full bg-primary hover:bg-blue-600 text-white py-2 rounded-lg text-sm font-medium transition">Update Status</button>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Customer</h3>
                    <div class="space-y-1 text-sm">
                        <p class="font-medium text-gray-800">{{ $order->name }}</p>
                        <p class="text-gray-500">{{ $order->email }}</p>
                        <p class="text-gray-500">{{ $order->phone }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Shipping Address</h3>
                    <div class="text-sm text-gray-600 leading-relaxed">
                        <p>{{ $order->address }}, {{ $order->locality }}</p>
                        <p>{{ $order->landmark }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-3">Payment</h3>
                    <p class="text-sm text-gray-600">{{ $order->payment_method === 'cod' ? 'Cash On Delivery' : $order->payment_method }}</p>
                </div>
            </div>

        </div>

    </main>
</x-admin-layout>
