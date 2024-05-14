<?php

namespace BenBjurstrom\Prezet\Actions;

use Illuminate\Support\Facades\Storage;

class GetImage
{
    public static function handle(string $path): string
    {
        $allowedExtensions = ['png', 'jpg', 'webp'];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($extension, $allowedExtensions)) {
            abort(404, 'Invalid file extension');
        }

        $size = self::extractSize($path);
        $path = self::removeSize($path);

        $imageStr = Storage::disk('prezet')->get('images/'.$path);
        if (! $imageStr) {
            abort(404);
        }

        $image = imagecreatefromstring($imageStr);

        if (isset($size)) {
            $image = self::resizeImage($image, $size);
        }

        return self::outputImage($image, $extension);
    }

    private static function extractSize(string $path): ?int
    {
        $allowedWidths = config('prezet.image.widths');
        $pattern = '/(.+)-(\d+)w\.(?:png|jpg|webp)$/';

        if (preg_match($pattern, $path, $matches) && in_array((int) $matches[2], $allowedWidths)) {
            return (int) $matches[2];
        }

        return null;
    }

    private static function removeSize(string $path): string
    {
        $pattern = '/(.+)-(\d+)w\.(\w+)$/';

        return preg_replace($pattern, '$1.$3', $path);
    }

    private static function create(string $file, string $extension)
    {
        switch ($extension) {
            case 'png':
                return imagecreatefromstring($file);
            case 'jpg':
                return imagecreatefromstring($file);
            case 'webp':
                return imagecreatefromstring($file);
            default:
                abort(500, 'Unsupported file extension');
        }
    }

    private static function resizeImage($image, int $size)
    {
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        $ratio = $size / $originalWidth;
        $newHeight = $originalHeight * $ratio;
        $resizedImage = imagecreatetruecolor($size, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $size, $newHeight, $originalWidth, $originalHeight);
        imagedestroy($image);

        return $resizedImage;
    }

    private static function outputImage($image, string $extension): string
    {
        ob_start();
        switch ($extension) {
            case 'png':
                imagepng($image);
                break;
            case 'jpg':
                imagejpeg($image);
                break;
            case 'webp':
                imagewebp($image);
                break;
        }
        $output = ob_get_clean();
        imagedestroy($image);

        return $output;
    }
}
