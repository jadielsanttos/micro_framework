<?php

namespace App\Http\Controllers;

use App\Utils\View;

class HomeController extends PageController
{
    public static function home()
    {
        $content = View::render('home', [
            'page' => 'Home',
            'description' => 'Seja bem-vindo(a)!'
        ]);

        return parent::page('Página Home', $content);
    }
}

