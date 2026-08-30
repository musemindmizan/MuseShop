<x-app-layout>
    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center"
        style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">{{ $product->name }}</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{ route('home.index') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-primary">Shop</a></li>
                <li>/</li>
                <li class="text-primary">{{ $product->name }}</li>
            </ul>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-12">

                <div class="w-full lg:w-1/2 overflow-hidden">
                    @php
                        $galleryImages = collect([$product->image])
                            ->merge(json_decode($product->gallery ?? '[]', true) ?? [])
                            ->filter();
                        if ($galleryImages->isEmpty()) {
                            $galleryImages = collect(['__placeholder__']);
                        }
                    @endphp
                    <div class="swiper-container gallery-top mb-4 rounded border bg-gray-50">
                        <div class="swiper-wrapper">
                            @foreach ($galleryImages as $galleryImage)
                                <div class="swiper-slide">
                                    <img src="{{ $galleryImage === '__placeholder__' ? asset('assets/images/product/product-01.jpg') : asset('uploads/products/' . $galleryImage) }}"
                                        class="w-full h-auto object-cover" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    @if ($galleryImages->count() > 1)
                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper">
                                @foreach ($galleryImages as $galleryImage)
                                    <div
                                        class="swiper-slide cursor-pointer border rounded overflow-hidden opacity-50 hover:opacity-100 transition">
                                        <img src="{{ $galleryImage === '__placeholder__' ? asset('assets/images/product/product-01.jpg') : asset('uploads/products/' . $galleryImage) }}"
                                            class="w-full" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="w-full lg:w-1/2">
                    <h2 class="text-3xl font-bold mb-4">{{ $product->name }}</h2>

                    <div class="flex items-center space-x-4 mb-4">
                        @if ($product->sale_price)
                            <span class="text-2xl text-primary font-bold">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xl text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="text-2xl text-primary font-bold">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="mb-6">
                        @if ($product->stock > 0)
                            <span class="text-green-600 font-medium text-sm"><i class="fa-solid fa-circle-check"></i> In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span class="text-red-500 font-medium text-sm"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</span>
                        @endif
                    </div>

                    @if ($product->short_description)
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex flex-wrap items-center gap-4 mb-8">
                        @csrf
                        @if ($product->stock > 0)
                            <div class="flex border rounded">
                                <button type="button" class="qty-btn px-3 py-2 hover:bg-gray-100" onclick="updateQty(-1)">-</button>
                                <input type="number" name="quantity" id="qty-input" value="1" min="1" max="{{ $product->stock }}"
                                    class="w-12 text-center focus:outline-none" readonly>
                                <button type="button" class="qty-btn px-3 py-2 hover:bg-gray-100" onclick="updateQty(1)">+</button>
                            </div>

                            <button type="submit"
                                class="bg-sky-700 text-white px-8 py-3 rounded hover:bg-primary transition font-medium">Add
                                To Cart</button>
                        @else
                            <button type="button" disabled
                                class="bg-gray-300 text-gray-500 px-8 py-3 rounded font-medium cursor-not-allowed">Out of Stock</button>
                        @endif

                        <div class="flex space-x-2">
                            <a href="#"
                                class="w-10 h-10 flex items-center justify-center border rounded hover:bg-primary hover:text-white transition"><i
                                    class="fa-regular fa-heart text-xl"></i></a>
                            <a href="#"
                                class="w-10 h-10 flex items-center justify-center border rounded hover:bg-primary hover:text-white transition"><i
                                    class="fa-solid fa-shuffle text-xl"></i></a>
                        </div>
                    </form>

                    <div class="space-y-2 text-sm text-gray-600 border-t pt-6">
                        <p><span class="font-bold text-gray-800 w-24 inline-block">SKU:</span> {{ $product->SKU }}</p>
                        @if ($product->category)
                            <p><span class="font-bold text-gray-800 w-24 inline-block">Category:</span>
                                <a href="{{ route('shop.index', ['categories' => [$product->category_id]]) }}" class="hover:text-primary">{{ $product->category->name }}</a></p>
                        @endif
                        @if ($product->brand)
                            <p><span class="font-bold text-gray-800 w-24 inline-block">Brand:</span>
                                <a href="{{ route('shop.index', ['brands' => [$product->brand_id]]) }}" class="hover:text-primary">{{ $product->brand->name }}</a></p>
                        @endif
                        <div class="flex items-center">
                            <span class="font-bold text-gray-800 w-24 inline-block">Share:</span>
                            <div class="flex space-x-4">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product.show', $product->slug)) }}" target="_blank" rel="noopener" class="hover:text-primary"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('product.show', $product->slug)) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener" class="hover:text-primary"><i class="fa-brands fa-twitter"></i></a>
                                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('product.show', $product->slug)) }}&description={{ urlencode($product->name) }}" target="_blank" rel="noopener" class="hover:text-primary"><i
                                        class="fa-brands fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-gray-50" id="details-tabs">
        <div class="container mx-auto px-4">
            <div class="flex justify-center border-b mb-8 overflow-x-auto">
                <button class="tab-btn active px-6 py-3 font-medium text-gray-600 hover:text-primary transition"
                    data-target="desc">Description</button>
                <button class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-primary transition"
                    data-target="review">Reviews</button>
            </div>

            <div class="max-w-4xl mx-auto">
                <div id="desc" class="tab-content">
                    @if ($product->description)
                        <div class="leading-relaxed">{!! nl2br(e($product->description)) !!}</div>
                    @else
                        <p class="text-gray-500">No description available for this product.</p>
                    @endif
                </div>

                <div id="review" class="tab-content hidden">
                    <div class="text-center py-10 text-gray-500">
                        <i class="fa-regular fa-comment-dots text-3xl mb-3"></i>
                        <p>Reviews aren't available yet — check back soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-end mb-8">
                    <h2 class="text-2xl font-bold">Related Products</h2>
                    <div class="flex space-x-2">
                        <button
                            class="related-prev w-10 h-10 rounded-full border hover:bg-primary hover:text-white transition flex items-center justify-center">
                            <i class="fa-solid fa-angle-left text-xl"></i>
                        </button>
                        <button
                            class="related-next w-10 h-10 rounded-full border hover:bg-primary hover:text-white transition flex items-center justify-center">
                            <i class="fa-solid fa-angle-right text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="swiper-container related-slider overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($relatedProducts as $related)
                            <div class="swiper-slide">
                                <div class="group text-center">
                                    <div class="relative overflow-hidden bg-gray-100 rounded mb-4">
                                        <a href="{{ route('product.show', $related->slug) }}">
                                            @if ($related->image)
                                                <img src="{{ asset('uploads/products/' . $related->image) }}"
                                                    class="w-full object-cover group-hover:scale-105 transition duration-500"
                                                    alt="{{ $related->name }}">
                                            @else
                                                <img src="{{ asset('assets/images/product/product-01.jpg') }}"
                                                    class="w-full object-cover group-hover:scale-105 transition duration-500"
                                                    alt="{{ $related->name }}">
                                            @endif
                                        </a>
                                        <div
                                            class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition">
                                            <a href="{{ route('product.show', $related->slug) }}"
                                                class="w-8 h-8 bg-white rounded shadow hover:bg-primary hover:text-white flex items-center justify-center transition"><i
                                                    class="fa-solid fa-magnifying-glass"></i></a>
                                            <form action="{{ route('cart.add', $related->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" {{ $related->stock <= 0 ? 'disabled' : '' }}
                                                    class="w-8 h-8 bg-white rounded shadow hover:bg-primary hover:text-white flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed"><i
                                                        class="fa-solid fa-bag-shopping"></i></button>
                                            </form>
                                            <a href="#"
                                                class="w-8 h-8 bg-white rounded shadow hover:bg-primary hover:text-white flex items-center justify-center transition"><i
                                                    class="fa-regular fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <h4 class="font-medium hover:text-primary"><a href="{{ route('product.show', $related->slug) }}">{{ $related->name }}</a></h4>
                                    <p class="text-primary font-bold mt-1">${{ number_format($related->sale_price ?: $related->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</x-app-layout>
