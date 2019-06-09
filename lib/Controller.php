<?php

namespace PhpTinyMVC;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Mustache_Loader_CascadingLoader;

class Controller {
	private $debug = false;
	protected $router;
	protected $params = array();
	protected $templateData = array();
	protected $errors = array();
	protected $messages = array();
	protected $jsonResult = array();
    protected $code = 200;
	
	public function execute() {
		// default action
	}
	
	public function setRouter($router) {
		$this->router = $router;
		$this->params = $router->getParams();
	}
	
	public function getRouter() {
		return $this->router;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function setDebug($debug = false) {
		$this->debug = $debug;
	}
	
	public function getDebug() {
		return $this->debug;
	}
	
	public function setAttribute($name, $value) {
		$this->templateData[$name] = $value;
	}
	
	public function getAttribute($name) {
		return $this->templateData[$name];
	}
	
	public function setAttributes($arr) {
		$this->templateData = array_merge($this->templateData, $arr);
	}
	
	public function getAttributes() {
		return $this->templateData;
	}
	
	public function addJsonData($name, $value) {
		$this->jsonResult[$name] = $value;
	}

	public function addJsonArray($name, $array) {
        $this->jsonResult[$name] = array_map(function($item){return $item->to_array();}, $array);
    }
	
	public function getJsonData() {
		return $this->jsonResult;
	}
	
	public function addError($value) {
		array_push($this->errors, $value);
	}
	
	public function addErrors($arr) {
		$this->errors = array_merge($this->errors, $arr);
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function clearErrors() {
		unset($this->errors);
	}
	
	public function addMessage($value) {
		array_push($this->messages, $value);
	}
	
	public function addMessages($arr) {
		$this->messages = array_merge($this->messages, $arr);
	}
	
	public function getMessages() {
		return $this->messages;
	}
	
	public function clearMessages() {
		unset($this->messages);
	}

    public function setCode($code) {
        $this->code = $code;
    }

    public function getCode() {
	    return $this->code;
    }
	
	public function renderMustache($module) {
		$mustTemplate = new Mustache_Engine();
		$mustacheLoader = new Mustache_Loader_FilesystemLoader(Initializer::$ROOT_PATH . Initializer::$settings["templates"], array("extension" => Initializer::$settings["extension"]));
		$mustTemplate->setLoader($mustacheLoader);
		$partialsLoader = new Mustache_Loader_CascadingLoader();
		if (is_array(Initializer::$settings["partials"])) {
			foreach(Initializer::$settings["partials"] as $partial) {
				$partialLoader = new Mustache_Loader_FilesystemLoader(Initializer::$ROOT_PATH . $partial, array("extension" => Initializer::$settings["extension"]));
				$partialsLoader->addLoader($partialLoader);
			}
		} else {
			$partialLoader = new Mustache_Loader_FilesystemLoader(Initializer::$ROOT_PATH . Initializer::$settings["partials"], array("extension" => Initializer::$settings["extension"]));
			$partialsLoader->addLoader($partialLoader);
		}
		$mustTemplate->setPartialsLoader($partialsLoader);
		if (isset($_SESSION)) {
            $mustTemplate->setHelpers($_SESSION);
        }
		$mustTemplate->addHelper("errors", $this->errors);
		$mustTemplate->addHelper("messages", $this->messages);
		if ($this->debug) {
            print_r($this->templateData);
			print_r($_SESSION);
			print_r($_POST);
			print_r($_GET);
			print_r($this->errors);
			print_r($this->messages);
		} else {
			echo $mustTemplate->render($module, $this->templateData);
		}
	}
	
}
