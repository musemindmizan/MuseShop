<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ImageUploaderInterface $imageUploader;

    public function __construct(ImageUploaderInterface $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index() {
        $products = Product::latest()->paginate(10);

        $categories = Category::select('id', 'name')->get();

        $brands = Brand::select('id', 'name')->get();

        return view('admin.products', compact('products', 'categories', 'brands'));
    }

    public function create() {
        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view('admin.product-create', compact('categories', 'brands'));
    }

    public function store( StoreProductRequest $request ) {

        $product = new Product();

        $product->category_id = $request->category_id ?: null;
        $product->brand_id = $request->brand_id ?: null;
        $product->name = $request->name;
        $product->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->price = $request->regular_price;
        $product->sale_price = $request->sale_price ?: null;
        $product->SKU = $request->SKU;
        $product->stock = $request->quantity;
        $product->status = $request->status ? 1 : 0;
        $product->featured = $request->has('featured') ? 1 : 0;

        $product->save();

        if( $request->hasFile('image') ) {
            $product->image = $this->imageUploader->upload($request->file('image'), 'uploads/products');
        }

        if( $request->hasFile('images') ) {
            $galleryNames = [];

            foreach( $request->file('images') as $galleryImage ) {
                $galleryNames[] = $this->imageUploader->upload($galleryImage, 'uploads/products');
            }

            $product->gallery = json_encode($galleryNames);
        }

        if( $product->isDirty() ) {
            $product->save();
        }

        return redirect()->route('admin.products')->with('success', 'Product Created Successfully!');
    }
}
