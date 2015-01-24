<?php

/**
 * ---------------------------------
 * PHP CODE BOOSTER
 * ---------------------------------
 *
 * @Author: Sandip Patel
 * @package PHPCodebooster
 * @version 5.0
 * @copyright (c) 2014, Sandip Patel
 **/
 namespace PCB\Services;
  
 class CacheService {
 	
 	private $configs = array();
 	private static $apc_conns = array();
 	private static $memcache_conns = array();
 	
 	public function __construct($root_dir=null, $configs = array()) {
 		$this->configs = $configs;
 	} 	 	
 	
 	public function getApcCache($conn='default') {
 	
 		if( in_array($conn, self::$apc_conns) ) {
 			return self::$apc_conns[$conn];
 		}
 	
 		return self::$apc_conns[$conn] = ( extension_loaded('apc') && ini_get('apc.enabled') ) ? new \Doctrine\Common\Cache\ApcCache() : FALSE;
 	}
 	
 	public function getMemcache($conn='default') {
 	
 		if( in_array($conn, self::$memcache_conns) ) {
 			return self::$memcache_conns[$conn];
 		}
 	
 		// connect to memcache
 		$memcache = $this->getMemcacheServer($conn);
 	
 		// check for drivers
 		if ( $memcache ) {
 	
 			// set doctrine memcache drivers
 			$memcacheConn = new \Doctrine\Common\Cache\MemcacheCache();
 			$memcacheConn->setMemcache($memcache);
 			 
 			return self::$memcache_conns[$conn] = $memcacheConn;
 		}
 	
 		return false;
 	} 	
 	
 	private function getMemcacheSettings($key) {
 		return isset($this->configs["memcache"]) && isset($this->configs["memcache"][$key]) ? $this->configs["memcache"][$key] : FALSE;
 	}
 	
 	private function getMemcacheServer($conn) {
 	
 		// get default settings
 		$settings = $this->getMemcacheSettings($conn);
 	
 		// check for memcache extension
 		if ( extension_loaded('memcache') && class_exists('Memcache') ) {
 			 $memcache = new \Memcache;
 			return $memcache->connect($settings['host'], $settings['port']) ? $memcache : FALSE;
 		}
 	
 		return FALSE;
 	} 	
}