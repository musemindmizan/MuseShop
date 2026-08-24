<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(10);

        $categories = Category::select('id', 'name')->get();

        $brands = Brand::select('id', 'name')->get();

        return view('admin.products', compact('products', 'categories', 'brands'));
    }

    public function create() {
        return view('admin.product-create');
    }

    public function store() {

    }
}
