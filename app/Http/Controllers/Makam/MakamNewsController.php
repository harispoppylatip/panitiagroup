<?php

namespace App\Http\Controllers\Makam;

use App\Http\Controllers\Controller;
use App\Models\MakamNews;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MakamNewsController extends Controller
{
    public function index(): View
    {
        $news = MakamNews::orderBy('created_at', 'desc')->paginate(10);
        return view('makam.news.index', compact('news'));
    }

    public function create(): View
    {
        return view('makam.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'content', 'author', 'published_at']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('makam-news', 'public');
            $data['image_url'] = $imagePath;
        }

        MakamNews::create($data);

        return redirect()->route('makam.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $news = MakamNews::findOrFail($id);
        return view('makam.news.edit', compact('news'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        $news = MakamNews::findOrFail($id);
        $data = $request->only(['title', 'content', 'author', 'published_at']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($news->image_url) {
                Storage::disk('public')->delete($news->image_url);
            }
            $imagePath = $request->file('image')->store('makam-news', 'public');
            $data['image_url'] = $imagePath;
        }

        $news->update($data);

        return redirect()->route('makam.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function AmbilData(){
        $berita = MakamNews::all();
        $data = [];

        foreach ($berita as $item) {
            $data[] = [
                'id' => $item->id,
                'title' => $item->title,
                'author' => $item->author,
                'content' => $item->content,
                'image_url' => $item->image_url ? Storage::url($item->image_url) : null,
                'dibuat' => $item->published_at
            ];
        }
        return $data;
    }

    public function destroy(int $id): RedirectResponse
    {
        $news = MakamNews::findOrFail($id);

        // Hapus gambar jika ada
        if ($news->image_url) {
            Storage::disk('public')->delete($news->image_url);
        }

        $news->delete();

        return redirect()->route('makam.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
