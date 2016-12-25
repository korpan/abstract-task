<?php



$routes = [];

$routes[] = array(
    '/',
    'controller' => '\App\Controllers\SiteController',
    'action' => 'index',
);



$routes[] = array(
    '/<section>(/<category>)(/<id>)',
    'controller' => '\App\Controllers\ArticlesController',
    'action' => 'resolve',
);


return $routes;