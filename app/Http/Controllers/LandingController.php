<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        // Ambil review terbaik (rating 4-5) dengan limit untuk testimonials
        $reviews = Review::with(['user', 'order.service'])
            ->where('rating', '>=', 0)
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Ambil 10 review rating tertinggi
        $reviews = Review::with(['user', 'order.service'])
            ->where('rating', '>=', 0)
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Ambil user yang user_id-nya ada di review yang tadi diambil
        $userIds = $reviews->pluck('user_id')->unique();

        $users = User::whereIn('id', $userIds)->get();

        // Hitung statistik review untuk ditampilkan
        $reviewStats = [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating') ?? 0, 1),
            'five_star_count' => Review::where('rating', 5)->count(),
            'four_star_count' => Review::where('rating', 4)->count(),
            'total_customers' => Review::distinct('user_id')->count(),
        ];

        // Ambil layanan untuk ditampilkan
        $services = Service::orderBy('name')->get();

        return view('landing', compact('reviews', 'reviewStats', 'services', 'users'));
    }
}
