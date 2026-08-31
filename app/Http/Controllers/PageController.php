<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Setting;

class PageController extends Controller
{
    public function about() {
        $setting = Setting::current();

        return view('about', compact('setting'));
    }

    public function contact() {
        $setting = Setting::current();

        return view('contact', compact('setting'));
    }

    public function storeMessage(StoreContactMessageRequest $request) {
        ContactMessage::create($request->validated());

        return back()->with('success', 'Thanks for reaching out! We will get back to you soon.');
    }
}
