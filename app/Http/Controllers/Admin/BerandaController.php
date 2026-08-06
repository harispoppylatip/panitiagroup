<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{
    private function processImage($request, $fieldName, $oldImageUrl = null)
    {
        $imageField = $fieldName . '_image';

        if ($request->hasFile($imageField)) {
            if ($oldImageUrl && !filter_var($oldImageUrl, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImageUrl));
            }

            $file = $request->file($imageField);
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('beranda', $filename, 'public');
            return '/storage/' . $path;
        }

        return $oldImageUrl;
    }

    public function index()
    {
        $heroImages = HeroImage::all()->keyBy('position');

        return view('admin.beranda.index', compact('heroImages'));
    }

    public function editHero()
    {
        $heroImages = HeroImage::all()->keyBy('position');
        return view('admin.beranda.edit-hero', compact('heroImages'));
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'main_alt_text' => 'nullable|string',
        ], [
            'main_image.image' => 'Foto utama harus berupa gambar.',
            'main_image.mimes' => 'Foto utama harus berformat JPG, PNG, GIF, atau WebP.',
            'main_image.max' => 'Foto utama maksimal 5MB.',
        ]);

        $mainOld = HeroImage::where('position', 'main')->first();
        $mainImageUrl = $this->processImage($request, 'main', $mainOld?->image_url);

        if (!$mainImageUrl) {
            return back()->withErrors(['main' => 'Pilih foto untuk diunggah']);
        }

        HeroImage::updateOrCreate(
            ['position' => 'main'],
            [
                'image_url' => $mainImageUrl,
                'alt_text' => $request->input('main_alt_text'),
            ]
        );

        $this->hapusFotoSamping();

        return redirect()->route('admin.beranda.edit-hero')
            ->with('success', 'Foto beranda berhasil diperbarui');
    }

    /**
     * Sinkronkan: hero hanya pakai 1 foto (main).
     * Hapus record & file foto samping yang tidak terpakai.
     */
    private function hapusFotoSamping()
    {
        foreach (['side1', 'side2'] as $position) {
            $row = HeroImage::where('position', $position)->first();
            if ($row) {
                $url = $row->image_url;
                if ($url && !filter_var($url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $url));
                }
                $row->delete();
            }
        }
    }
}
