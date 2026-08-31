<x-app-layout>
    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center"
        style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">About Us</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{ route('home.index') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">About Us</li>
            </ul>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="bg-white border rounded p-8 space-y-6">
                <h3 class="text-2xl font-bold text-gray-800">Welcome to {{ $setting->store_name ?? config('app.name') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    We're dedicated to bringing you quality products at fair prices, backed by
                    friendly service and fast delivery. Our team hand-picks every item in the
                    catalog so you can shop with confidence.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Have a question about an order or a product? Our
                    <a href="{{ route('contact.index') }}" class="text-primary hover:underline">Contact page</a>
                    has everything you need to reach us.
                </p>
            </div>
        </div>
    </section>
</x-app-layout>
