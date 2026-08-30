<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Coupon</h1>
            <div class="flex gap-2">
                <a href="{{route('admin.coupons')}}"
                    class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
                <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this coupon? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-2xl mx-auto">
            <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                        class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary uppercase">
                    @error('code')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select name="type" required class="w-full border px-4 py-2 rounded-lg outline-none bg-white">
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                            <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value *</label>
                        <input type="number" step="0.01" min="0.01" name="value" value="{{ old('value', $coupon->value) }}" required
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('value')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount</label>
                        <input type="number" step="0.01" min="0" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
                            placeholder="No minimum"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        @error('min_order_amount')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses</label>
                        <input type="number" min="1" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" placeholder="Unlimited"
                            class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-gray-500 mt-1">Used {{ $coupon->used_count }} time(s) so far.</p>
                        @error('max_uses')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}"
                        class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-gray-600">
                    @error('expires_at')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="status" name="status" value="1" {{ old('status', $coupon->status) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                    <label for="status" class="text-sm text-gray-700">Set as Active</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{route('admin.coupons')}}"
                        class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition text-sm">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-md">Update
                        Coupon</button>
                </div>
            </form>
        </div>
    </main>
</x-admin-layout>
