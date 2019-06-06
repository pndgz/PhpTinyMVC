<?php

namespace PhpTinyMVC;

abstract class Singleton {

    private static $instances = array();

    final public static function instance() {
        $cls = get_called_class();

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new $cls;
        }

        return self::$instances[$cls];
    }

    final protected function get_called_class() {
        $backtrace = debug_backtrace();
        return $this->get_class($backtrace[2]['object']);
    }

}