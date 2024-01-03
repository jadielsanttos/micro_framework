<?php

namespace App\Http\Controllers;

use App\Utils\View;

class HomeController
{
    public static function home()
    {
        return View::render('home', [
            'page' => 'Home',
            'description' => 'Seja bem-vindo(a)!'
        ]);
    }
}

