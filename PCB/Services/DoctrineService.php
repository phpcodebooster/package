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
  
 class DoctrineService {
 	
 	private $configs = array(); 	
 	private static $dbal_conns = array();
 	private static $em_conns = array();
 	private $doctrineConfig = null;
 	
 	public function __construct($root_dir=null, $configs = array()) {
 		
 		// load configs
 		$this->configs = $configs;
 	
 		// get environment
 		$env = isset($this->configs['environment']) && ($this->configs['environment'] == 'production') ? true : false;
 		
 		/** doctrine configuration **/
 		$this->doctrineConfig = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array( $root_dir. '/Models' ), $env, NULL, NULL, FALSE);
 		
 		// set other dirs
 		$this->doctrineConfig->setProxyDir( $root_dir. '/Models/Proxy' );
 		$this->doctrineConfig->setProxyNamespace('Models\Proxy');
 		$this->doctrineConfig->setAutoGenerateProxyClasses(true); 		
 	} 	 	
 	
 	public function getDatabaseSettings() {
 		return isset($this->configs["database"]) ? $this->configs["database"] : FALSE;
 	} 	

 	public function getEntityManager($conn='default') {
 	
 		if( in_array($conn, self::$em_conns) ) {
 			return self::$em_conns[$conn];
 		}
 	
 		// got entity manager
 		$em = \Doctrine\ORM\EntityManager::create($this->getDBAL($conn), $this->doctrineConfig);
 	
 		// register enum
 		if ( $em instanceof \Doctrine\ORM\EntityManager ) {
 			 
 			/** register enum type */
 			$platform = $em->getConnection()->getDatabasePlatform();
 			$platform->registerDoctrineTypeMapping('enum', 'string');
 		}
 	
 		return self::$em_conns[$conn] = $em;
 	}
 	 
 	public function getDBAL($conn='default') {
 	
 		if( in_array($conn, self::$dbal_conns) ) {
 			return self::$dbal_conns[$conn];
 		}
 		return self::$dbal_conns[$conn] = \Doctrine\DBAL\DriverManager::getConnection( $this->getConnection($conn), new \Doctrine\DBAL\Configuration());
 	}
 	 
 	private function getConnection($key) {
 		return isset($this->configs["database"]) && isset($this->configs["database"][$key]) ? $this->configs["database"][$key] : FALSE;
 	} 	
}