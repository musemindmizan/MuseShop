<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    protected ImageUploaderInterface $imageUploader;

    public function __construct(ImageUploaderInterface $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index() {
        $setting = Setting::current();

        return view('admin.settings', compact('setting'));
    }

    public function updateProfile( Request $request ) {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if( $request->hasFile('avatar') ) {
            $this->imageUploader->delete($user->avatar, 'uploads/avatars');
            $user->avatar = $this->imageUploader->upload($request->file('avatar'), 'uploads/avatars');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword( Request $request ) {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateStore( Request $request ) {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'currency' => 'required|in:USD,EUR,GBP',
            'notification_email' => 'nullable|email|max:255',
        ]);

        Setting::current()->update([
            'store_name' => $request->store_name,
            'currency' => $request->currency,
            'status' => $request->has('status') ? 1 : 0,
            'notification_email' => $request->notification_email,
        ]);

        return back()->with('success', 'Store settings updated successfully!');
    }
}
