<?php

namespace PhpTinyMVC;

class Dispatcher {
	
	public static function dispatch() {
		ob_start();
		if (Initializer::$debug) {
			$stat_start_time = microtime(true);
		}
		self::callchain();
	}
	
	private static function callchain($pathinfo = null, $precontroller = null) {
		$router = new Router($pathinfo);
		$controller = $router->getController();
		$action = $router->getAction();
		try {
			$class_name = $controller . Initializer::$settings["suffix"];
			$app_dispatch_caller = new $class_name();
			$app_dispatch_caller->setRouter($router);
			$app_dispatch_caller->setAttribute("indexEntry", $router->getEntry());
			if ($precontroller != null) {
				$app_dispatch_caller->setDebug($precontroller->getDebug());
				$app_dispatch_caller->setAttributes($precontroller->getAttributes());
				$app_dispatch_caller->addErrors($precontroller->getErrors());
				$app_dispatch_caller->addMessages($precontroller->getMessages());
			} else {
				if (isset($_SESSION["MVC_TMP_FOWARD_ERRORS"])) {
					$app_dispatch_caller->addErrors($_SESSION["MVC_TMP_FOWARD_ERRORS"]);
					unset($_SESSION["MVC_TMP_FOWARD_ERRORS"]);
				}
				if (isset($_SESSION["MVC_TMP_FOWARD_MESSAGES"])) {
					$app_dispatch_caller->addMessages($_SESSION["MVC_TMP_FOWARD_MESSAGES"]);
					unset($_SESSION["MVC_TMP_FOWARD_MESSAGES"]);
				}
			}
			$ret = "PASS";
			foreach (Initializer::$interceptors as $interceptor) {
				$itr_name = ucfirst($interceptor) . Initializer::$settings["itcpt_suffix"];
				$it = new $itr_name();
				$ret = $it->intercept($app_dispatch_caller);
				if ($ret != "PASS") {
					break;
				}
			}
			if ($ret == "PASS") {
				$ret = $app_dispatch_caller->$action();
			}
			if (preg_match("/^chain:/", $ret)) {
				return self::callchain(substr($ret, 6), $app_dispatch_caller);
			} else if (preg_match("/^forward:/", $ret)) {
				$_SESSION["MVC_TMP_FOWARD_ERRORS"] = $app_dispatch_caller->getErrors();
				$_SESSION["MVC_TMP_FOWARD_MESSAGES"] = $app_dispatch_caller->getMessages();
				header("Location:" . $router->getEntry() . substr($ret, 8));
			} else if ($ret == "JSON") {
                $result = array(
                  "code" => empty($app_dispatch_caller->getErrors()) ?  $app_dispatch_caller->getCode() : 500,
                  "message" => empty($app_dispatch_caller->getErrors()) ?
                      implode(",", $app_dispatch_caller->getMessages()) :
                      implode(",", $app_dispatch_caller->getErrors()),
                  "data" => $app_dispatch_caller->getJsonData()
                );
                echo json_encode($result, JSON_NUMERIC_CHECK);
			} else if ($ret != "DEFAULT") {
                $app_dispatch_caller->renderMustache($ret);
            }
			if (isset($stat_start_time)) {
				echo("Total time for dispatching is :" .(microtime(true) - $stat_start_time) . "s.<br/>");
			}
			$output = ob_get_clean();
			echo($output);
		} catch(Exception $e) {
			echo($e->getMessage() . "\r\n");
			echo($e->getTraceAsString());
		}
	}
}
