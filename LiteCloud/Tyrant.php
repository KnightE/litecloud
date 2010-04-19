<?php

// vim600: ff=unix 

class LiteCloud_Tyrant extends LiteCloud_Common {

    private $_conn;

    public function get($key) {
        return $this->_getConn()->get($key);
    }

    public function set($key, $value) {
        return $this->_getConn()->put($key, $value);
    }

    public function incr($key, $delta = 1) {
        return $this->_getConn()->add($key, $delta);
    }

    public function delete($key) {
        return $this->_getConn()->out($key);
    }

    private function _getConn() {
		if(empty($this->_conn)) {
			$this->_conn = new Memcached;
			$this->_conn->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, true);
			foreach($this->servers as $v) {
				list($host, $port) = $v;
				$this->_conn->addServer($host, $port);
			}
		}
        return $this->_conn;
    }

}
