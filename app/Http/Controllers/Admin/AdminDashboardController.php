<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\Film;
    use App\Models\Subscription;
    use App\Models\WatchHistory;
    use Illuminate\Support\Facades\DB;

    class AdminDashboardController extends Controller
    {

        public function index()
    {
        // 1. Data Statistik Utama (Kartu Atas)
        $totalUsers = User::count();
        $totalFilms = Film::count();
        $activeSubscriptions = Subscription::where('status', 'completed')
            ->where('expires_at', '>', now())
            ->count();
        $totalWatches = WatchHistory::count();

        // 2. Data Tabel (Recent Subscription)
        $recentSubscriptions = Subscription::with('user', 'plan')
            ->where('status', 'completed')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // 3. Data Grafik (INI BAGIAN PENTING YANG HILANG TADI)
        $usersData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dates = $usersData->pluck('date');
        $counts = $usersData->pluck('count');

        // === 4. TAMBAHAN BARU: FILM TERPOPULER (TOP 5) ===
        // withCount otomatis menghitung jumlah data di tabel watch_histories
        $popularFilms = Film::withCount('watchHistories')
            ->orderBy('watch_histories_count', 'desc') // Urutkan dari terbanyak
            ->take(5) // Ambil 5 saja
            ->get();

        // 4. Kirim Semua ke View
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalFilms',
            'activeSubscriptions',
            'totalWatches',
            'recentSubscriptions',
            'dates',   // <--- WAJIB ADA INI
            'counts',  // <--- WAJIB ADA INI
            'popularFilms'   // <--- WAJIB ADA INI
        ));
    }
}
