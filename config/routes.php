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

//use Cake\Core\Configure;
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
    $langs = 'en|pt|zh|fr|de|ro|vn|es|gr|dk|id|hu|in|it|tr|ua|ar|es_CT|ru|fa';

    $builder->connect('/', ['controller' => 'Explorer', 'action' => 'toen']);

    $builder->connect('/{lang}/', ['controller' => 'Explorer', 'action' => 'index'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/assets', ['controller' => 'Explorer', 'action' => 'assets'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/asset/*', ['controller' => 'Explorer', 'action' => 'asset'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/block/*', ['controller' => 'Explorer', 'action' => 'block'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/blocktransactions/*', ['controller' => 'Explorer', 'action' => 'blocktransactions'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/pendingtransactions', ['controller' => 'Explorer', 'action' => 'pendingtransactions'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/pendingtransaction/*', ['controller' => 'Explorer', 'action' => 'pendingtransaction'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/transaction/*', ['controller' => 'Explorer', 'action' => 'transaction'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/address/*', ['controller' => 'Explorer', 'action' => 'address'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/top', ['controller' => 'Explorer', 'action' => 'top'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/search/*', ['controller' => 'Explorer', 'action' => 'search'])
    ->setPatterns(['lang'=>$langs]);

    $builder->connect('/{lang}/from/{id}', ['controller' => 'Explorer', 'action' => 'index'])
    ->setPatterns(['lang'=>$langs, 'id'=>'\d+'])
    ->setPass(['id']);

    /*
        * ...and connect the rest of 'Pages' controller's URLs.
        */
    //$builder->connect('/pages/*', 'Pages::display');

    /*
        * Connect catchall routes for all controllers.
        *
        * The `fallbacks` method is a shortcut for
        *
        * ```
        * $builder->connect('/:controller', ['action' => 'index']);
        * $builder->connect('/:controller/:action/*', []);
        * ```
        *
        * You can remove these routes once you've connected the
        * routes you want in your application.
        */
    $builder->fallbacks();

    $builder->prefix('api', function (RouteBuilder $builder) {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'api']);

        $builder->fallbacks();

        $builder->scope('/v1', function (RouteBuilder $builder) {
            $builder->setExtensions(['json']);

            $builder->connect('/net-stats', ['controller' => 'Explorer', 'action' => 'netstats']);
            $builder->connect('/kusk-info', ['controller' => 'Explorer', 'action' => 'kuskinfo']);

            $builder->fallbacks();
        });
    });

});


/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *
 *     // Parse specified extensions from URLs
 *     // $builder->setExtensions(['json', 'xml']);
 *
 *     // Connect API actions here.
 * });
 * ```
 */
