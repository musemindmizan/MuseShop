<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\BulkDeleteProductsRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
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

    protected function filteredProductsQuery() {
        $query = Product::query();

        if( request()->filled('search') ) {
            $query->where('name', 'LIKE', '%' . request('search') . '%');
        }

        if( request()->filled('category_id') ) {
            $query->where('category_id', request('category_id'));
        }

        if( request()->filled('status') ) {
            $query->where('status', request('status'));
        }

        return $query;
    }

    public function index() {
        $products = $this->filteredProductsQuery()->latest()->paginate(10)->withQueryString();

        $categories = Category::select('id', 'name')->get();

        $brands = Brand::select('id', 'name')->get();

        return view('admin.products', compact('products', 'categories', 'brands'));
    }

    public function export() {
        $products = $this->filteredProductsQuery()->latest()->with(['category', 'brand'])->get();

        $filename = 'products-' . now()->format('Y-m-d-His') . '.csv';

        $columns = ['ID', 'Name', 'SKU', 'Category', 'Brand', 'Price', 'Sale Price', 'Stock', 'Status', 'Featured'];

        $callback = function () use ($products, $columns) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, $columns, escape: '\\');

            foreach( $products as $product ) {
                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->SKU,
                    $product->category?->name,
                    $product->brand?->name,
                    $product->price,
                    $product->sale_price,
                    $product->stock,
                    $product->status ? 'Published' : 'Draft',
                    $product->featured ? 'Yes' : 'No',
                ], escape: '\\');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
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

    public function edit($id) {
        $product = Product::findOrFail($id);

        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function update( UpdateProductRequest $request, $id ) {

        $product = Product::findOrFail($id);

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

        if( $request->hasFile('image') ) {
            $this->imageUploader->delete($product->image, 'uploads/products');
            $product->image = $this->imageUploader->upload($request->file('image'), 'uploads/products');
        }

        if( $request->hasFile('images') ) {
            foreach( json_decode($product->gallery ?? '[]', true) as $oldGalleryImage ) {
                $this->imageUploader->delete($oldGalleryImage, 'uploads/products');
            }

            $galleryNames = [];

            foreach( $request->file('images') as $galleryImage ) {
                $galleryNames[] = $this->imageUploader->upload($galleryImage, 'uploads/products');
            }

            $product->gallery = json_encode($galleryNames);
        }

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product Updated Successfully!');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);

        $this->imageUploader->delete($product->image, 'uploads/products');

        foreach( json_decode($product->gallery ?? '[]', true) as $galleryImage ) {
            $this->imageUploader->delete($galleryImage, 'uploads/products');
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product Deleted Successfully!');
    }

    public function bulkDestroy( BulkDeleteProductsRequest $request ) {

        $products = Product::whereIn('id', $request->ids)->get();

        foreach( $products as $product ) {
            $this->imageUploader->delete($product->image, 'uploads/products');

            foreach( json_decode($product->gallery ?? '[]', true) as $galleryImage ) {
                $this->imageUploader->delete($galleryImage, 'uploads/products');
            }
        }

        $deletedCount = Product::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.products')->with('success', $deletedCount . ' Product(s) Deleted Successfully!');
    }
}
