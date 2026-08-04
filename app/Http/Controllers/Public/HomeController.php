<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function home()
    {
        $heroImages = HeroImage::all()->keyBy('position');
        $teamMembers = TeamMember::orderBy('order')->get();

        return view('pages.home', compact('heroImages', 'teamMembers'));
    }
}
