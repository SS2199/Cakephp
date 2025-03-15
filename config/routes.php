<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    // Enable JSON extension for all routes
    $routes->setExtensions(['json']);

    // Define resource routes for Tasks
    $routes->resources('Tasks', [
        'map' => [
            'index' => [
                'action' => 'index',
                'method' => 'GET'
            ],
            'view' => [
                'action' => 'view',
                'method' => 'GET'
            ],
            'add' => [
                'action' => 'add',
                'method' => 'POST'
            ],
            'edit' => [
                'action' => 'edit',
                'method' => 'PUT'
            ],
            'delete' => [
                'action' => 'delete',
                'method' => 'DELETE'
            ]
        ]
    ]);

    // Fallback for other routes
    $routes->fallbacks('DashedRoute');
});