<?php

namespace PhpTinyMVC;

class Router {
	private $entry;
	private $controller;
	private $action;
	private $params;
	
	public function __construct($pathinfo = null) {
		$this->entry = $_SERVER["SCRIPT_NAME"];
		if ($pathinfo == null) {
            if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])) {
                $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
            }
			if(!empty($_SERVER["PATH_INFO"])) {
				$route_parts = explode("/", $_SERVER["PATH_INFO"]);
			} else {
				$path = array_keys($_GET);
				if (empty($path)) {
					$route_parts = array("", "Index", "execute");
				} else {
					$route_parts = explode("/", $path[0]);
				}
			}
		} else {
			$route_parts = explode("/", $pathinfo);
		}
		array_shift($route_parts);
		$this->controller = ucfirst($route_parts[0]);
		$this->action = isset($route_parts[1]) ? $route_parts[1] : "execute";
		array_shift($route_parts);
		array_shift($route_parts);
		$this->params = $route_parts;
		if (Initializer::$debug) {
			print_r($this);
			echo("<br/>");
		}
	}
	
	public function getEntry() {
		return $this->entry;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getController() {
		return $this->controller;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function getPathInfo($withparams = false) {
		return "/" . $this->controller . "/" . $this->action . ($withparams ? "/" . implode("/", $this->params) : "");
	}
}
