<?php

namespace App\Http\Controllers;

use App\Utils\View;

class PageController
{

    private static function header()
    {
        return View::render('header');
    }

    private static function footer()
    {
        return View::render('footer');
    }

    public static function page($title, $content)
    {
        return View::render('page', [
            'title'   => $title,
            'header'  => self::header(),
            'content' => $content,
            'footer'  => self::footer()
        ]);
    }
}

