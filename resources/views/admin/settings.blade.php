<x-admin-layout>
    <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Account Settings</h1>
            <p class="text-sm text-gray-500">Update your profile, security, and store preferences.</p>
        </div>

        <div class="w-full mx-auto">
            <div class="flex border-b border-gray-200 mb-8 overflow-x-auto no-scrollbar">
                <button
                    class="tab-btn active px-6 py-3 text-sm font-medium border-b-2 border-transparent transition whitespace-nowrap"
                    data-target="profile-tab">
                    <i class="fa-solid fa-user-gear mr-2"></i> Profile Info
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent transition whitespace-nowrap"
                    data-target="security-tab">
                    <i class="fa-solid fa-shield-halved mr-2"></i> Password & Security
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent transition whitespace-nowrap"
                    data-target="store-tab">
                    <i class="fa-solid fa-store mr-2"></i> Store Configuration
                </button>
            </div>

            <div id="profile-tab" class="tab-content block animate-fadeIn">
                <form action="{{ route('admin.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Personal Information</h3>

                        <div class="flex flex-col md:flex-row gap-8 items-start mb-8">
                            <div class="relative group">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('uploads/avatars/' . Auth::user()->avatar) }}"
                                        class="w-32 h-32 rounded-full border-4 border-gray-100 shadow-sm object-cover"
                                        alt="Profile">
                                @else
                                    <div class="w-32 h-32 rounded-full border-4 border-gray-100 shadow-sm bg-blue-100 text-blue-600 flex items-center justify-center text-3xl font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <label
                                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition text-white">
                                    <i class="fa-solid fa-camera"></i>
                                    <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">
                                </label>
                            </div>
                            <div class="flex-1 space-y-4 w-full">
                                @error('avatar')
                                    <p class="text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                            class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                                        @error('name')
                                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email
                                            Address</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                            class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                                        @error('email')
                                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                        class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                                    @error('phone')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-t pt-6">
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-md">Save
                                Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="security-tab" class="tab-content hidden animate-fadeIn">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Update Password</h3>
                    <form action="{{ route('admin.settings.password') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                            @error('current_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="new_password"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                            @error('new_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-primary text-white py-2.5 rounded-lg hover:bg-blue-600 transition font-medium shadow-sm">Update
                                Password</button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t">
                        <h4 class="text-sm font-bold text-red-600 mb-2">Two-Factor Authentication</h4>
                        <p class="text-xs text-gray-500 mb-4">Add an extra layer of security to your account.</p>
                        <button type="button" disabled title="Coming soon"
                            class="px-4 py-2 border border-gray-300 text-gray-400 rounded-lg text-xs font-bold cursor-not-allowed">Enable
                            2FA</button>
                    </div>
                </div>
            </div>

            <div id="store-tab" class="tab-content hidden animate-fadeIn">
                <form action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Store Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                                    <input type="text" name="store_name" value="{{ old('store_name', $setting->store_name) }}"
                                        class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                                    @error('store_name')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                                    <select name="currency"
                                        class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm bg-white">
                                        <option value="USD" {{ old('currency', $setting->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ old('currency', $setting->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="GBP" {{ old('currency', $setting->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    </select>
                                    @error('currency')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Store Status</label>
                                    <label class="flex items-center gap-3 py-2 cursor-pointer w-max">
                                        <span class="relative inline-block w-10 h-5">
                                            <input type="checkbox" name="status" value="1" class="peer hidden"
                                                {{ old('status', $setting->status) ? 'checked' : '' }}>
                                            <span
                                                class="absolute inset-0 bg-gray-300 peer-checked:bg-green-500 rounded-full transition duration-200 ease-in-out"></span>
                                            <span
                                                class="absolute top-0 left-0 w-5 h-5 bg-white border border-gray-300 rounded-full transform peer-checked:translate-x-5 transition-transform duration-200"></span>
                                        </span>
                                        <span class="text-sm text-gray-600">Online / Public</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Notifications
                                        Email</label>
                                    <input type="email" name="notification_email" value="{{ old('notification_email', $setting->notification_email) }}"
                                        class="w-full border px-4 py-2 rounded-lg focus:ring-1 focus:ring-primary outline-none text-sm">
                                    @error('notification_email')
                                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-8">
                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-md">Update
                                Store</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </main>

    <script>
        // Tabs Switching Logic
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active classes
                tabBtns.forEach(b => b.classList.remove('active', 'border-primary', 'text-primary'));
                tabContents.forEach(c => c.classList.add('hidden'));

                // Add active classes
                btn.classList.add('active', 'border-primary', 'text-primary');
                const target = btn.getAttribute('data-target');
                document.getElementById(target).classList.remove('hidden');
            });
        });
    </script>
</x-admin-layout>
