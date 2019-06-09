# PhpTinyMVC

> Php 简易 MVC 控制库

## 安装

    composer require pndgz/php-tiny-mvc
    
    
## 使用 index.php

    <?php
    
    require_once __DIR__ . "/../vendor/autoload.php";
    
    use PhpTinyMVC\Initializer;
    use PhpTinyMVC\Dispatcher;
    
    //Initializer::$debug = true;
    Initializer::initialize();
    Dispatcher::dispatch();
    
## Demo: HelloAction.php

    <?php
    
    use PhpTinyMVC\Controller;
    
    class HelloAction extends Controller {
    
        public function __construct() {
            $this->addJsonData("keywords", "I'am Keywords");
        }
    
        public function friend() {
            $this->addJsonData("myname", "BINGO");
            return "JSON";
        }
    
        public function myname() {
            $this->setAttribute("name", "BINGO");
            return "hello";
        }
    
    }

即可访问：

/index.php/hello/friend (Restful 接口，返回 JSON)

/index.php/hello/myname (后端渲染 mustache 模板页面)
    
    


