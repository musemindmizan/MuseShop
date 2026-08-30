<x-app-layout>
    <style>
        .range-thumb-only {
            pointer-events: none;
        }
        .range-thumb-only::-webkit-slider-thumb {
            pointer-events: auto;
        }
        .range-thumb-only::-moz-range-thumb {
            pointer-events: auto;
        }
    </style>

    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center"
        style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">Shop</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{route('home.index')}}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">Shop</li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col lg:flex-row gap-8">

            <aside class="w-full lg:w-1/4 order-2 lg:order-1 space-y-8">

                <form id="shop-filter-form" method="GET" action="{{ route('shop.index') }}" class="space-y-8">
                <input type="hidden" name="sort" value="{{ request('sort') }}">

                @if ( request()->filled('search') || request()->filled('categories') || request()->filled('brands') || request()->filled('min_price') || request()->filled('max_price') )
                    <div class="bg-gray-50 p-6 rounded-lg border flex items-center justify-between">
                        <span class="text-sm text-gray-600">Filters applied</span>
                        <a href="{{ route('shop.index') }}"
                            class="text-sm font-medium text-primary hover:underline flex items-center gap-1">
                            <i class="fa fa-xmark"></i> Clear Filter
                        </a>
                    </div>
                @endif

                <div class="bg-gray-50 p-6 rounded-lg border">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product..."
                            class="w-full border p-3 rounded focus:outline-none focus:border-primary pr-10">
                        <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-primary"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h4 class="font-bold text-lg mb-4">Categories</h4>
                    <ul class="space-y-3">
                        @foreach ($categories as $category)
                            <li class="flex items-center">
                            <label class="flex items-center cursor-pointer hover:text-primary">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    class="peer hidden" onchange="this.form.submit()"
                                    {{ in_array($category->id, (array) request('categories', [])) ? 'checked' : '' }}>
                                <div
                                    class="w-4 h-4 border border-gray-300 rounded mr-3 flex items-center justify-center bg-white peer-checked:bg-primary peer-checked:border-primary transition">
                                </div>
                                <span class="peer-checked:text-primary">{{$category->name}}</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h4 class="font-bold text-lg mb-4">Brands</h4>
                    <ul class="space-y-3">
                        @foreach ($brands as $brand)
                            <li class="flex items-center">
                            <label class="flex items-center cursor-pointer hover:text-primary">
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                    class="peer hidden" onchange="this.form.submit()"
                                    {{ in_array($brand->id, (array) request('brands', [])) ? 'checked' : '' }}>
                                <div
                                    class="w-4 h-4 border border-gray-300 rounded mr-3 flex items-center justify-center bg-white peer-checked:bg-primary peer-checked:border-primary transition">
                                </div>
                                <span class="peer-checked:text-primary">{{$brand->name}}</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg border">
                    <h4 class="font-bold text-lg mb-4">Filter By Price</h4>
                    <div class="relative pt-6 pb-2">
                        <div class="relative w-full h-1 bg-gray-300 rounded">
                            <div class="absolute h-1 bg-primary rounded" id="range-track"></div>
                        </div>
                        <input type="range" name="min_price" min="0" max="500" value="{{ request('min_price', 0) }}"
                            class="range-thumb-only absolute w-full h-1 bg-transparent appearance-none top-6 left-0 z-20"
                            id="range-min">
                        <input type="range" name="max_price" min="0" max="500" value="{{ request('max_price', 500) }}"
                            class="range-thumb-only absolute w-full h-1 bg-transparent appearance-none top-6 left-0 z-20"
                            id="range-max">

                        <div class="flex justify-between mt-4 text-sm font-medium text-gray-600">
                            <span>$<span id="price-min-display">{{ request('min_price', 0) }}</span></span>
                            <span>$<span id="price-max-display">{{ request('max_price', 500) }}</span></span>
                        </div>
                    </div>
                </div>
                </form>

                {{-- <div class="bg-gray-50 p-6 rounded-lg border">
                    <h4 class="font-bold text-lg mb-4">Filter By Color</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <input type="checkbox" id="c1" class="hidden peer">
                            <label for="c1"
                                class="flex items-center cursor-pointer hover:text-primary peer-checked:text-primary group">
                                <span
                                    class="w-4 h-4 rounded-full bg-blue-500 mr-3 border border-gray-200 group-hover:shadow-md"></span>
                                Blue
                            </label>
                        </li>
                        <li class="flex items-center">
                            <input type="checkbox" id="c2" class="hidden peer">
                            <label for="c2"
                                class="flex items-center cursor-pointer hover:text-primary peer-checked:text-primary group">
                                <span
                                    class="w-4 h-4 rounded-full bg-green-500 mr-3 border border-gray-200 group-hover:shadow-md"></span>
                                Green
                            </label>
                        </li>
                        <li class="flex items-center">
                            <input type="checkbox" id="c3" class="hidden peer">
                            <label for="c3"
                                class="flex items-center cursor-pointer hover:text-primary peer-checked:text-primary group">
                                <span
                                    class="w-4 h-4 rounded-full bg-gray-500 mr-3 border border-gray-200 group-hover:shadow-md"></span>
                                Gray
                            </label>
                        </li>
                        <li class="flex items-center">
                            <input type="checkbox" id="c4" class="hidden peer">
                            <label for="c4"
                                class="flex items-center cursor-pointer hover:text-primary peer-checked:text-primary group">
                                <span
                                    class="w-4 h-4 rounded-full bg-black mr-3 border border-gray-200 group-hover:shadow-md"></span>
                                Black
                            </label>
                        </li>
                    </ul>
                </div> --}}

                {{-- <div class="bg-gray-50 p-6 rounded-lg border">
                    <h4 class="font-bold text-lg mb-4">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="#"
                            class="px-3 py-1 bg-white border rounded text-sm hover:bg-primary hover:text-white transition">Clothing</a>
                        <a href="#"
                            class="px-3 py-1 bg-white border rounded text-sm hover:bg-primary hover:text-white transition">Furniture</a>
                        <a href="#"
                            class="px-3 py-1 bg-white border rounded text-sm hover:bg-primary hover:text-white transition">Lights</a>
                        <a href="#"
                            class="px-3 py-1 bg-white border rounded text-sm hover:bg-primary hover:text-white transition">Modern</a>
                    </div>
                </div> --}}

            </aside>

            <div class="w-full lg:w-3/4 order-1 lg:order-2">

                <div
                    class="flex flex-col sm:flex-row justify-between items-center bg-white border p-4 rounded mb-8 shadow-sm">
                    <p class="text-sm mb-4 sm:mb-0">
                        Showing <span class="font-bold text-primary">{{$products->firstItem() ?? 0}} - {{$products->lastItem() ?? 0}}</span> of <span class="font-bold">{{$products->total()}}</span>
                        Results
                    </p>

                    <div class="flex items-center space-x-6">
                        <div class="flex space-x-2">
                            <button id="btn-grid"
                                class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white transition">
                                <i class="fa fa-th"></i>
                            </button>
                            <button id="btn-list"
                                class="w-8 h-8 flex items-center justify-center rounded bg-gray-200 hover:bg-primary hover:text-white transition">
                                <i class="fa fa-list"></i>
                            </button>
                        </div>

                        <div class="flex items-center">
                            <span class="mr-2 text-sm font-medium">Sort By:</span>
                            <select id="sort-select" class="border rounded p-1 text-sm focus:outline-none focus:border-primary">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="product-grid-view" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($products as $product)
                        <div class="group">
                        <div class="relative overflow-hidden bg-gray-100 rounded-lg mb-4">
                            <a href="details.php">
                                @if ( $product->image )
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{$product->name}}"
                                    class="w-full h-[300px] object-cover transition duration-500 group-hover:scale-105" />
                                @else
                                    <img src="assets/images/product/product-01.jpg" alt="Product"
                                    class="w-full h-[300px] object-cover transition duration-500 group-hover:scale-105" />
                                @endif
                            </a>
                            <div
                                class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button
                                    class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button
                                    class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </button>
                                <button
                                    class="w-10 h-10 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="text-lg font-medium hover:text-primary"><a href="details.php">{{$product->name}}</a></h4>
                            <p class="text-primary font-bold mt-1">${{$product->sale_price ? $product->sale_price : $product->price}}</p>
                        </div>
                    </div>
                    @empty
                        <p>No Product Available!</p>
                    @endforelse
                    
                </div>

                <div id="product-list-view" class="flex flex-col space-y-8 hidden">
                    @forelse ($products as $product)
                        <div
                        class="flex flex-col md:flex-row gap-6 bg-white border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="w-full md:w-1/3 relative bg-gray-100 rounded overflow-hidden">
                            <a href="details.php">
                                @if ($product->image)
                                    <img src="{{asset('uploads/products/' . $product->image)}}" alt="{{$product->name}}"
                                    class="w-full h-full object-cover">
                                @else
                                    <img src="assets/images/product/product-01.jpg" alt="Product"
                                    class="w-full h-full object-cover">
                                @endif
                            </a>
                        </div>
                        <div class="w-full md:w-2/3 flex flex-col justify-center">
                            <h4 class="text-xl font-bold hover:text-primary mb-2"><a href="details.php">{{$product->name}}</a></h4>
                            <p class="text-primary font-bold text-lg mb-4">${{$product->sale_price ? $product->sale_price : $product->price}}</p>
                            @if ($product->short_description)
                                <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                                {{$product->short_description}}
                            </p>
                            @endif
                            <div class="flex space-x-2">
                                <button
                                    class="px-4 py-2 border rounded hover:bg-primary hover:text-white transition"><i
                                        class="fa-regular fa-heart"></i></button>
                                <button
                                    class="px-4 py-2 border rounded hover:bg-primary hover:text-white transition"><i
                                        class="fa-solid fa-bag-shopping"></i> Add to Cart</button>
                                <button
                                    class="px-4 py-2 border rounded hover:bg-primary hover:text-white transition"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </div>
                    @empty
                        <p>No Product Available!</p>
                    @endforelse
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $products->onEachSide(1)->links('vendor.pagination.shop') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            const btnGrid = document.getElementById('btn-grid');
            const btnList = document.getElementById('btn-list');
            const gridView = document.getElementById('product-grid-view');
            const listView = document.getElementById('product-list-view');

            const activeClasses = ['bg-primary', 'text-white'];
            const inactiveClasses = ['bg-gray-200'];

            function showGrid() {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');

                btnGrid.classList.add(...activeClasses);
                btnGrid.classList.remove(...inactiveClasses);
                btnList.classList.remove(...activeClasses);
                btnList.classList.add(...inactiveClasses);

                localStorage.setItem('shopView', 'grid');
            }

            function showList() {
                listView.classList.remove('hidden');
                gridView.classList.add('hidden');

                btnList.classList.add(...activeClasses);
                btnList.classList.remove(...inactiveClasses);
                btnGrid.classList.remove(...activeClasses);
                btnGrid.classList.add(...inactiveClasses);

                localStorage.setItem('shopView', 'list');
            }

            btnGrid.addEventListener('click', showGrid);
            btnList.addEventListener('click', showList);

            let savedView = null;
            try {
                savedView = localStorage.getItem('shopView');
            } catch (e) {}

            if (savedView === 'list') {
                showList();
            }
        })();

        (function () {
            const sortSelect = document.getElementById('sort-select');

            sortSelect.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', sortSelect.value);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        })();

        (function () {
            const minRange = document.getElementById('range-min');
            const maxRange = document.getElementById('range-max');
            const track = document.getElementById('range-track');
            const minDisplay = document.getElementById('price-min-display');
            const maxDisplay = document.getElementById('price-max-display');
            const form = document.getElementById('shop-filter-form');

            function updateTrack() {
                const min = parseInt(minRange.min);
                const max = parseInt(minRange.max);

                if (parseInt(minRange.value) > parseInt(maxRange.value)) {
                    minRange.value = maxRange.value;
                }

                const minVal = parseInt(minRange.value);
                const maxVal = parseInt(maxRange.value);

                const minPercent = ((minVal - min) / (max - min)) * 100;
                const maxPercent = ((maxVal - min) / (max - min)) * 100;

                track.style.left = minPercent + '%';
                track.style.right = (100 - maxPercent) + '%';

                minDisplay.textContent = minVal;
                maxDisplay.textContent = maxVal;
            }

            minRange.addEventListener('input', updateTrack);
            maxRange.addEventListener('input', updateTrack);

            minRange.addEventListener('change', function () { form.submit(); });
            maxRange.addEventListener('change', function () { form.submit(); });

            updateTrack();
        })();
    </script>
</x-app-layout>
