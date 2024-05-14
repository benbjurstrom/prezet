<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Facades\Prezet;
use Illuminate\Http\Request;

class IndexController
{
    public function __invoke(Request $request)
    {
        $articles = Prezet::getAllPosts();

        return view('prezet::index', [
            'articles' => $articles,
        ]);
    }
}
