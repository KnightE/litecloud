<?php

# vim60: ff=unix

abstract class LiteCloud_Common {

	public $name;
	public $servers = array();

	public function __construct($name, $nodes) {
		$this->name = $name;
		foreach((array)$nodes as $v)
			$this->servers[] = explode(':', $v);
	}

	public function __toString() {
		return $this->name;
	}

	abstract function get($key);
	abstract function set($key, $value);
	abstract function incr($key, $delta = 1);
	abstract function delete($key);

}
