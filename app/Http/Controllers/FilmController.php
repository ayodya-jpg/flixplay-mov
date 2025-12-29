<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::with('genre')
            ->latest()
            ->paginate(12);

        return view('films.index', compact('films'));
    }

    public function show(Film $film)
    {
       $canWatch = false;

        if (Auth::check()) {
            $canWatch = auth()->user()->canWatchFilm($film->id);
        }

        return view('films.show', compact('film', 'canWatch'));;
    }

    /**
     * TAMBAH FILM + UPLOAD POSTER & BACKDROP KE AZURE BLOB
     */
    public function store(Request $request)
    {
        // dd(
        // $request->all(),
        // $request->file('poster'),
        // $request->file('backdrop')
        // );

 /* ===============================
         | UPLOAD POSTER (WAJIB)
         =============================== */
        $posterPath = $request->file('poster')->store('posters', 'azure');
        $posterUrl  = Storage::disk('azure')->url($posterPath);

        /* ===============================
         | UPLOAD BACKDROP (OPSIONAL)
         =============================== */
        $backdropUrl = null;
        if ($request->hasFile('backdrop')) {
            $backdropPath = $request->file('backdrop')->store('backdrops', 'azure');
            $backdropUrl  = Storage::disk('azure')->url($backdropPath);
        }

        $request->validate([
            'title'     => 'required|string|max:255',
            'genre_id'  => 'required|exists:genres,id',
            'poster'    => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'backdrop'  => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'video_url' => 'required|url',
        ]);



        /* ===============================
         | SIMPAN KE DATABASE
         =============================== */
        Film::create([
            'title'        => $request->title,
            'genre_id'     => $request->genre_id,
            'poster_url'   => $posterUrl,
            'backdrop_url' => $backdropUrl,
            'video_url'    => $request->video_url,
            'status'       => $request->status ?? 'published',
            'is_featured'  => $request->boolean('is_featured'),
            'is_trending'  => $request->boolean('is_trending'),
            'is_popular'   => $request->boolean('is_popular'),
            'is_hero'      => $request->boolean('is_hero'),
        ]);

        return redirect()
            ->route('admin.films.index')
            ->with('success', 'Film berhasil ditambahkan');
    }

    /**
     * NONTON FILM
     */
    public function watch(Request $request, Film $film)
    {
         $user = auth()->user();

        // 🔐 PREMIUM → selalu boleh
        if ($user->hasActiveSubscription()) {

            WatchHistory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'film_id' => $film->id,
                ],
                [
                    'last_watched_at' => now(),
                    'is_completed' => true,
                ]
            );

            return redirect()->away($film->video_url);
        }

        // 🎁 FREE USER — cek apakah SUDAH PERNAH KLIK
        $alreadyWatched = WatchHistory::where('user_id', $user->id)
            ->where('film_id', $film->id)
            ->exists();

        if ($alreadyWatched) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Kesempatan menonton gratis untuk film ini sudah digunakan.');
        }

        // 🔒 LANGSUNG KUNCI SAAT KLIK
        WatchHistory::create([
            'user_id' => $user->id,
            'film_id' => $film->id,
            'last_watched_at' => now(),
            'is_completed' => true, // langsung true
        ]);

        return redirect()->away($film->video_url);
    }
}
