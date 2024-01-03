<?php

namespace App\Http\Controllers;

use App\Utils\View;

class NotFoundController 
{
    public static function page404()
    {
        return View::render('/default/404');
    }
}