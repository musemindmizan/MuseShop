<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add New Coupon</h1>
            <a href="{{route('admin.coupons')}}"
                class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Coupons
            </a>
        </div>

        <div class="max-w-2xl mx-auto">
            <form action="{{ route('admin.coupon.store') }}" method="POST"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. SUMMER25" required
                        class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary uppercase">
                    @error('code')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select name="type" required class="w-full border px-4 py-2 rounded-lg outline-none bg-white">
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                        <input type="number" step="0.01" min="0.01" name="value" value="{{ old('value') }}" required
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('value')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                        <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount') }}"
                            placeholder="No minimum"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('min_order_amount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses</label>
                        <input type="number" min="1" name="max_uses" value="{{ old('max_uses') }}" placeholder="Unlimited"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('max_uses')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                        class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-gray-600">
                    @error('expires_at')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                    <label for="status" class="text-sm text-gray-700">Set as Active</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{route('admin.coupons')}}"
                        class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition text-sm">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm">Save
                        Coupon</button>
                </div>
            </form>
        </div>
    </main>
</x-admin-layout>
