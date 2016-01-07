<?php

use Application\Controller\HelloWorldAction;
use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello', [
    '_controller' => HelloWorldAction::class
]));

return $routes;