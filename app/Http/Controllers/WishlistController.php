<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected WishlistService $wishlist;
    protected CartService $cart;

    public function __construct(WishlistService $wishlist, CartService $cart)
    {
        $this->wishlist = $wishlist;
        $this->cart = $cart;
    }

    public function index()
    {
        $products = $this->wishlist->items();

        return view('wishlist.index', compact('products'));
    }

    public function toggle($productId)
    {
        $added = $this->wishlist->toggle((int) $productId);

        return back()->with('success', $added ? 'Added to wishlist!' : 'Removed from wishlist!');
    }

    public function destroy($productId)
    {
        $this->wishlist->remove((int) $productId);

        return back()->with('success', 'Removed from wishlist!');
    }

    public function moveToCart( Request $request, $productId )
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if( $product->stock <= 0 ) {
            return back()->with('error', $product->name . ' is out of stock.');
        }

        $this->cart->add((int) $productId, (int) ($request->quantity ?? 1));
        $this->wishlist->remove((int) $productId);

        return back()->with('success', 'Moved to cart!');
    }
}
