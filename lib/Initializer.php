<?php

namespace PhpTinyMVC;

class Initializer {
	
	public static $debug = false;

	public static $ROOT_PATH = '';
	
	public static $settings = array(
		"path" => "/controllers",
		"itcpt" => "/interceptors",
		"templates" => "/templates/pages",
		"extension" => "tpl",
		"partials" => "/templates",
		"suffix" => "Action",
		"itcpt_suffix" => "Interceptor"
	);
	
	public static $interceptors = array();
	
	public static function initialize($settings = array()) {
        $trace = debug_backtrace();
        self::$ROOT_PATH = dirname($trace[0]['file']);
		if (isset($settings["path"])) {
			self::$settings["path"] = $settings["path"];
		}
		if (isset($settings["itcpt"])) {
			self::$settings["itcpt"] = $settings["itcpt"];
		}
		if (isset($settings["templates"])) {
			self::$settings["templates"] = $settings["templates"];
		}
		if (isset($settings["extension"])) {
			self::$settings["extension"] = $settings["extension"];
		}
		if (isset($settings["partials"])) {
			self::$settings["partials"] = $settings["partials"];
		}
		if (isset($settings["suffix"])) {
			self::$settings["suffix"] = $settings["suffix"];
		}
		if (isset($settings["itcpt_suffix"])) {
			self::$settings["itcpt_suffix"] = $settings["itcpt_suffix"];
		}
		if (isset($settings["debug"])) {
			self::$debug = $settings["debug"];
		}
		$base = str_replace('/', DIRECTORY_SEPARATOR, self::$ROOT_PATH . self::$settings["path"]);
		$mvc_include_path = __DIR__ . PATH_SEPARATOR . $base;
		$dirs = scandir($base);
		foreach ($dirs as $item) {
			if (substr($item, 0, 1) != "." && is_dir($base . DIRECTORY_SEPARATOR . $item)) {
				$mvc_include_path .= PATH_SEPARATOR . $base . DIRECTORY_SEPARATOR . $item;
			}
		}
		$itcpt = str_replace('/', DIRECTORY_SEPARATOR, self::$ROOT_PATH . self::$settings["itcpt"]);
		$mvc_include_path .= PATH_SEPARATOR . $itcpt;
		if (file_exists($itcpt)) {
            $dirs = scandir($itcpt);
            foreach ($dirs as $item) {
                if (substr($item, 0, 1) != "." && is_dir($itcpt . DIRECTORY_SEPARATOR . $item)) {
                    $mvc_include_path .= PATH_SEPARATOR . $itcpt . DIRECTORY_SEPARATOR . $item;
                }
            }
        }
        if (Initializer::$debug) {
            echo("[mvc] " . $mvc_include_path . "<br/>");
        }
		set_include_path($mvc_include_path);
		spl_autoload_register(function ($className) {
			$filename = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
			$include_paths = array_reverse(explode(PATH_SEPARATOR, get_include_path()));
			foreach($include_paths as $include_path){
				if (Initializer::$debug) {
					echo("[mvc] " . $include_path . DIRECTORY_SEPARATOR . $filename . "<br/>");
				}
				if (file_exists($include_path . DIRECTORY_SEPARATOR . $filename)) {
					if (Initializer::$debug) {
						echo("[mvc] Loading File " . $include_path . DIRECTORY_SEPARATOR . $filename . "<br/>");
					}
					include_once($include_path . DIRECTORY_SEPARATOR . $filename);
				}
			}
		});
	}
	
}
