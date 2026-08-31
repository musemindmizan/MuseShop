<x-app-layout>

    <section class="relative bg-gray-50 overflow-hidden">
        <div class="swiper-container main-slider h-[500px] lg:h-[600px]">
            <div class="swiper-wrapper">
                <div class="swiper-slide flex items-center justify-center">
                    <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center h-full">
                        <div class="text-center lg:text-left space-y-4 animate-fadeIn">
                            <h2 class="text-4xl lg:text-6xl font-bold text-gray-800 leading-tight">New Stylish <br />
                                Decor Furniture</h2>
                            <p class="text-lg text-gray-600">Unique Furniture Style Design for Your Family</p>
                            <a href="{{ route('shop.index') }}"
                                class="inline-block bg-primary text-white px-8 py-3 rounded hover:bg-blue-600 transition shadow-lg mt-4">Purchase
                                Now</a>
                        </div>
                        <div class="hidden lg:block">
                            <img src="assets/images/slider/slider-item-1.png" alt="Furniture" class="mx-auto" />
                        </div>
                    </div>
                </div>
                <div class="swiper-slide flex items-center justify-center">
                    <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center h-full">
                        <div class="text-center lg:text-left space-y-4">
                            <h2 class="text-4xl lg:text-6xl font-bold text-gray-800 leading-tight">Modern Living <br />
                                Room Sets</h2>
                            <p class="text-lg text-gray-600">Comfort and Style combined.</p>
                            <a href="{{ route('shop.index') }}"
                                class="inline-block bg-primary text-white px-8 py-3 rounded hover:bg-blue-600 transition shadow-lg mt-4">Shop
                                Now</a>
                        </div>
                        <div class="hidden lg:block">
                            <img src="assets/images/slider/slider-item-2.png" alt="Furniture" class="mx-auto" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-10 text-center uppercase tracking-widest text-gray-800">Popular Categories
            </h2>

            <div class="swiper-container category-slider overflow-hidden">
                <div class="swiper-wrapper pb-10">
                    @foreach ($categories as $category)
                        <div class="swiper-slide text-center group">
                            <a href="#"
                                class="block bg-white rounded-2xl p-6 transition-all duration-300 border border-transparent hover:border-gray-100 hover:shadow-2xl hover:-translate-y-2">
                                <img src="{{ asset($category->image ? 'uploads/categories/' . $category->image : 'assets/images/category.png') }}"
                                    class="mx-auto w-24 h-24 object-contain opacity-80 group-hover:opacity-100 transition-opacity duration-300"
                                    alt="{{ $category->name }}">
                            </a>
                            <h4
                                class="mt-5 font-semibold text-gray-600 group-hover:text-primary transition-colors uppercase text-xs tracking-widest">
                                {{ $category->name }}
                            </h4>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 border rounded-lg hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-1.png') }}" alt="Free Shipping"
                        class="mx-auto mb-4 h-16" />
                    <h3 class="text-xl font-bold mb-2">Free Shipping</h3>
                    <p class="text-sm text-gray-500">Get 10% cash back, free shipping, free returns, and more at 1000+
                        top retailers!</p>
                </div>
                <div class="text-center p-6 border rounded-lg hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-2.png') }}" alt="Safe Payment"
                        class="mx-auto mb-4 h-16" />
                    <h3 class="text-xl font-bold mb-2">Safe Payment</h3>
                    <p class="text-sm text-gray-500">Get 10% cash back, free shipping, free returns, and more at 1000+
                        top retailers!</p>
                </div>
                <div class="text-center p-6 border rounded-lg hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-3.png') }}" alt="Online Support"
                        class="mx-auto mb-4 h-16" />
                    <h3 class="text-xl font-bold mb-2">Online Support</h3>
                    <p class="text-sm text-gray-500">Get 10% cash back, free shipping, free returns, and more at 1000+
                        top retailers!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-10 uppercase tracking-widest">Our Partner Brands
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center">
                @foreach ($brands as $brand)
                    <a href="/brands/the-oak-studio" class="flex flex-col items-center group">
                        <div
                            class="w-32 h-32 flex items-center justify-center p-4 rounded-xl grayscale group-hover:grayscale-0 group-hover:shadow-xl group-hover:bg-white transition-all duration-300 border border-transparent group-hover:border-gray-100">
                            <img src="{{ asset($brand->logo ? 'uploads/brands/' . $brand->logo : 'assets/images/brand.png') }}"
                                alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain">
                        </div>
                        <p
                            class="mt-4 text-sm font-semibold text-gray-500 group-hover:text-primary transition-colors uppercase tracking-wider text-center">
                            {{ $brand->name }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">New Products</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($newProducts as $product)
                    <div class="group relative">
                        <div class="relative overflow-hidden bg-gray-100 rounded-lg">
                            <a href="{{ route('product.show', $product->slug) }}">
                                @if ($product->image)
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-[300px] object-cover transition duration-500 group-hover:scale-105" />
                                @else
                                    <img src="{{ asset('assets/images/product/product-01.jpg') }}" alt="{{ $product->name }}"
                                        class="w-full h-[300px] object-cover transition duration-500 group-hover:scale-105" />
                                @endif
                            </a>
                            <div
                                class="absolute top-4 right-4 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition duration-300 transform translate-x-4 group-hover:translate-x-0">
                                <a href="{{ route('product.show', $product->slug) }}"
                                    class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition"><i
                                        class="fas fa-search"></i></a>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                                        class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed"><i
                                            class="fas fa-shopping-bag"></i></button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition {{ in_array($product->id, $wishlistProductIds) ? 'text-red-500' : '' }}"><i
                                            class="{{ in_array($product->id, $wishlistProductIds) ? 'fas' : 'far' }} fa-heart"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <h4 class="text-lg font-medium hover:text-primary transition"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h4>
                            <p class="text-primary font-bold mt-1">${{ number_format($product->sale_price ?: $product->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="relative group overflow-hidden rounded-lg">
                    <img src="{{ asset('assets/images/banner/banner-05.jpg') }}" alt="Banner"
                        class="w-full transition duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 flex flex-col justify-center items-start p-8">
                        <h6 class="text-sm font-bold uppercase text-gray-500 mb-2">High-Quality</h6>
                        <h3 class="text-2xl font-bold mb-4">New Kitchen <br> Furniture</h3>
                        <a href="{{ route('shop.index') }}"
                            class="bg-primary text-white px-6 py-2 rounded hover:bg-blue-700 transition">Shop Now</a>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-lg">
                    <img src="{{ asset('assets/images/banner/banner-06.jpg') }}" alt="Banner"
                        class="w-full transition duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 flex flex-col justify-center items-start p-8">
                        <h6 class="text-sm font-bold uppercase text-gray-500 mb-2">Best-Quality</h6>
                        <h3 class="text-2xl font-bold mb-4">Bed Room <br> Furniture</h3>
                        <a href="{{ route('shop.index') }}"
                            class="bg-primary text-white px-6 py-2 rounded hover:bg-blue-700 transition">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-100 overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold">Chair Collection <span class="text-primary">50%</span> Off</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipisicing sed do eiusmol tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad mini veniam.
                    </p>

                    <div class="flex space-x-4 text-center" id="countdown-timer">
                        <div class="bg-white p-4 rounded shadow w-20">
                            <span class="block text-2xl font-bold text-primary" id="days">00</span>
                            <span class="text-xs text-gray-500 uppercase">Days</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow w-20">
                            <span class="block text-2xl font-bold text-primary" id="hours">00</span>
                            <span class="text-xs text-gray-500 uppercase">Hrs</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow w-20">
                            <span class="block text-2xl font-bold text-primary" id="minutes">00</span>
                            <span class="text-xs text-gray-500 uppercase">Mins</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow w-20">
                            <span class="block text-2xl font-bold text-primary" id="seconds">00</span>
                            <span class="text-xs text-gray-500 uppercase">Secs</span>
                        </div>
                    </div>

                    <a href="{{ route('shop.index') }}"
                        class="inline-block bg-primary text-white px-8 py-3 rounded hover:bg-blue-600 transition shadow-lg">Shop
                        Now</a>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/images/countdown.png') }}" alt="Chair"
                        class="relative z-10 w-full max-w-md mx-auto" />
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 mb-8">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-bold">Featured Products</h2>
                <div class="flex space-x-2">
                    <button
                        class="feat-prev w-10 h-10 rounded-full border border-gray-300 hover:bg-primary hover:border-primary hover:text-white transition flex items-center justify-center"><i
                            class="fa-solid fa-angle-left text-xl"></i></button>
                    <button
                        class="feat-next w-10 h-10 rounded-full border border-gray-300 hover:bg-primary hover:border-primary hover:text-white transition flex items-center justify-center"><i
                            class="fa-solid fa-angle-right text-xl"></i></button>
                </div>
            </div>

            <div class="swiper-container featured-slider overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($featuredProducts as $product)
                        <div class="swiper-slide">
                            <div class="group text-center">
                                <div class="overflow-hidden rounded bg-gray-100 mb-4 relative">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        @if ($product->image)
                                            <img src="{{ asset('uploads/products/' . $product->image) }}"
                                                class="w-full object-cover group-hover:scale-105 transition duration-500"
                                                alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('assets/images/product/product-12.jpg') }}"
                                                class="w-full object-cover group-hover:scale-105 transition duration-500"
                                                alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                </div>
                                <h4 class="font-medium"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h4>
                                <p class="text-primary font-bold">${{ number_format($product->sale_price ?: $product->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
