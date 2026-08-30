<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index() {
        $query = Coupon::query();

        if( request()->filled('search') ) {
            $query->where('code', 'LIKE', '%' . request('search') . '%');
        }

        if( request()->filled('status') ) {
            $query->where('status', request('status'));
        }

        $coupons = $query->latest()->paginate(10)->withQueryString();

        return view('admin.coupons', compact('coupons'));
    }

    public function create() {
        return view('admin.coupon-create');
    }

    public function store( StoreCouponRequest $request ) {

        Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?: null,
            'max_uses' => $request->max_uses ?: null,
            'expires_at' => $request->expires_at ?: null,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon Created Successfully!');
    }

    public function edit($id) {
        $coupon = Coupon::findOrFail($id);

        return view('admin.coupon-edit', compact('coupon'));
    }

    public function update( UpdateCouponRequest $request, $id ) {

        $coupon = Coupon::findOrFail($id);

        $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?: null,
            'max_uses' => $request->max_uses ?: null,
            'expires_at' => $request->expires_at ?: null,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon Updated Successfully!');
    }

    public function destroy($id) {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return redirect()->route('admin.coupons')->with('success', 'Coupon Deleted Successfully!');
    }
}
