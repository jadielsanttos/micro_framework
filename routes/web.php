<?php

use App\Http\Response;
use App\Http\Router;
use App\Utils\View;

$obRouter = new Router(URL);

$obRouter->get('/', [
    function(){
        return new Response(200, View::render('/default/welcome'));
    }
]);