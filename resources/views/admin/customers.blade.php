<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Customers</h1>
                <p class="text-sm text-gray-500">Manage registered users and customer data</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.customers.export', request()->query()) }}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-export"></i> Export
                </a>
                <button type="button" disabled title="Coming soon"
                    class="bg-gray-300 text-gray-500 px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 cursor-not-allowed">
                    <i class="fa-solid fa-plus"></i> Add Customer
                </button>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form method="GET" action="{{ route('admin.customers') }}" class="flex flex-col md:flex-row gap-4 justify-between">
                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Name or Email...">
                    </div>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full md:w-40 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()"
                        class="w-full md:w-40 border px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-primary bg-white text-gray-600">
                        <option value="">Sort By</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="spent" {{ request('sort') == 'spent' ? 'selected' : '' }}>Highest Spent</option>
                    </select>

                    @if ( request('search') || request('status') || request('sort') )
                        <a href="{{ route('admin.customers') }}" class="text-sm text-gray-500 hover:text-primary self-center">Clear</a>
                    @endif
                </div>

                <input type="submit" value="Apply" class="hidden">
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                            </th>
                            <th class="px-6 py-4">Customer Name</th>
                            <th class="px-6 py-4">Contact Info</th>
                            <th class="px-6 py-4">Orders</th>
                            <th class="px-6 py-4">Total Spent</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Join Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">{{ $customer->name }}</p>
                                            <p class="text-xs text-gray-500">ID: #CUST-{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $customer->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->orders_count }} Orders</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">${{ number_format($customer->orders_sum_total ?? 0, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if ($customer->status)
                                        <span
                                            class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Active</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Blocked</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.customer.show', $customer->id) }}"
                                            class="w-8 h-8 rounded-full hover:bg-gray-100 text-gray-500 transition flex items-center justify-center"
                                            title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button type="button" disabled title="Coming soon"
                                            class="w-8 h-8 rounded-full text-gray-300 transition flex items-center justify-center cursor-not-allowed">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" disabled title="Coming soon"
                                            class="w-8 h-8 rounded-full text-gray-300 transition flex items-center justify-center cursor-not-allowed">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-users text-4xl mb-3 text-gray-300"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No customers found</h3>
                                        <p class="text-sm mt-1">Registered customers will show up here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $customers->links() }}
            </div>
        </div>

    </main>
</x-admin-layout>
