<x-app-layout>
    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center" style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">Cart</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{route('home.index')}}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">Cart</li>
            </ul>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4">
            @if ($items->isEmpty())
                <div id="empty-cart" class="text-center py-10">
                    <h2 class="text-2xl font-bold mb-4">There are no more items in your cart</h2>
                    <img src="{{ asset('assets/images/cart.png') }}" alt="Empty Cart" class="mx-auto mb-6 max-w-xs">
                    <p class="text-gray-500 mb-6">No item found in your cart</p>
                    <a href="{{route('shop.index')}}" class="inline-block bg-primary text-white px-6 py-2 rounded hover:bg-blue-600 transition">Continue Browsing</a>
                </div>
            @else
                <div id="cart-content" class="cart-wrapper">

                    <form id="cart-quantities-form" action="{{ route('cart.bulk-update') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <div class="overflow-x-auto mb-8">
                        <table class="w-full cart-table">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="py-4 px-4 text-left font-bold text-gray-700">Image</th>
                                    <th class="py-4 px-4 text-left font-bold text-gray-700">Product Information</th>
                                    <th class="py-4 px-4 text-left font-bold text-gray-700">Quantity</th>
                                    <th class="py-4 px-4 text-left font-bold text-gray-700">Total Price</th>
                                    <th class="py-4 px-4 text-left font-bold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($items as $item)
                                    <tr>
                                    <td class="py-4 px-4 product-thumb" data-label="Image">
                                        <a href="{{ route('product.show', $item['product']->slug) }}">
                                            @if ($item['product']->image)
                                                <img src="{{ asset('uploads/products/' . $item['product']->image) }}" class="w-20 h-20 object-cover rounded" alt="{{ $item['product']->name }}">
                                            @else
                                                <img src="{{ asset('assets/images/product/product-03.jpg') }}" class="w-20 h-20 object-cover rounded" alt="{{ $item['product']->name }}">
                                            @endif
                                        </a>
                                    </td>
                                    <td class="py-4 px-4" data-label="Product">
                                        <h6 class="font-bold text-gray-800"><a href="{{ route('product.show', $item['product']->slug) }}" class="hover:text-primary">{{$item['product']->name}}</a></h6>
                                        <div class="text-sm mt-1">
                                            @if ($item['product']->sale_price)
                                                <span class="line-through text-gray-400 mr-2">${{ number_format($item['product']->price, 2) }}</span>
                                                <span class="text-primary font-bold">${{ number_format($item['product']->sale_price, 2) }}</span>
                                            @else
                                                <span class="text-primary font-bold">${{ number_format($item['product']->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4" data-label="Quantity">
                                        <div class="flex border rounded w-max">
                                            <button type="button" class="qty-decrement px-3 py-1 hover:bg-gray-100">-</button>
                                            <input type="number" name="quantities[{{ $item['product']->id }}]"
                                                value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}"
                                                form="cart-quantities-form"
                                                class="qty-input w-12 text-center focus:outline-none">
                                            <button type="button" class="qty-increment px-3 py-1 hover:bg-gray-100">+</button>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-primary" data-label="Total">${{ number_format($item['subtotal'], 2) }}</td>
                                    <td class="py-4 px-4" data-label="Action">
                                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" onsubmit="return confirm('Remove this item from your cart?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition"><i class="fa-solid fa-trash-can text-xl"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center mb-12 gap-4">
                        <a href="{{ route('shop.index') }}" class="bg-sky-800 text-white px-6 py-3 rounded hover:bg-primary transition w-full sm:w-auto text-center">Continue Shopping</a>
                        <div class="flex gap-4 w-full sm:w-auto">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear your entire cart?')" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-gray-800 text-gray-800 px-6 py-3 rounded hover:bg-sky-800 hover:text-white transition w-full sm:w-auto text-center">Clear Cart</button>
                            </form>
                            <button type="submit" form="cart-quantities-form" class="border border-gray-800 text-gray-800 px-6 py-3 rounded hover:bg-sky-800 hover:text-white transition w-full sm:w-auto text-center">Update Cart</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded border">
                            <h4 class="font-bold text-lg mb-2">Coupon Code</h4>
                            @if ($appliedCoupon)
                                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded p-3">
                                    <div>
                                        <p class="text-sm font-bold text-green-700">{{ $appliedCoupon->code }}</p>
                                        <p class="text-xs text-green-600">
                                            {{ $appliedCoupon->type === 'percentage' ? rtrim(rtrim(number_format($appliedCoupon->value, 2), '0'), '.') . '%' : '$' . number_format($appliedCoupon->value, 2) }} off applied
                                        </p>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="text" name="code" placeholder="Enter coupon code..." class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                    <button type="submit" class="bg-sky-800 text-white px-6 py-3 rounded hover:bg-primary transition w-full">Apply Coupon</button>
                                </form>
                            @endif
                        </div>

                        <div class="bg-gray-50 p-6 rounded border">
                            <h4 class="font-bold text-lg mb-4">Cart Totals</h4>
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium">Subtotal</span>
                                    <span class="font-bold">${{ number_format($total, 2) }}</span>
                                </div>
                                @if ($discount > 0)
                                    <div class="flex justify-between border-b pb-2 text-green-600">
                                        <span class="font-medium">Discount ({{ $appliedCoupon->code }})</span>
                                        <span class="font-bold">-${{ number_format($discount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between pt-2">
                                    <span class="font-bold text-lg">Total</span>
                                    <span class="font-bold text-lg text-primary">${{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="block bg-primary text-white text-center px-6 py-3 rounded hover:bg-blue-600 transition w-full">Proceed To Checkout</a>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </section>

    <script>
        document.querySelectorAll('.qty-increment').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = button.previousElementSibling;
                const max = parseInt(input.max);
                if (parseInt(input.value) < max) {
                    input.value = parseInt(input.value) + 1;
                }
            });
        });

        document.querySelectorAll('.qty-decrement').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = button.nextElementSibling;
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });
    </script>
</x-app-layout>
