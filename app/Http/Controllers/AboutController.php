<?php

namespace App\Http\Controllers;

use App\Utils\View;

class AboutController extends PageController
{
    public static function about()
    {
        $content = View::render('about', [
            'page' => 'Sobre',
            'description' => 'Descrição da página sobre'
        ]);

        return parent::page('Página Sobre', $content);
    }
}

