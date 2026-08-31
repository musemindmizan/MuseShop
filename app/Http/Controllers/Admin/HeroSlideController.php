<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ImageUploaderInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSlide\StoreHeroSlideRequest;
use App\Http\Requests\HeroSlide\UpdateHeroSlideRequest;
use App\Models\HeroSlide;

class HeroSlideController extends Controller
{
    protected ImageUploaderInterface $imageUploader;

    public function __construct(ImageUploaderInterface $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function index() {
        $heroSlides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.hero-slides', compact('heroSlides'));
    }

    public function create() {
        return view('admin.hero-slide-create');
    }

    public function store( StoreHeroSlideRequest $request ) {
        $heroSlide = new HeroSlide();

        $heroSlide->title = $request->title;
        $heroSlide->subtitle = $request->subtitle;
        $heroSlide->button_text = $request->button_text;
        $heroSlide->button_link = $request->button_link;
        $heroSlide->sort_order = $request->sort_order ?? 0;
        $heroSlide->status = $request->has('status') ? 1 : 0;
        $heroSlide->image = $this->imageUploader->upload($request->file('image'), 'uploads/hero-slides');

        $heroSlide->save();

        return redirect()->route('admin.hero-slides')->with('success', 'Hero Slide Created Successfully!');
    }

    public function edit($id) {
        $heroSlide = HeroSlide::findOrFail($id);

        return view('admin.hero-slide-edit', compact('heroSlide'));
    }

    public function update( UpdateHeroSlideRequest $request, $id ) {
        $heroSlide = HeroSlide::findOrFail($id);

        $heroSlide->title = $request->title;
        $heroSlide->subtitle = $request->subtitle;
        $heroSlide->button_text = $request->button_text;
        $heroSlide->button_link = $request->button_link;
        $heroSlide->sort_order = $request->sort_order ?? 0;
        $heroSlide->status = $request->has('status') ? 1 : 0;

        if( $request->hasFile('image') ) {
            $this->imageUploader->delete($heroSlide->image, 'uploads/hero-slides');
            $heroSlide->image = $this->imageUploader->upload($request->file('image'), 'uploads/hero-slides');
        }

        $heroSlide->save();

        return redirect()->route('admin.hero-slides')->with('success', 'Hero Slide Updated Successfully!');
    }

    public function destroy($id) {
        $heroSlide = HeroSlide::findOrFail($id);

        $this->imageUploader->delete($heroSlide->image, 'uploads/hero-slides');

        $heroSlide->delete();

        return redirect()->route('admin.hero-slides')->with('success', 'Hero Slide Deleted Successfully!');
    }
}
