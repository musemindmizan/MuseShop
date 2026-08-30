<x-app-layout>
    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center" style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">Order Confirmed</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{ route('home.index') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">Success</li>
            </ul>
        </div>
    </div>
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-gray-50 p-8 md:p-12 rounded border text-center">

                <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2">Thank You for Your Order!</h2>
                <p class="text-lg mb-8">Your order has been placed successfully. Your order ID is <span class="font-bold text-primary">#{{ $order->order_number }}</span>.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left mb-10">
                    <div class="bg-white p-6 rounded border">
                        <h4 class="font-bold border-b pb-2 mb-4">Order Details</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between"><span>Date:</span> <span class="font-medium">{{ $order->created_at->format('F d, Y') }}</span></li>
                            @if ($order->discount_amount > 0)
                                <li class="flex justify-between"><span>Coupon ({{ $order->coupon_code }}):</span> <span class="font-medium text-green-600">-${{ number_format($order->discount_amount, 2) }}</span></li>
                            @endif
                            <li class="flex justify-between"><span>Total:</span> <span class="font-medium text-primary">${{ number_format($order->total, 2) }}</span></li>
                            <li class="flex justify-between"><span>Payment Mode:</span> <span class="font-medium">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method }}</span></li>
                            <li class="flex justify-between"><span>Status:</span> <span class="font-medium capitalize">{{ $order->status }}</span></li>
                        </ul>
                    </div>

                    <div class="bg-white p-6 rounded border">
                        <h4 class="font-bold border-b pb-2 mb-4">Shipping To</h4>
                        <address class="text-sm not-italic leading-relaxed">
                            <span class="font-bold block">{{ $order->name }}</span>
                            {{ $order->address }}, {{ $order->locality }},<br>
                            {{ $order->landmark }}<br>
                            {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}<br>
                            Phone: {{ $order->phone }}
                        </address>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('shop.index') }}" class="bg-primary text-white px-8 py-3 rounded font-bold hover:bg-blue-600 transition shadow-lg">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
