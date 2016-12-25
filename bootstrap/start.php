<?php

use Engine\App;
use Engine\Config\Config;


Config::instantiate([
    Config::APP=>require __DIR__.'/../config/app.php',
    Config::DATABASE=>require __DIR__.'/../config/database.php',
]);

$app = new App;

$app->bindRoutes(require __DIR__.'/../app/routes.php');

return $app;
