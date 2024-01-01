<?php

namespace App\Http\Controllers;

use App\Utils\View;

class ProductController extends PageController
{
    public static function index()
    {
        $content = View::render('product', [
            'page' => 'Produtos',
            'description' => 'Listando todos os produtos'
        ]);

        return parent::page('Página Home', $content);
    }

    public static function findById($productID)
    {
        $content = View::render('product', [
            'page' => 'Produtos',
            'description' => 'Listando produto '.$productID
        ]);

        return parent::page('Página Home', $content);
    }
}

