<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index() {
        $query = Product::query()->where('status', 1);

        if( request()->filled('search') ) {
            $query->where('name', 'LIKE', '%' . request('search') . '%');
        }

        if( request()->filled('categories') ) {
            $query->whereIn('category_id', request('categories'));
        }

        if( request()->filled('brands') ) {
            $query->whereIn('brand_id', request('brands'));
        }

        if( request()->filled('min_price') ) {
            $query->where('price', '>=', request('min_price'));
        }

        if( request()->filled('max_price') ) {
            $query->where('price', '<=', request('max_price'));
        }

        match (request('sort')) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'featured'   => $query->where('featured', 1)->latest(),
            'oldest'     => $query->oldest(),
            default      => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $brands = Brand::select('id', 'name')->where('status', 1)->get();

        $categories = Category::select('id', 'name')->where('status', 1)->get();

        return view('shop.index', compact('products', 'brands', 'categories'));
    }
}
