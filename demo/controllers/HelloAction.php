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
