<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\VisitorComment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'messages_total' => ContactMessage::count(),
            'messages_unread' => ContactMessage::unread()->count(),
            'comments_total' => VisitorComment::count(),
            'comments_approved' => VisitorComment::approved()->count(),
            'projects_total' => Project::count(),
            'projects_website' => Project::where('tab', 'website')->count(),
            'projects_aplikasi' => Project::where('tab', 'aplikasi')->count(),
            'projects_desain' => Project::where('tab', 'desain')->count(),
            'gallery_total' => GalleryPhoto::count(),
        ];

        $recentMessages = ContactMessage::latest('id')->limit(5)->get();
        $recentComments = VisitorComment::latest('id')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages', 'recentComments'));
    }
}
