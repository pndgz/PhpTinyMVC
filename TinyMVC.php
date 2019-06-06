<?php

if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
    die('PHP ActiveRecord requires PHP 5.3 or higher');

define('PHP_TINY_MVC_VERSION_ID','1.0');

require __DIR__.'/lib/Singleton.php';
require __DIR__.'/lib/Config.php';
require __DIR__.'/lib/Initializer.php';
require __DIR__.'/lib/Dispatcher.php';
require __DIR__.'/lib/Router.php';
require __DIR__.'/lib/Controller.php';
require __DIR__.'/lib/Interceptor.php';
require __DIR__.'/lib/Pager.php';
