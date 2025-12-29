<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Film;
use App\Models\Subscription;
use App\Models\WatchHistory;
use App\Models\SiteVisit; // ✅ Pastikan Model ini ada
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. STATISTIK KARTU ATAS
        // ==========================================
        $totalUsers = User::count();
        $totalFilms = Film::count();

        // Menghitung user yang langganannya aktif (completed & belum expired)
        $activeSubscriptions = Subscription::where('status', 'completed')
            ->where('expires_at', '>', now())
            ->count();

        $totalWatches = WatchHistory::count();

        // ==========================================
        // 2. TABEL RECENT SUBSCRIPTION
        // ==========================================
        $recentSubscriptions = Subscription::with('user', 'plan')
            ->where('status', 'completed')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // ==========================================
        // 3. GRAFIK USER BARU (SELAMANYA / ALL TIME)
        // ==========================================
        $usersData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Format tanggal agar lebih rapi di grafik (misal: 12 Jan 2024)
        $dates = $usersData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d M Y');
        });
        $counts = $usersData->pluck('count');

        // ==========================================
        // 4. GRAFIK TRAFIK PENGUNJUNG (SELAMANYA)
        // ==========================================
        // Mengambil data dari tabel site_visits yang diisi oleh Middleware TrackVisitor
        $trafficData = SiteVisit::orderBy('visit_date', 'asc')->get();

        $trafficDates = $trafficData->pluck('visit_date')->map(function ($date) {
            return Carbon::parse($date)->format('d M Y');
        });
        $trafficCounts = $trafficData->pluck('count');

        // ==========================================
        // 5. FILM TERPOPULER (TOP 5)
        // ==========================================
        $popularFilms = Film::withCount('watchHistories')
            ->orderBy('watch_histories_count', 'desc')
            ->take(5)
            ->get();

        // ==========================================
        // 6. KIRIM KE VIEW
        // ==========================================
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFilms',
            'activeSubscriptions',
            'totalWatches',
            'recentSubscriptions',
            'dates',         // Data Label Grafik User
            'counts',        // Data Angka Grafik User
            'trafficDates',  // Data Label Grafik Trafik
            'trafficCounts', // Data Angka Grafik Trafik
            'popularFilms'
        ));
    }
}
