<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFilmController extends Controller
{
    public function index()
    {
        $films = Film::with('genre')->latest()->paginate(15);
        return view('admin.films.index', compact('films'));
    }

    public function create()
    {
        $genres = Genre::all();
        return view('admin.films.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255|min:3',
            'description'   => 'required|string|min:10',
            'genre_id'      => 'required|exists:genres,id',
            'release_year'  => 'required|integer|min:1900|max:' . date('Y'),
            'duration'      => 'required|integer|min:1|max:600',
            'director'      => 'required|string|max:255',
            'poster_url'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'backdrop_url'  => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'video_url'     => 'nullable|url',
            'rating'        => 'nullable|numeric|min:0|max:10',
            'status'        => 'required|in:draft,published,archived',
        ]);

        // // 🔥 UPLOAD POSTER KE AZURE
        // $posterPath = $request->file('poster_url')->store('posters', 'azure');
        // $validated['poster_url'] = Storage::disk('azure')->url($posterPath);

        // // 🔥 UPLOAD BACKDROP KE AZURE (OPSIONAL)
        // if ($request->hasFile('backdrop_url')) {
        //     $backdropPath = $request->file('backdrop_url')->store('backdrops', 'azure');
        //     $validated['backdrop_url'] = Storage::disk('azure')->url($backdropPath);
        // }

         // ✅ Handle poster upload
        if ($request->hasFile('poster_url')) {
            $file = $request->file('poster_url');
            $path = $file->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        // ✅ Handle backdrop upload (NEW)
        if ($request->hasFile('backdrop_url')) {
            $file = $request->file('backdrop_url');
            $path = $file->store('backdrops', 'public');
            $validated['backdrop_url'] = '/storage/' . $path;
        }

        // ✅ CHECKBOX FLAGS
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_popular']  = $request->boolean('is_popular');
        $validated['is_hero']     = $request->boolean('is_hero');

        Film::create($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil ditambahkan');
    }

    public function edit(Film $film)
    {
        $genres = Genre::all();
        return view('admin.films.edit', compact('film', 'genres'));
    }

    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255|min:3',
            'description'   => 'required|string|min:10',
            'genre_id'      => 'required|exists:genres,id',
            'duration'      => 'required|integer|min:1|max:600',
            'release_year'  => 'required|integer|min:1900|max:' . date('Y'),
            'director'      => 'required|string|max:255',
            'poster_url'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'backdrop_url'  => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'video_url'     => 'nullable|url',
            'rating'        => 'nullable|numeric|min:0|max:10',
            'status'        => 'required|in:draft,published,archived',
        ]);

        // ✅ Handle poster upload
        if ($request->hasFile('poster_url')) {
            if ($film->poster_url && file_exists(public_path($film->poster_url))) {
                unlink(public_path($film->poster_url));
            }

            $file = $request->file('poster_url');
            $path = $file->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        } else {
            unset($validated['poster_url']);
        }


        // // 🔁 UPDATE POSTER (HAPUS YANG LAMA DI AZURE)
        // if ($request->hasFile('poster_url')) {
        //     if ($film->poster_url) {
        //         $this->deleteAzureFile($film->poster_url);
        //     }

        //     $posterPath = $request->file('poster_url')->store('posters', 'azure');
        //     $validated['poster_url'] = Storage::disk('azure')->url($posterPath);
        // }

        // // 🔁 UPDATE BACKDROP
        // if ($request->hasFile('backdrop_url')) {
        //     if ($film->backdrop_url) {
        //         $this->deleteAzureFile($film->backdrop_url);
        //     }

        //     $backdropPath = $request->file('backdrop_url')->store('backdrops', 'azure');
        //     $validated['backdrop_url'] = Storage::disk('azure')->url($backdropPath);
        // }

        // ✅ CHECKBOX FLAGS
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_popular']  = $request->boolean('is_popular');
        $validated['is_hero']     = $request->boolean('is_hero');

        $film->update($validated);

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil diperbarui');
    }

    // public function destroy(Film $film)
    // {
    //     // 🗑️ HAPUS FILE DI AZURE
    //     if ($film->poster_url) {
    //         $this->deleteAzureFile($film->poster_url);
    //     }

    //     if ($film->backdrop_url) {
    //         $this->deleteAzureFile($film->backdrop_url);
    //     }

    //     $film->delete();

    //     return redirect()->route('admin.films.index')
    //         ->with('success', 'Film berhasil dihapus');
    // }

    /**
     * 🧹 Helper hapus file Azure dari URL
     */

    public function destroy(Film $film)
    {
        // ✅ Hapus file poster saat hapus film
        if ($film->poster_url && file_exists(public_path($film->poster_url))) {
            unlink(public_path($film->poster_url));
        }

        $film->delete();

        return redirect()->route('admin.films.index')
            ->with('success', 'Film berhasil dihapus');
    }

    // private function deleteAzureFile(string $url): void
    // {
    //     $container = config('filesystems.disks.azure.container');
    //     $path = parse_url($url, PHP_URL_PATH);

    //     if ($path) {
    //         $blobPath = ltrim(str_replace("/{$container}/", '', $path), '/');
    //         Storage::disk('azure')->delete($blobPath);
    //     }
    // }
}
