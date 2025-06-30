<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Display order history for the authenticated user
     */
    public function index(Request $request)
    {
        $query = Order::with(['service', 'review'])
            ->forUser(Auth::id())
            ->completed()
            ->where('payment_status', Order::PAYMENT_PAID);

        // Apply filters
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('has_review')) {
            if ($request->has_review === 'yes') {
                $query->has('review');
            } else {
                $query->doesntHave('review');
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get available services for filter
        $services = \App\Models\Service::orderBy('name')->get();

        // Get statistics
        $stats = $this->getHistoryStatistics();

        return view('member.history.index', compact('orders', 'services', 'stats'));
    }

    /**
     * Store a review for an order
     */
    public function storeReview(Request $request, Order $order)
    {
        // Ensure user can only review their own completed orders
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        if (!$order->canBeReviewed()) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak dapat direview'], 400);
        }

        // Check if review already exists
        if ($order->review) {
            return response()->json(['success' => false, 'message' => 'Review sudah ada untuk pesanan ini'], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $review = Review::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil disimpan!',
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'rating_text' => $review->rating_text,
                    'rating_stars' => $review->rating_stars,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d M Y, H:i')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a review
     */
    public function updateReview(Request $request, Order $order)
    {
        // Ensure user can only update their own reviews
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        $review = $order->review;
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan'], 404);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil diupdate!',
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'rating_text' => $review->rating_text,
                    'rating_stars' => $review->rating_stars,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d M Y, H:i')
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review data for editing
     */
    public function getReview(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan'], 403);
        }

        $review = $order->review;
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
            ]
        ]);
    }

    /**
     * Get history statistics
     */
    private function getHistoryStatistics()
    {
        $userId = Auth::id();

        return [
            'total_completed' => Order::forUser($userId)->completed()->where('payment_status', Order::PAYMENT_PAID)->count(),
            'total_reviewed' => Review::forUser($userId)->count(),
            'average_rating' => Review::forUser($userId)->avg('rating'),
            'total_spent' => Order::forUser($userId)->completed()->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
        ];
    }
}
