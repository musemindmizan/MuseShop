<x-app-layout>
    <div class="relative bg-sky-700 text-white h-64 flex items-center justify-center bg-cover bg-center"
        style="background-image: url('assets/images/page-banner.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-bold mb-2">My Account</h2>
            <ul class="flex justify-center space-x-2 text-sm">
                <li><a href="{{ route('home.index') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-primary">My Account</li>
            </ul>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">

                @include('user.sidebar')

                <div class="w-full lg:w-3/4">

                    <div id="dashboard" class="account-tab-content">
                        <div class="bg-white border rounded p-6">
                            <h4 class="text-xl font-bold mb-4">Dashboard</h4>
                            <div class="bg-blue-50 border-l-4 border-primary p-4 mb-6">
                                @auth
                                    <p>Hello, <strong>{{ Auth::user()->name }}</strong> (If Not
                                        <strong>{{ Auth::user()->name }} !</strong>
                                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            href="{{ route('logout') }}" class="text-primary hover:underline">Logout</a>)
                                    </p>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                                        @csrf
                                    </form>
                                @endauth
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                From your account dashboard, you can easily check & view your recent orders, manage your
                                shipping and billing addresses, and edit your password and account details.
                            </p>
                        </div>
                    </div>

                    <div id="orders" class="account-tab-content hidden">
                        <div class="bg-white border rounded p-6">
                            <h4 class="text-xl font-bold mb-6">Orders</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 border-b">
                                            <th class="p-4 font-bold">No</th>
                                            <th class="p-4 font-bold">Name</th>
                                            <th class="p-4 font-bold">Date</th>
                                            <th class="p-4 font-bold">Status</th>
                                            <th class="p-4 font-bold">Total</th>
                                            <th class="p-4 font-bold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @forelse ($orders as $order)
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'processing' => 'bg-blue-100 text-blue-800',
                                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <tr>
                                                <td class="p-4">{{ $loop->iteration }}</td>
                                                <td class="p-4">{{ $order->order_number }}</td>
                                                <td class="p-4">{{ $order->created_at->format('M d, Y') }}</td>
                                                <td class="p-4"><span
                                                        class="{{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded text-xs capitalize">{{ $order->status }}</span>
                                                </td>
                                                <td class="p-4">${{ number_format($order->total, 2) }}</td>
                                                <td class="p-4"><a href="{{ route('checkout.confirmation', $order) }}"
                                                        class="bg-primary text-white px-4 py-1 rounded hover:bg-blue-600 text-sm">View</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-6 text-center text-gray-500">You haven't
                                                    placed any orders yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="payment" class="account-tab-content hidden">
                        <div class="bg-white border rounded p-6">
                            <h4 class="text-xl font-bold mb-4">Payment Method</h4>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <p class="text-yellow-700">You Can't Save Your Payment Method yet.</p>
                            </div>
                        </div>
                    </div>

                    <div id="address" class="account-tab-content hidden">
                        <div class="bg-white border rounded p-6">
                            <h4 class="text-xl font-bold mb-6">Address</h4>
                            @if ($lastOrder)
                                <div class="border p-4 rounded bg-gray-50 max-w-md">
                                    <h5 class="font-bold text-lg mb-2">Last Used Address</h5>
                                    <address class="not-italic text-sm text-gray-600">
                                        <strong class="block text-gray-800 text-base mb-1">{{ $lastOrder->name }}</strong>
                                        {{ $lastOrder->address }}, {{ $lastOrder->locality }}@if ($lastOrder->landmark), {{ $lastOrder->landmark }}@endif<br>
                                        {{ $lastOrder->city }}, {{ $lastOrder->state }} {{ $lastOrder->postal_code }}<br>
                                        Mobile: {{ $lastOrder->phone }}
                                    </address>
                                </div>
                                <p class="text-xs text-gray-500 mt-4">A new delivery address can be entered on the
                                    checkout page for each order.</p>
                            @else
                                <p class="text-gray-500">No address on file yet — one will be saved after your first
                                    order.</p>
                            @endif
                        </div>
                    </div>

                    <div id="details" class="account-tab-content hidden">
                        <div class="bg-white border rounded p-6">
                            <h4 class="text-xl font-bold mb-6">Account Details</h4>
                            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block mb-1 text-sm">Name</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                        class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                    @error('name')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                        class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                    @error('email')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="bg-primary text-white px-6 py-3 rounded hover:bg-blue-600 transition shadow">Save
                                    Changes</button>
                            </form>

                            <form action="{{ route('password.update') }}" method="POST" class="space-y-4 pt-8 mt-8 border-t">
                                @csrf
                                @method('PUT')
                                <h5 class="text-lg font-bold mb-3">Password Change</h5>
                                <div class="space-y-4">
                                    <input type="password" name="current_password" placeholder="Current Password"
                                        class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                    @error('current_password', 'updatePassword')
                                        <p class="text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="password" name="password" placeholder="New Password"
                                            class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                            class="w-full border p-3 rounded focus:outline-none focus:border-primary">
                                    </div>
                                    @error('password', 'updatePassword')
                                        <p class="text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="bg-primary text-white px-6 py-3 rounded hover:bg-blue-600 transition shadow">Update
                                    Password</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        const accountTabBtns = document.querySelectorAll('.account-tab-btn');
        const accountTabContents = document.querySelectorAll('.account-tab-content');

        function showAccountTab(target) {
            accountTabBtns.forEach(b => b.classList.toggle('active', b.dataset.target === target));
            accountTabContents.forEach(c => c.classList.toggle('hidden', c.id !== target));
        }

        accountTabBtns.forEach(btn => {
            btn.addEventListener('click', () => showAccountTab(btn.dataset.target));
        });

        const hashTarget = window.location.hash.replace('#', '');
        if (hashTarget && document.getElementById(hashTarget)) {
            showAccountTab(hashTarget);
        }

        @if ($errors->any() || $errors->updatePassword->any())
            showAccountTab('details');
        @endif
    </script>

</x-app-layout>
