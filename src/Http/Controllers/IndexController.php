<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Facades\Prezet;
use Illuminate\Http\Request;

class IndexController
{
    public function __invoke(Request $request)
    {
        $posts = Prezet::getAllPosts();

        return view('prezet::index', [
            'posts' => $posts,
        ]);
    }
}
