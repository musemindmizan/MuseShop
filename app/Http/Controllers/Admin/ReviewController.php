<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\BulkApproveReviewsRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    protected function filteredReviewsQuery() {
        $query = Review::with(['product', 'user']);

        if( request()->filled('search') ) {
            $search = request('search');

            $query->where(function ($query) use ($search) {
                $query->where('comment', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('product', fn ($query) => $query->where('name', 'LIKE', '%' . $search . '%'))
                    ->orWhereHas('user', fn ($query) => $query->where('name', 'LIKE', '%' . $search . '%'));
            });
        }

        if( request()->filled('rating') ) {
            $query->where('rating', request('rating'));
        }

        if( request()->filled('status') ) {
            $query->where('status', request('status'));
        }

        return $query;
    }

    public function index() {
        $reviews = $this->filteredReviewsQuery()->latest()->paginate(10)->withQueryString();

        return view('admin.reviews', compact('reviews'));
    }

    public function approve(Review $review) {
        $review->update(['status' => 'published']);

        return back()->with('success', 'Review approved successfully!');
    }

    public function unpublish(Review $review) {
        $review->update(['status' => 'pending']);

        return back()->with('success', 'Review unpublished successfully!');
    }

    public function bulkApprove(BulkApproveReviewsRequest $request) {
        $updatedCount = Review::whereIn('id', $request->ids)->update(['status' => 'published']);

        return back()->with('success', $updatedCount . ' Review(s) Approved Successfully!');
    }

    public function destroy(Review $review) {
        $review->delete();

        return redirect()->route('admin.reviews')->with('success', 'Review Deleted Successfully!');
    }
}
