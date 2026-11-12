<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GalleryPhoto;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;

class PortfolioApiController extends Controller
{
    public function index(): JsonResponse
    {
        $allProjects = Project::orderBy('sort_order')->orderBy('id')->get();

        $website = $allProjects
            ->where('tab', 'website')
            ->values()
            ->map(fn (Project $p) => $p->toApiArray());

        $aplikasi = $allProjects
            ->where('tab', 'aplikasi')
            ->values()
            ->map(fn (Project $p) => $p->toApiArray());

        $desain = $allProjects
            ->where('tab', 'desain')
            ->values()
            ->map(fn (Project $p) => $p->toApiArray());

        $combined = $allProjects
            ->values()
            ->map(fn (Project $p) => $p->toApiArray());

        $gallery = GalleryPhoto::orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (GalleryPhoto $g) => $g->toApiArray());

        $profile = PortfolioProfile::first();
        $education = Education::orderBy('sort_order')->orderBy('id')->get()->map(fn (Education $e) => $e->toApiArray());
        $experience = Experience::orderBy('sort_order')->orderBy('id')->get()->map(fn (Experience $e) => $e->toApiArray());
        $socialLinks = SocialLink::orderBy('sort_order')->orderBy('id')->get()->map(fn (SocialLink $s) => $s->toApiArray());

        return response()->json([
            'personal' => $profile?->toApiArray(),
            'education' => $education,
            'experience' => $experience,
            'socialLinks' => $socialLinks,
            'projectsWebsite' => $website,
            'projectsAplikasi' => $aplikasi,
            'projectsDesain' => $desain,
            'projects' => $combined,
            'gallery' => $gallery,
            'totalProjects' => $allProjects->count(),
        ]);
    }
}
