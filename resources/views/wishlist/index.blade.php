<x-app-layout>

    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center"
        style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">Wishlist</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{ route('home.index') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">Wishlist</li>
            </ul>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4">

            @if ($products->isEmpty())
                <div id="empty-wishlist" class="text-center py-10">
                    <h2 class="text-2xl font-bold mb-4">There are no more items in your wishlist</h2>
                    <img src="{{ asset('assets/images/wishlist.png') }}" alt="Empty Wishlist" class="mx-auto mb-6 max-w-xs">
                    <p class="text-gray-500 mb-6">No item found in your wishlist</p>
                    <a href="{{ route('shop.index') }}"
                        class="inline-block bg-primary text-white px-6 py-2 rounded hover:bg-blue-600 transition">Wishlist
                        Now</a>
                </div>
            @else
                <div id="wishlist-content" class="overflow-x-auto">
                    <table class="w-full wishlist-table">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Image</th>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Product Information</th>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Quantity</th>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Total Price</th>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Add to Cart</th>
                                <th class="py-4 px-4 text-left font-bold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($products as $product)
                                @php $unitPrice = $product->sale_price ?: $product->price; @endphp
                                <tr>
                                    <td class="py-4 px-4 product-thumb" data-label="Image">
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            @if ($product->image)
                                                <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                    class="w-20 h-20 object-cover rounded" alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ asset('assets/images/product/product-03.jpg') }}"
                                                    class="w-20 h-20 object-cover rounded" alt="{{ $product->name }}">
                                            @endif
                                        </a>
                                    </td>
                                    <td class="py-4 px-4" data-label="Product">
                                        <h6 class="font-bold text-gray-800">
                                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-primary">{{ $product->name }}</a>
                                        </h6>
                                        <div class="text-sm mt-1">
                                            @if ($product->sale_price)
                                                <span class="line-through text-gray-400 mr-2">${{ number_format($product->price, 2) }}</span>
                                                <span class="text-primary font-bold">${{ number_format($product->sale_price, 2) }}</span>
                                            @else
                                                <span class="text-primary font-bold">${{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        @if ($product->stock <= 0)
                                            <p class="text-xs text-red-500 mt-1">Out of Stock</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4" data-label="Quantity">
                                        <div class="flex border rounded w-max">
                                            <button type="button" class="qty-decrement px-3 py-1 hover:bg-gray-100">-</button>
                                            <input type="number" form="add-to-cart-{{ $product->id }}" name="quantity"
                                                value="1" min="1" max="{{ $product->stock }}"
                                                data-unit-price="{{ $unitPrice }}"
                                                class="qty-input w-12 text-center focus:outline-none" readonly>
                                            <button type="button" class="qty-increment px-3 py-1 hover:bg-gray-100">+</button>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-primary row-total" data-label="Total">${{ number_format($unitPrice, 2) }}</td>
                                    <td class="py-4 px-4" data-label="Add to Cart">
                                        <form id="add-to-cart-{{ $product->id }}" action="{{ route('wishlist.move-to-cart', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                                                class="bg-sky-800 text-white px-4 py-2 rounded hover:bg-primary transition text-sm disabled:opacity-50 disabled:cursor-not-allowed">Add
                                                to Cart</button>
                                        </form>
                                    </td>
                                    <td class="py-4 px-4" data-label="Action">
                                        <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" onsubmit="return confirm('Remove this item from your wishlist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition"><i
                                                    class="fa-solid fa-trash-can text-xl"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    @if ($products->isNotEmpty())
        <script>
            document.querySelectorAll('.qty-increment').forEach(function (button) {
                button.addEventListener('click', function () {
                    const row = button.closest('tr');
                    const input = row.querySelector('.qty-input');
                    const max = parseInt(input.max);
                    if (parseInt(input.value) < max) {
                        input.value = parseInt(input.value) + 1;
                        updateRowTotal(row);
                    }
                });
            });

            document.querySelectorAll('.qty-decrement').forEach(function (button) {
                button.addEventListener('click', function () {
                    const row = button.closest('tr');
                    const input = row.querySelector('.qty-input');
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateRowTotal(row);
                    }
                });
            });

            function updateRowTotal(row) {
                const input = row.querySelector('.qty-input');
                const unitPrice = parseFloat(input.dataset.unitPrice);
                const quantity = parseInt(input.value);
                row.querySelector('.row-total').textContent = '$' + (unitPrice * quantity).toFixed(2);
            }
        </script>
    @endif

</x-app-layout>
