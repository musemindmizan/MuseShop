<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $items = $this->cart->items();

        if( $items->isEmpty() ) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $this->cart->total();

        return view('checkout.index', compact('items', 'total'));
    }

    public function store( StoreCheckoutRequest $request )
    {
        $items = $this->cart->items();

        if( $items->isEmpty() ) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach( $items as $item ) {
            if( $item['quantity'] > $item['product']->stock ) {
                return redirect()->route('cart.index')
                    ->with('error', $item['product']->name . ' no longer has enough stock. Please update your cart.');
            }
        }

        $order = DB::transaction(function () use ($request, $items) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'locality' => $request->locality,
                'landmark' => $request->landmark,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->zip,
                'notes' => $request->notes,
                'payment_method' => $request->mode,
                'total' => $items->sum('subtotal'),
                'status' => 'pending',
            ]);

            foreach( $items as $item ) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.confirmation', $order)->with('success', 'Order placed successfully!');
    }

    public function confirmation( Order $order )
    {
        abort_if( $order->user_id !== auth()->id(), 403 );

        $order->load('items');

        return view('checkout.confirmation', compact('order'));
    }
}
