<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'order.service']);

        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('order.service', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('comment', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = $this->getReviewStatistics();

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load(['user', 'order.service']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review.'
            ], 500);
        }
    }

    /**
     * Bulk delete reviews
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        try {
            Review::whereIn('id', $request->review_ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reviews berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus reviews.'
            ], 500);
        }
    }

    /**
     * Get review statistics
     */
    private function getReviewStatistics()
    {
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating');

        $ratingDistribution = Review::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }
        ksort($ratingDistribution);

        $recentReviews = Review::where('created_at', '>=', now()->subDays(7))->count();
        $monthlyReviews = Review::where('created_at', '>=', now()->subDays(30))->count();

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 2),
            'rating_distribution' => $ratingDistribution,
            'recent_reviews' => $recentReviews,
            'monthly_reviews' => $monthlyReviews,
        ];
    }

    /**
     * Get chart data for reviews
     */
    public function getChartData()
    {
        // Daily reviews for the last 30 days
        $dailyReviews = Review::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('AVG(rating) as avg_rating')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates
        $dates = [];
        $counts = [];
        $ratings = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $dayData = $dailyReviews->firstWhere('date', $date);
            $counts[] = $dayData ? $dayData->count : 0;
            $ratings[] = $dayData ? round($dayData->avg_rating, 1) : 0;
        }

        return [
            'daily_reviews' => [
                'labels' => $dates,
                'counts' => $counts,
                'ratings' => $ratings
            ],
            'rating_distribution' => $this->getReviewStatistics()['rating_distribution']
        ];
    }

    /**
     * Export reviews data
     */
    public function export(Request $request)
    {
        // This would implement Excel/CSV export
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Export functionality will be implemented'
        ]);
    }
}
