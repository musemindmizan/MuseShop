<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(1);
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();


        return view('shop.index', compact('products', 'brands', 'categories'));
    }
}
