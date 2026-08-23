<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function products() {

        return view('admin.products');
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

    public function brandStore( Request $request ) {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

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

    public function brandUpdate( Request $request, $id ) {

        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

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
        $categories = Category::orderBy('id', 'DESC')->paginate(10);

        return view('admin.categories', compact('categories'));
    }

    public function categoryCreate() {

        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.category-create', compact('categories'));
    }

    public function categoryStore( Request $request ) {

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

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
}
