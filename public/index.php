<?php

/**
 * App_mk0
 *
 * This work would be a little PHP framework, a learn exercice. 
 * Work started from php MINI https://github.com/panique/mini good for understand how a MVC framework run :) 
 * I rewrote Router, Dispatcher, Controller and I added some new class like Model, View... etc for more flexibility  
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 * @version 0.1.0
 */


/**
 * Set a constant that holds the project's folder path, like "/var/www/".
 * DIRECTORY_SEPARATOR adds a slash to the end of the path
 */
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
/**
 * Set a constant that holds the project's "application" folder, like "/var/www/application".
 */
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);


//load configuration from config file
require APP . '/config/config.php';

//load routes. 
require APP . '/config/routes.php';

//load application class
//for more information see http://www.php-fig.org/psr/psr-4/
//require SRC . '/autoload.php';

require '../vendor/autoload.php';


$loader = new \Leviu\Autoloader();
$loader->register();

$loader->addNamespaces([
    ['App\Lib', __DIR__.'/../app/library'],
    ['App\Controllers', __DIR__.'/../app/controllers'],
    ['App\Models', __DIR__.'/../app/models']
]);


//session handler, archive session in mysql :)
$dbSessionHandler = new \Leviu\Session\DatabaseSessionHandler('MY_SESSION');

//set session handler and start session
session_set_save_handler($dbSessionHandler, true);


//initialize session
\Leviu\Session\Session::$expire = 1800;
\Leviu\Session\Session::$name = 'MY_SESSION';

$session = \Leviu\Session\Session::start();


//router
$router = new \Leviu\Routing\Router($routes, URL_SUB_FOLDER);

//get route
$route = $router->getRoute();

//var_dump($route);

//dispatch route
$dispatcher = new \Leviu\Routing\Dispatcher($route);
$dispatcher->dispatch();

//only for debug, return time execution and memory usage
echo '<!-- Memory: ';
echo round(xdebug_memory_usage() / 1024, 2) , ' (';
echo round(xdebug_peak_memory_usage() / 1024, 2) , ') KByte - Time: ';
echo xdebug_time_index();
echo ' Seconds -->';
