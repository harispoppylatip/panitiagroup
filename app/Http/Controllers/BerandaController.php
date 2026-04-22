<?php

namespace App\Http\Controllers;

use App\Models\HeroImage;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{
    /**
     * Helper function to process image upload or URL
     */
    private function processImage($request, $fieldName, $oldImageUrl = null)
    {
        $imageField = $fieldName . '_image';
        $urlField = $fieldName . '_image_url';

        // If file is uploaded
        if ($request->hasFile($imageField)) {
            // Delete old file if exists and is a local file
            if ($oldImageUrl && !filter_var($oldImageUrl, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImageUrl));
            }

            // Store new file
            $file = $request->file($imageField);
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('beranda', $filename, 'public');
            return '/storage/' . $path;
        }

        // If URL is provided
        if ($request->filled($urlField)) {
            // Delete old file if exists and is a local file
            if ($oldImageUrl && !filter_var($oldImageUrl, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImageUrl));
            }
            return $request->input($urlField);
        }

        // Return old image if nothing new provided
        return $oldImageUrl;
    }

    /**
     * Show beranda management page
     */
    public function index()
    {
        $heroImages = HeroImage::all()->keyBy('position');
        $teamMembers = TeamMember::orderBy('order')->get();

        return view('admin.beranda.index', compact('heroImages', 'teamMembers'));
    }

    /**
     * Edit hero image
     */
    public function editHero()
    {
        $heroImages = HeroImage::all()->keyBy('position');
        return view('admin.beranda.edit-hero', compact('heroImages'));
    }

    /**
     * Update hero image
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'main_image_url' => 'nullable|url',
            'main_alt_text' => 'nullable|string',
            'side1_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'side1_image_url' => 'nullable|url',
            'side1_alt_text' => 'nullable|string',
            'side2_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'side2_image_url' => 'nullable|url',
            'side2_alt_text' => 'nullable|string',
        ]);

        // Process main image
        $mainOld = HeroImage::where('position', 'main')->first();
        $mainImageUrl = $this->processImage($request, 'main', $mainOld?->image_url);

        if (!$mainImageUrl) {
            return back()->withErrors(['main' => 'Upload foto atau masukkan URL']);
        }

        HeroImage::updateOrCreate(
            ['position' => 'main'],
            [
                'image_url' => $mainImageUrl,
                'alt_text' => $request->input('main_alt_text'),
            ]
        );

        // Process side1 image
        $side1Old = HeroImage::where('position', 'side1')->first();
        $side1ImageUrl = $this->processImage($request, 'side1', $side1Old?->image_url);

        if (!$side1ImageUrl) {
            return back()->withErrors(['side1' => 'Upload foto atau masukkan URL']);
        }

        HeroImage::updateOrCreate(
            ['position' => 'side1'],
            [
                'image_url' => $side1ImageUrl,
                'alt_text' => $request->input('side1_alt_text'),
            ]
        );

        // Process side2 image
        $side2Old = HeroImage::where('position', 'side2')->first();
        $side2ImageUrl = $this->processImage($request, 'side2', $side2Old?->image_url);

        if (!$side2ImageUrl) {
            return back()->withErrors(['side2' => 'Upload foto atau masukkan URL']);
        }

        HeroImage::updateOrCreate(
            ['position' => 'side2'],
            [
                'image_url' => $side2ImageUrl,
                'alt_text' => $request->input('side2_alt_text'),
            ]
        );

        return redirect()->route('admin.beranda.edit-hero')
            ->with('success', 'Foto beranda berhasil diperbarui');
    }

    /**
     * Show team management page
     */
    public function editTeam()
    {
        $teamMembers = TeamMember::orderBy('order')->get();
        return view('admin.beranda.edit-team', compact('teamMembers'));
    }

    /**
     * Store new team member
     */
    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photo_image_url' => 'nullable|url',
            'order' => 'required|integer|min:0',
        ]);

        $imageUrl = $this->processImage($request, 'photo');

        if (!$imageUrl) {
            return back()->withErrors(['photo_image' => 'Upload foto atau masukkan URL']);
        }

        TeamMember::create([
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'image_url' => $imageUrl,
            'order' => $request->input('order'),
        ]);

        return redirect()->route('admin.beranda.edit-team')
            ->with('success', 'Anggota tim berhasil ditambahkan');
    }

    /**
     * Update team member
     */
    public function updateTeam(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photo_image_url' => 'nullable|url',
            'order' => 'required|integer|min:0',
        ]);

        $imageUrl = $this->processImage($request, 'photo', $teamMember->image_url);

        if (!$imageUrl) {
            return back()->withErrors(['photo_image' => 'Upload foto atau masukkan URL']);
        }

        $teamMember->update([
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'image_url' => $imageUrl,
            'order' => $request->input('order'),
        ]);

        return redirect()->route('admin.beranda.edit-team')
            ->with('success', 'Anggota tim berhasil diperbarui');
    }

    /**
     * Delete team member
     */
    public function destroyTeam(TeamMember $teamMember)
    {
        // Delete local file if exists
        if ($teamMember->image_url && !filter_var($teamMember->image_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete(str_replace('storage/', '', $teamMember->image_url));
        }

        $teamMember->delete();

        return redirect()->route('admin.beranda.edit-team')
            ->with('success', 'Anggota tim berhasil dihapus');
    }
}
