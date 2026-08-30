<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function toggle(int $productId): bool
    {
        $wishlist = Auth::user()->wishlistProducts();

        if( $wishlist->where('product_id', $productId)->exists() ) {
            $wishlist->detach($productId);

            return false;
        }

        $wishlist->attach($productId);

        return true;
    }

    public function remove(int $productId): void
    {
        Auth::user()->wishlistProducts()->detach($productId);
    }

    public function has(int $productId): bool
    {
        if( ! Auth::check() ) {
            return false;
        }

        return Auth::user()->wishlistProducts()->where('product_id', $productId)->exists();
    }

    public function productIds(): array
    {
        if( ! Auth::check() ) {
            return [];
        }

        return Auth::user()->wishlistProducts()->pluck('products.id')->toArray();
    }

    public function items(): Collection
    {
        if( ! Auth::check() ) {
            return collect();
        }

        return Auth::user()->wishlistProducts()->latest('wishlists.created_at')->get();
    }

    public function count(): int
    {
        return count($this->productIds());
    }
}
