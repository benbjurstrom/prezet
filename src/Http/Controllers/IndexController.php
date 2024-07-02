<?php

namespace BenBjurstrom\Prezet\Http\Controllers;

use BenBjurstrom\Prezet\Prezet;
use Illuminate\Http\Request;

class IndexController
{
    public function __invoke(Request $request)
    {
        $nav = Prezet::getNav();
        $articles = Prezet::getAllPosts();

        return view('prezet::index', [
            'nav' => $nav,
            'articles' => $articles,
        ]);
    }
}
