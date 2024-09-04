<?php

namespace BenBjurstrom\Prezet\Actions;

use BenBjurstrom\Prezet\Exceptions\InvalidConfigurationException;
use GdImage;
use Illuminate\Support\Facades\Storage;

class GetImage
{
    public static function handle(string $path): string
    {
        self::validateFileExtension($path);

        $size = self::extractSize($path);
        $path = self::removeSize($path);

        $image = self::loadImage($path);

        if (isset($size)) {
            $image = self::resizeImage($image, $size);
        }

        return self::outputImage($image, pathinfo($path, PATHINFO_EXTENSION));
    }

    protected static function validateFileExtension(string $path): void
    {
        $allowedExtensions = ['png', 'jpg', 'webp'];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($extension, $allowedExtensions)) {
            abort(404, 'Invalid file extension');
        }
    }

    protected static function loadImage(string $path): GdImage
    {
        $imageStr = Storage::disk(GetPrezetDisk::handle())->get('images/'.$path);
        if (! $imageStr) {
            abort(404);
        }

        $image = imagecreatefromstring($imageStr);
        if (! $image) {
            abort(500, 'Failed to create image from string');
        }

        return $image;
    }

    private static function extractSize(string $path): ?int
    {
        $allowedWidths = config('prezet.image.widths');
        if (! is_array($allowedWidths)) {
            throw new InvalidConfigurationException('prezet.image.widths', $allowedWidths, 'is not an array');
        }

        $pattern = '/(.+)-(\d+)w\.(?:png|jpg|webp)$/';

        if (preg_match($pattern, $path, $matches) && in_array((int) $matches[2], $allowedWidths)) {
            return (int) $matches[2];
        }

        return null;
    }

    private static function removeSize(string $path): string
    {
        $pattern = '/(.+)-(\d+)w\.(\w+)$/';

        $result = preg_replace($pattern, '$1.$3', $path);

        if (! $result) {
            abort(404, 'Invalid image path');
        }

        return $result;
    }

    private static function resizeImage(GdImage $image, int $size): GdImage
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

    private static function outputImage(GdImage $image, string $extension): string
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

        if (! $output) {
            abort(500, 'failed to output image');
        }

        return $output;
    }
}
