<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }
    
    public function brands() {
        $query = Brand::query();

        if( request()->filled('search') ) {
            $query->where('name', 'LIKE', '%' . request('search') . '%');
        }

        if( request()->filled('status') ) {
            $query->where('status', request('status'));
        }

        $brands = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.brands', compact('brands'));
    }

    public function brandCreate() {
        return view('admin.brand-create');
    }

    public function brandStore( StoreBrandRequest $request ) {
        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brands') , $imageName);
            $brand->logo = $imageName;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand Created Successfully!');
    }

    public function brandEdit($id) {
        $brand = Brand::findOrFail($id);

        return view('admin.brand-edit', compact('brand'));
    }

    public function brandUpdate( UpdateBrandRequest $request, $id ) {

        $brand = Brand::findOrFail($id);

        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            if( $brand->logo && file_exists(public_path('uploads/brands/' . $brand->logo)) ) {
                unlink(public_path('uploads/brands/' . $brand->logo));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brands'), $imageName);
            $brand->logo = $imageName;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand Updated Successfully!');
    }

    public function brandDelete($id) {

        $brand = Brand::findOrFail($id);

        if( $brand->logo && file_exists(public_path('uploads/brands/' . $brand->logo)) ) {
            unlink(public_path('uploads/brands/' . $brand->logo));
        }

        $brand->delete();

        return redirect()->route('admin.brands')->with('success', 'Brand Deleted Successfully!');
    }

    public function categories() {
        $query = Category::query();

        if( request()->filled('search') ) {
            $query->where('name', 'LIKE', '%' . request('search') . '%');
        }

        if( request()->filled('parent-category') ) {
            $query->whereHas('parent', function ($q) {
                $q->where('slug', request('parent-category'));
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();

        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories', compact('categories', 'parentCategories'));
    }

    public function categoryCreate() {

        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.category-create', compact('categories'));
    }

    public function categoryStore( StoreCategoryRequest $request ) {

        $category = new Category();

        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->parent_id = $request->parent_id ?: null;
        $category->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category Created Successfully!');
    }

    public function categoryEdit($id) {
        $category = Category::findOrFail($id);

        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.category-edit', compact('category', 'categories'));
    }

    public function categoryUpdate( UpdateCategoryRequest $request, $id ) {

        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->parent_id = $request->parent_id ?: null;
        $category->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            if( $category->image && file_exists(public_path('uploads/categories/' . $category->image)) ) {
                unlink(public_path('uploads/categories/' . $category->image));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category Updated Successfully!');
    }

    public function categoryDelete($id) {
        $category = Category::findOrFail($id);

        if( $category->image && file_exists(public_path('uploads/categories/' . $category->image)) ) {
            unlink(public_path('uploads/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category Deleted Successfully!');
    }

    public function products() {
        $products = Product::latest()->paginate(10);

        $categories = Category::select('id', 'name')->get();

        $brands = Brand::select('id', 'name')->get();

        return view('admin.products', compact('products', 'categories', 'brands'));
    }

    public function productCreate() {
        return view('admin.product-create');
    }

    public function productStore() {
        
    }

    

}
