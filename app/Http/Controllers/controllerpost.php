<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modelpost;
use App\Models\HeroImage;
use App\Models\TeamMember;
use App\Models\sikaddata;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Http;

class controllerpost extends Controller
{


    public function home()
    {
        $heroImages = HeroImage::all()->keyBy('position');
        $teamMembers = TeamMember::orderBy('order')->get();

        return view('pages.home', compact('heroImages', 'teamMembers'));
    }

    public function index()
    {
        $data = modelpost::all();
        return view('pages.postingan', compact('data'));
    }

    public function editor()
    {
        $data = modelpost::all();
        return view('admin.editorpost', compact('data'));
    }
    public function delete($id)
    {
        modelpost::destroy($id);
        return redirect('/editor');
    }

    
}
