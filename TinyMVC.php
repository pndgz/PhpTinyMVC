<?php

if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
    die('PHP ActiveRecord requires PHP 5.3 or higher');

define('PHP_TINY_MVC_VERSION_ID','1.0');

if (!defined('PHP_TINY_MVC_AUTOLOAD_PREPEND'))
    define('PHP_TINY_MVC_AUTOLOAD_PREPEND', true);

require __DIR__.'/lib/Initializer.php';
require __DIR__.'/lib/Dispatcher.php';
require __DIR__.'/lib/Router.php';
require __DIR__.'/lib/Controller.php';
require __DIR__.'/lib/Interceptor.php';
require __DIR__.'/lib/Pager.php';


if (!defined('PHP_TINY_MVC_AUTOLOAD_DISABLE'))
    spl_autoload_register('tiny_mvc_autoload',false,PHP_TINY_MVC_AUTOLOAD_PREPEND);

use PhpTinyMVC\Initializer;
use PhpTinyMVC\Config;

function tiny_mvc_autoload() {
    Initializer::initialize(Config::instance()->get_mvc_dirs());
    Initializer::$interceptors = Config::instance()->get_interceptors();
    Initializer::dispatch();
}