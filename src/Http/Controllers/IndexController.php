<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Facades\Prezet;
use Illuminate\Http\Request;

class IndexController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $posts = Prezet::GetAllPosts();

        return view('prezet::index', [
            'posts' => $posts,
        ]);
    }
}
