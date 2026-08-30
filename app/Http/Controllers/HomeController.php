<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;


class HomeController extends Controller
{
    public function index() {
        $categories = Category::where('status', 1)->get();

        $brands = Brand::where('status', 1)->get();

        $newProducts = Product::where('status', 1)->latest()->limit(4)->get();

        $featuredProducts = Product::where('status', 1)->where('featured', 1)->take(10)->get();

        return view('index', compact('categories', 'brands', 'newProducts', 'featuredProducts'));
    }
}
