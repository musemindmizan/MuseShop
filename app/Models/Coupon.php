<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    public function isValid(float $subtotal = 0): bool
    {
        if( ! $this->status ) {
            return false;
        }

        if( $this->expires_at && $this->expires_at->isPast() ) {
            return false;
        }

        if( $this->max_uses !== null && $this->used_count >= $this->max_uses ) {
            return false;
        }

        if( $this->min_order_amount && $subtotal < $this->min_order_amount ) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        $discount = $this->type === 'percentage'
            ? $subtotal * ($this->value / 100)
            : $this->value;

        return min($discount, $subtotal);
    }
}
