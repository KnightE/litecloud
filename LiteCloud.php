<?php

// vim600: cindent tabstop=4 shiftwidth=4 

class LiteCloud {

	protected static $_classes 	= array(
		'storage' => 'LiteCloud_Tyrant',
	);

	protected static $_nodes	= array();

	public static $lookupRing;

	public static $storageRing;

	public static $lookupNodes 	= array();

	public static $storageNodes = array();

	public function __construct($config = array()) {
		self::init($config);
	}

	public static function config(array $config = array()) {
		foreach ($config as $key => $val) {
			$key = "_{$key}";
			if (isset(static::${$key})) 
				static::${$key} = $val + static::${$key};
		}
	}
	
	#--- Init and config ----------------------------------------------
	public static function init($config = array()) {
		self::config($config);
		foreach(self::$_nodes as $key => $value){
			if(strpos($key, 'lookup') !== false)
				self::$lookupNodes[$key] = $value;

			if(strpos($key, 'storage') !== false)
				self::$storageNodes[$key] = $value;
		}

		self::$lookupRing 	= self::generateRing($lookupNodes);
		self::$storageRing 	= self::generateRing($storageNodes);
	}

	public static function generateRing(array $nodes) {
		// fake servers, just for generate ring
		$servers = array();
		foreach($nodes as $k => $v) 
			$servers[$k] = array($k, 11211);

		$cache = new Memcached;
		$cache->setOption(Memcached::OPT_DISTRIBUTION, 			Memcached::DISTRIBUTION_CONSISTENT);
		$cache->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, 	true);
		$cache->setOption(Memcached::OPT_HASH, 					Memcached::HASH_FNV1_32);
		$cache->addServers($servers);
		return $cache;
	}

	public static function getLookupRing() {
		return self::$lookupRing;
	}

	public static function getStorageRing() {
		return self::$storageRing;
	}

	public static function getStorageNode($name) {
		return self::_genNode($name, self::$storageNodes);
	}

	public static function getLookupNode($name) {
		return self::_genNode($name, self::$lookupNodes);
	}

	private static function _genNode($name, array $nodes) {
		if(!isset($name) or !isset($nodes[$name])) 
			return null;

		static $cont = array();
		$server = $nodes[$name];
		$node	= self::$_classes['storage'];
		if(!isset($cont[$server])) {
			// autoload?
			$cache = new $node($name, $nodes);
			$cont[$server] = $cache;
		}

		return $cont[$server];
	}

	public static function locateLookupNode($key) {
		$name = self::getlookupRing()->getServerByKey($key);
		return self::getLookupNode($name['host']);
	}

	public static function locateStorageNode($key) {
		$name = self::getStorageRing()->getServerByKey($key);
		return self::getStorageNode($name['host']);
	}

	public static function get($key) {
		// Try to look it up directly
		$result	= self::locateStorageNode($key)->get($key);

		// Else use the lookup ring to locate the key
		if(!$result) {
			$storageNode 	= self::locateNode($key);
			$result 		= $storageNode->get($key);
		}

		return $result;
	}

	public static function set($key, $value) {
		$storageNode = self::locateNodeOrInit($key);
		return $storageNode->set($key, $value);
	}

	public static function incr($key, $offset = 1) {
		$storageNode = self::locateNodeOrInit($key);
		return $storageNode->incr($key, $offset);
	}

	public static function delete($key) {
		$storageNode = self::locateNode($key);
		if(empty($storageNode)) $storageNode = self::locateStorageNode($key);
		$storageNode->delete($key);

		self::locateLookupNode($key)->delete($key);

		return true;
	}

	public static function locateNodeOrInit($key) {
		$storageNode = self::locateNode($key);

		// init
		if(empty($storageNode)) {
			$storageName 	= self::getStorageRing()->getServerByKey($key);
			$server			= $storageName['host'];
			self::locateLookupNode($key)->set($key, $server);
			$storageNode	= self::getStorageNode($server);
		}

		return $storageNode;
	}

	public static function locateNode($key){
		$value 		= self::locateLookupNode($key)->get($key);
		if(!isset($value) or empty($value))
			return null;

		return self::getStorageNode($value);
	}

}
