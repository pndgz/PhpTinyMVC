<?php

require_once __DIR__ . "/../vendor/autoload.php";

use PhpTinyMVC\Initializer;
use PhpTinyMVC\Dispatcher;

//Initializer::$debug = true;
Initializer::initialize();
Dispatcher::dispatch();

