<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\Project;
use App\Models\SiteVisit;
use App\Models\VisitorComment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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

        $visitAnalytics = $this->buildVisitAnalytics();

        $recentMessages = ContactMessage::latest('id')->limit(5)->get();
        $recentComments = VisitorComment::latest('id')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'visitAnalytics', 'recentMessages', 'recentComments'));
    }

    /**
     * Build 30-day Jakarta-timezone visitor series and summary metrics for the dashboard chart.
     */
    private function buildVisitAnalytics(): array
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $start = $today->copy()->subDays(29);
        $hasTable = Schema::hasTable('site_visits');

        $rows = $hasTable
            ? SiteVisit::query()
                ->where('visited_date', '>=', $start->toDateString())
                ->selectRaw('visited_date, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as unique_visitors')
                ->groupBy('visited_date')
                ->get()
                ->keyBy('visited_date')
            : collect();

        $series = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $start->copy()->addDays($i);
            $dateKey = $day->toDateString();
            $row = $rows->get($dateKey);

            $series[] = [
                'date' => $dateKey,
                'label' => $day->locale('id')->translatedFormat('d M'),
                'fullDate' => $day->locale('id')->translatedFormat('d M Y'),
                'views' => (int) ($row->views ?? 0),
                'unique' => (int) ($row->unique_visitors ?? 0),
            ];
        }

        $totalViews = $hasTable ? SiteVisit::count() : 0;
        $uniqueVisitors = $hasTable ? SiteVisit::distinct('visitor_hash')->count('visitor_hash') : 0;
        $mobileOnlyViews = $hasTable ? SiteVisit::where('device_type', 'mobile')->count() : 0;
        $tabletViews = $hasTable ? SiteVisit::where('device_type', 'tablet')->count() : 0;
        $mobileViews = $mobileOnlyViews + $tabletViews;
        $desktopViews = max(0, $totalViews - $mobileViews);

        $todayRow = $series[count($series) - 1] ?? ['views' => 0, 'unique' => 0];
        $views7d = array_sum(array_column(array_slice($series, -7), 'views'));
        $unique7d = array_sum(array_column(array_slice($series, -7), 'unique'));
        $views30d = array_sum(array_column($series, 'views'));
        $unique30d = array_sum(array_column($series, 'unique'));

        $desktopPct = $totalViews > 0 ? (int) round(($desktopViews / $totalViews) * 100) : 0;
        $mobileOnlyPct = $totalViews > 0 ? (int) round(($mobileOnlyViews / $totalViews) * 100) : 0;
        $tabletPct = $totalViews > 0 ? max(0, 100 - $desktopPct - $mobileOnlyPct) : 0;

        return [
            'series' => $series,
            'total_views' => $totalViews,
            'unique_total' => $uniqueVisitors,
            'today_views' => $todayRow['views'],
            'today_unique' => $todayRow['unique'],
            'views_7d' => $views7d,
            'unique_7d' => $unique7d,
            'views_30d' => $views30d,
            'unique_30d' => $unique30d,
            'mobile_pct' => $totalViews > 0 ? (int) round(($mobileViews / $totalViews) * 100) : 0,
            'desktop_pct' => $desktopPct,
            'mobile_only_pct' => $mobileOnlyPct,
            'tablet_pct' => $tabletPct,
        ];
    }
}
