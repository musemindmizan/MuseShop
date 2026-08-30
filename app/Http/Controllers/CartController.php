<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $items = $this->cart->items();

        $total = $this->cart->total();

        $appliedCoupon = $this->cart->appliedCoupon();

        $discount = $this->cart->discount();

        $grandTotal = $this->cart->grandTotal();

        return view('cart.index', compact('items', 'total', 'appliedCoupon', 'discount', 'grandTotal'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if( $product->stock <= 0 ) {
            return back()->with('error', $product->name . ' is out of stock.');
        }

        $this->cart->add((int) $productId, (int) ($request->quantity ?? 1));

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cart->update((int) $productId, (int) $request->quantity);

        return back()->with('success', 'Cart updated!');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        foreach( $request->quantities as $productId => $quantity ) {
            $this->cart->update((int) $productId, (int) $quantity);
        }

        return back()->with('success', 'Cart updated!');
    }

    public function destroy($productId)
    {
        $this->cart->remove((int) $productId);

        return back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        $this->cart->clear();

        return back()->with('success', 'Cart cleared!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->cart->applyCoupon($request->code);

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function removeCoupon()
    {
        $this->cart->removeCoupon();

        return back()->with('success', 'Coupon removed.');
    }
}
