<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

return static function (RouteBuilder $routes): void {
    // Set the default route class
    $routes->setRouteClass(DashedRoute::class);

    // Enable JSON extension for all routes
    $routes->setExtensions(['json']);

    // Define homepage route (redirects to tasks index)
    $routes->connect('/', ['controller' => 'Tasks', 'action' => 'index']);

    // Define RESTful routes for Tasks
    $routes->scope('/tasks', function (RouteBuilder $builder) {
        $builder->connect('/', ['controller' => 'Tasks', 'action' => 'index', '_method' => 'GET']);
        $builder->connect('/:id', ['controller' => 'Tasks', 'action' => 'view'], ['pass' => ['id'], 'id' => '\d+', '_method' => 'GET']);
        $builder->connect('/', ['controller' => 'Tasks', 'action' => 'add', '_method' => 'POST']);
        $builder->connect('/:id', ['controller' => 'Tasks', 'action' => 'edit'], ['pass' => ['id'], 'id' => '\d+', '_method' => 'PUT']);
        $builder->connect('/:id', ['controller' => 'Tasks', 'action' => 'delete'], ['pass' => ['id'], 'id' => '\d+', '_method' => 'DELETE']);
    });

    // Fallback routes
    $routes->fallbacks(DashedRoute::class);
};
