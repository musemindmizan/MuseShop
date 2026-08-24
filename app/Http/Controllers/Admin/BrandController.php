<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected ImageUploaderInterface $imageUploader;

    public function __construct(ImageUploaderInterface $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index() {
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

    public function create() {
        return view('admin.brand-create');
    }

    public function store( StoreBrandRequest $request ) {
        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $brand->logo = $this->imageUploader->upload($request->file('image'), 'uploads/brands');
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand Created Successfully!');
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id);

        return view('admin.brand-edit', compact('brand'));
    }

    public function update( UpdateBrandRequest $request, $id ) {

        $brand = Brand::findOrFail($id);

        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $this->imageUploader->delete($brand->logo, 'uploads/brands');
            $brand->logo = $this->imageUploader->upload($request->file('image'), 'uploads/brands');
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand Updated Successfully!');
    }

    public function destroy($id) {

        $brand = Brand::findOrFail($id);

        $this->imageUploader->delete($brand->logo, 'uploads/brands');

        $brand->delete();

        return redirect()->route('admin.brands')->with('success', 'Brand Deleted Successfully!');
    }
}
