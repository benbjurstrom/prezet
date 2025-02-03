<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController
{
    public function __invoke(Request $request, string $path): Response
    {
        $file = Prezet::getImage($path);
        $size = strlen($file);

        return response($file, 200, [
            'Content-Type' => match (pathinfo($path, PATHINFO_EXTENSION)) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                default => 'image/webp'
            },
            'Content-Length' => $size,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
