<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected const SESSION_KEY = 'cart';

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::findOrFail($productId);

        $cart = $this->raw();

        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        $cart[$productId] = min($newQuantity, max($product->stock, 0));

        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);

        $cart = $this->raw();

        if( $quantity <= 0 ) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($quantity, max($product->stock, 0));
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

    protected function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }
}
