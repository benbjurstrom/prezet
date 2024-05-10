<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class GetImage
{
    public static function handle(string $path): string
    {
        if (! preg_match('/^[\w-]+\.webp$/', $path)) {
            abort(404, 'Invalid path');
        }

        $gdInfo = gd_info();
        if (! isset($gdInfo['WebP Support']) || $gdInfo['WebP Support'] !== true) {
            abort(500, 'WebP support is not available in the GD extension.');
        }

        $allowedWidths = config('prezet.image.widths');

        $pattern = '/(.+)-(\d+)w\.webp$/';
        preg_match($pattern, $path, $matches);

        if (isset($matches[2]) && in_array((int) $matches[2], $allowedWidths)) {
            $size = (int) $matches[2];
            $path = $matches[1].'.webp';
        }

        $file = Storage::disk('prezet')->get('images/'.$path);
        if (! $file) {
            abort(404);
        }

        $image = imagecreatefromstring($file);

        // resize image
        if (isset($size)) {
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            $ratio = $size / $originalWidth;
            $newHeight = $originalHeight * $ratio;
            $resizedImage = imagecreatetruecolor($size, $newHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $size, $newHeight, $originalWidth, $originalHeight);
            imagedestroy($image);
            $image = $resizedImage;
        }

        ob_start();
        imagewebp($image, null, 75);
        $file = ob_get_clean();
        imagedestroy($image);

        return $file;
    }
}
