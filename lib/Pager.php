<?php

namespace PhpTinyMVC;

class Pager {
	private $page = 1;
	private $size = 8;
	private $total_rows = 0;
	private $total_page = 1;
	private $router;
	
	public function __construct($router, $total, $curr = 1, $size = 8) {
		$this->router = $router;
		$this->total_rows = $total;
		$this->size = $size;
		$this->total_page = ceil($total / $size);
		if (isset($curr) && is_numeric($curr) && !strpos($curr,".") && $curr <= $this->total_page) {
			$this->page = $curr;
		}
	}
	
	public function get_offset() {
		return ($this->page - 1) * $this->size;
	}
	
	public function get_size() {
		return $this->size;
	}
	
	public function total_page() {
		return $this->total_page;
	}

    public function total_rows() {
        return $this->total_rows;
    }
	
	public function set_page($page) {
		if ($page < 1) {
			$this->page = 1;
		} else if ($page > $this->total_page) {
			$this->page = $this->total_page;
		} else {
			$this->page = $page;
		}
	}
	
	public function current_page() {
		return $this->page;
	}
	
	public function prev_page() {
		$page = $this->page - 1;
		if ($page < 1) {
			$page = 1;
		}
		return $page;
	}
	
	public function next_page() {
		$page = $this->page + 1;
		if ($page > $this->total_page) {
			$page = $this->total_page;
		}
		return $page;
	}
	
	public function generate() {
		if ($this->total_page > 1) {
            $pages = '';
			if ($this->total_page > 5) {
				$start = ($this->page - 2) >= 1 && ($this->page + 2) <= $this->total_page ? $this->page - 2 :
					(($this->page - 2) >= 1 ? $this->total_page - 4 : 1);
				for ($i = 0; $i < 5; $i++) {
                    $pages .= '<a ';
                    if ($start + $i == $this->current_page()) {
                        $pages .= 'href="javascript:void(0)" class="current"';
                    } else {
                        $pages .= 'href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/' . ($start + $i) . '"';
                    }
					$pages .= '>' . ($start + $i) . '</a>';
				}
			} else {
                for ($i = 1; $i <= $this->total_page(); $i++) {
                    $pages .= '<a ';
                    if ($i == $this->current_page()) {
                        $pages .= 'href="javascript:void(0)" class="current"';
                    } else {
                    	$pages .= 'href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/' . $i . '"';
					}
                    $pages .= '>' . $i . '</a>';
                }
			}
			if ($this->page <= 1) {
				$html = '<div class="pager"><a href="javascript:void(0)" class="disabled">首页</a>' . $pages . '<a href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/' . $this->total_page() . '">末页</a></div>';
			} else if ($this->page >= $this->total_page) {
				$html = '<div class="pager"><a href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/1">首页</a>' . $pages . '<a href="javascript:void(0)" class="disabled">末页</a></div>';
			} else {
				$html = '<div class="pager"><a href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/1">首页</a>' . $pages . '<a href="' . $this->router->getEntry() . $this->router->getPathInfo() . '/' . $this->total_page() . '">末页</a></div>';
			}
			return $html;
		}
	}
}

?>
