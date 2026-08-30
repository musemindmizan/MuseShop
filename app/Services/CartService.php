<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected const SESSION_KEY = 'cart';
    protected const COUPON_SESSION_KEY = 'applied_coupon';

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::findOrFail($productId);

        if( $product->stock <= 0 ) {
            return;
        }

        $cart = $this->raw();

        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        $cart[$productId] = min($newQuantity, $product->stock);

        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);

        $cart = $this->raw();

        if( $quantity <= 0 || $product->stock <= 0 ) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($quantity, $product->stock);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();

        unset($cart[$productId]);

        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
        $this->removeCoupon();
    }

    public function items(): Collection
    {
        $cart = $this->raw();

        if( empty($cart) ) {
            return collect();
        }

        return Product::whereIn('id', array_keys($cart))
            ->get()
            ->map(function (Product $product) use ($cart) {
                $quantity = $cart[$product->id];
                $price = $product->sale_price ?? $product->price;

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ];
            });
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function total(): float
    {
        return $this->items()->sum('subtotal');
    }

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if( ! $coupon ) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if( ! $coupon->isValid($this->total()) ) {
            return ['success' => false, 'message' => 'This coupon is not valid for your order.'];
        }

        session([self::COUPON_SESSION_KEY => $coupon->code]);

        return ['success' => true, 'message' => 'Coupon applied!'];
    }

    public function removeCoupon(): void
    {
        session()->forget(self::COUPON_SESSION_KEY);
    }

    public function appliedCoupon(): ?Coupon
    {
        $code = session(self::COUPON_SESSION_KEY);

        if( ! $code ) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if( ! $coupon || ! $coupon->isValid($this->total()) ) {
            return null;
        }

        return $coupon;
    }

    public function discount(): float
    {
        $coupon = $this->appliedCoupon();

        return $coupon ? $coupon->calculateDiscount($this->total()) : 0;
    }

    public function grandTotal(): float
    {
        return max($this->total() - $this->discount(), 0);
    }

    protected function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }
}
