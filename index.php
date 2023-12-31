<?php

require __DIR__.'/vendor/autoload.php';

define("URL", "http://localhost/mvc");

include __DIR__.'/routes/web.php';

$obRouter->run()
         ->sendResponse();