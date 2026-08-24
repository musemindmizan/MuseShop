<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected ImageUploaderInterface $imageUploader;

    public function __construct(ImageUploaderInterface $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index() {
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

    public function create() {

        $categories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.category-create', compact('categories'));
    }

    public function store( StoreCategoryRequest $request ) {

        $category = new Category();

        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->parent_id = $request->parent_id ?: null;
        $category->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $category->image = $this->imageUploader->upload($request->file('image'), 'uploads/categories');
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category Created Successfully!');
    }

    public function edit($id) {
        $category = Category::findOrFail($id);

        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.category-edit', compact('category', 'categories'));
    }

    public function update( UpdateCategoryRequest $request, $id ) {

        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->parent_id = $request->parent_id ?: null;
        $category->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $this->imageUploader->delete($category->image, 'uploads/categories');
            $category->image = $this->imageUploader->upload($request->file('image'), 'uploads/categories');
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category Updated Successfully!');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);

        $this->imageUploader->delete($category->image, 'uploads/categories');

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category Deleted Successfully!');
    }
}
