<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    /**
     * Resolve an asset URL for rendering inside the Laravel Admin Dashboard.
     */
    public static function adminPreviewUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/storage/')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return url('/portfolio-media/'.ltrim($path, '/'));
        }

        return $path;
    }

    /**
     * Store an uploaded file on the public disk and return its /storage/... URL path.
     */
    public static function storePublicFile(UploadedFile $file, string $directory): string
    {
        $stored = $file->store($directory, 'public');

        return '/storage/'.ltrim($stored, '/');
    }

    /**
     * Store an uploaded image on the public disk and return its /storage/... URL path.
     */
    public static function storePublicImage(UploadedFile $file, string $directory): string
    {
        return self::storePublicFile($file, $directory);
    }

    /**
     * Delete a previously stored /storage/... file when replaced or removed.
     */
    public static function deleteStoredPublicImage(?string $path): void
    {
        if (! $path || ! str_starts_with($path, '/storage/')) {
            return;
        }

        $relative = ltrim(substr($path, strlen('/storage/')), '/');
        if ($relative !== '' && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
