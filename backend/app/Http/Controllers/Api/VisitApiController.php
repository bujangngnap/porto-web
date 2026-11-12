<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $rawPath = (string) $request->input('path', '/');
        $path = '/'.ltrim(substr(trim($rawPath), 0, 250), '/');

        $rawReferrer = trim((string) $request->input('referrer', ''));
        $referrerHost = null;
        if ($rawReferrer !== '') {
            $host = parse_url($rawReferrer, PHP_URL_HOST);
            if (is_string($host) && $host !== '') {
                $referrerHost = strtolower(substr($host, 0, 150));
            }
        }

        $ua = (string) $request->userAgent();
        $deviceType = 'desktop';
        if (preg_match('/ipad|tablet|(android(?!.*mobile))/i', $ua)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/mobile|iphone|ipod|android.*mobile|windows phone/i', $ua)) {
            $deviceType = 'mobile';
        }

        $today = now('Asia/Jakarta')->toDateString();
        $fingerprint = hash('sha256', implode('|', [
            $today,
            (string) $request->ip(),
            substr($ua, 0, 200),
            (string) config('app.key'),
        ]));

        SiteVisit::create([
            'visited_date' => $today,
            'visitor_hash' => $fingerprint,
            'path' => $path === '//' ? '/' : $path,
            'referrer_host' => $referrerHost,
            'device_type' => $deviceType,
        ]);

        return response()->json(['ok' => true], 201);
    }
}
