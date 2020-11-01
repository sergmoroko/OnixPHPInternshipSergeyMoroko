<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    $builder->setExtensions(['json']);


    $builder->resources('Users', [
        'map' => [
            'index' => [
                'action' => 'index',
                'method' => 'GET',
                'path' => '',
            ],
            'delete' => [
                'action' => 'delete',
                'method' => 'DELETE',
                'path' => '/:id/delete',
            ],
            'edit' => [
                'action' => 'edit',
                'method' => 'PATCH',
                'path' => '/:id/edit',
            ],
            'setRole' => [
                'action' => 'setRole',
                'method' => ['PUT', 'DELETE'],
                'path' => '/:id/role',
            ],
            'view' => [
                'action' => 'view',
                'method' => 'GET',
                'path' => '/:id',
            ],
        ]
    ]);

    $builder->resources('Categories', [
        'map' => [
            'add' => [
                'action' => 'add',
                'method' => ['POST'],
                'path' => '/add',
            ],
            'index' => [
                'action' => 'index',
                'method' => 'GET',
                'path' => '',
            ],
            'view' => [
                'action' => 'view',
                'method' => 'GET',
                'path' => '/:id',
            ],
            'delete' => [
                'action' => 'delete',
                'method' => 'DELETE',
                'path' => '/:id/delete',
            ],
            'edit' => [
                'action' => 'edit',
                'method' => 'PATCH',
                'path' => '/:id/edit',
            ],
        ]
    ]);

    $builder->resources('Listings', [
        'map' => [
            'add' => [
                'action' => 'add',
                'method' => ['POST'],
                'path' => '/add',
            ],
            'index' => [
                'action' => 'index',
                'method' => 'GET',
                'path' => '',
            ],
            'delete' => [
                'action' => 'delete',
                'method' => 'DELETE',
                'path' => '/:id/delete',
            ],
            'edit' => [
                'action' => 'edit',
                'method' => 'PATCH',
                'path' => '/:id/edit',
            ],
            'buy' => [
                'action' => 'buy',
                'method' => 'GET',
                'path' => '/:id/buy',
            ],
        ]
    ]);

    $builder->resources('Orders', [
        'map' => [
            'index' => [
                'action' => 'index',
                'method' => 'GET',
                'path' => '',
            ],
            'view' => [
                'action' => 'view',
                'method' => 'GET',
                'path' => '/:id',
            ],
        ]
    ]);

    $builder->resources('Transactions', [
        'map' => [
            'index' => [
                'action' => 'index',
                'method' => 'GET',
                'path' => '',
            ],
            'view' => [
                'action' => 'view',
                'method' => 'GET',
                'path' => '/:id',
            ],
        ]
    ]);

    $builder->connect('/users/:id',
        ['controller' => 'Users', 'action' => 'view'],
        ['id' => '\d+', 'pass' => ['id']]);


    $builder->connect('/registration', ['controller' => 'Users', 'method' => 'POST', 'action' => 'add']);

    $builder->connect('/login', ['controller' => 'Users', 'method' => 'POST', 'action' => 'login']);

    $builder->connect('/account', ['controller' => 'Users', 'method' => 'GET', 'action' => 'myAccount']);

    $builder->connect('account/deposit', ['controller' => 'Users', 'action' => 'deposit', 'method' => 'POST']);

    $builder->connect('/account/transactions',
        ['controller' => 'Transactions', 'method' => 'GET', 'action' => 'myTransactions']);

    $builder->connect('/account/orders',
        ['controller' => 'Orders', 'method' => 'GET', 'action' => 'myOrders']);

    $builder->connect('/account/listings',
        ['controller' => 'Listings', 'method' => 'GET', 'action' => 'myListings']);

});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
