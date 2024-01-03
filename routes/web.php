<?php

use App\Http\Controllers\HomeController;
use App\Http\Response;
use App\Http\Router;

$obRouter = new Router(URL);

$obRouter->get('/', [
    function(){
        return new Response(200, HomeController::home());
    }
]);