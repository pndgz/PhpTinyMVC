<?php

namespace PhpTinyMVC;

class Config extends Singleton {

	protected $mvc_dirs = array();
	protected $interceptors = array();

	public function set_mvc_dirs($arr) {
	    $this->mvc_dirs = $arr;
    }

    public function set_controller_dir($dir) {
        $this->mvc_dirs['path'] = $dir;
    }

    public function set_interceptor_dir($dir) {
        $this->mvc_dirs['itcpt'] = $dir;
    }

    public function set_template_dir($dir) {
        $this->mvc_dirs['templates'] = $dir;
    }

    public function set_template_partial_dir($dir) {
        $this->mvc_dirs['partials'] = $dir;
    }

    public function set_interceptors($interceptors) {
	    $this->interceptors = $interceptors;
    }
	
}
