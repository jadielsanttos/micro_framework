<?php

use App\Utils\View;

require __DIR__.'/vendor/autoload.php';

define("URL", "http://localhost/mvc");

include __DIR__.'/routes/web.php';

View::init([
    'URL' => URL
]);

$obRouter->run()
         ->sendResponse();