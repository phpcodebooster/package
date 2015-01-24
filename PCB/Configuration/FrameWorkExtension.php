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
 namespace PCB\Configuration;
 
 use Symfony\Component\Yaml\Yaml;
 use Symfony\Component\Config\FileLocator;
 use Symfony\Component\DependencyInjection\Reference;
 use Symfony\Component\DependencyInjection\ContainerBuilder;
 use Symfony\Component\DependencyInjection\Extension\Extension;
 use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
 
 class FrameWorkExtension extends Extension {
 	
 	public function getAlias() {
 		return 'pcb.framework.extension';
 	}
 	 	
 	public function load(array $configs, ContainerBuilder $container) {

 		// time to load services
 		$loader = new YamlFileLoader($container, new FileLocator(__DIR__. '/Resources'));
 			
 		// get default and project configs
 		$config1 = Yaml::parse(__DIR__. '/Resources/settings.yml');
 		$config2 = Yaml::parse($container->getParameter('pcb.config_dir'). 'settings.yml');
 		
 		// handle priorities
		$configs = $this->processConfiguration(
		    new Configuration(),
		    array($config1, $config2)
		);

		// set configuration settings
		$container->setParameter('pcb.settings', $configs);
		
 		// set environment
		$prod = isset($configs['environment']) && ($configs['environment'] == 'production') ? true : false;
		
		// set errors based on environment
		error_reporting( !$prod ? E_ALL : 0 );
		ini_set('display_errors', !$prod);
		ini_set('display_startup_errors', !$prod);

		// session settings
		if (isset($configs['session'])) {
			$this->loadSessionConfiguration($configs['session'], $container, $loader);
		}
		
		// validator settings
		if (isset($configs['validator'])) {
			$this->loadValidatorConfiguration($configs['validator'], $container, $loader);
		}
				
		// request settings
		if (isset($configs['request'])) {
			$this->loadRequestConfiguration($configs['request'], $container, $loader);
		}
				
		// cache settings
		if (isset($configs['cache'])) {
			$this->loadCacheConfiguration($configs['cache'], $container, $loader);
		}
		
		// doctrine settings
		if (isset($configs['doctrine'])) {
			$this->loadDoctrineConfiguration($configs['doctrine'], $container, $loader);
		}

		// user settings
		if (isset($configs['user'])) {
			$this->loadUserConfiguration($configs['user'], $container, $loader);
		}
				
		// template settings
		if (isset($configs['template'])) {
			$this->loadTemplateConfiguration($configs['template'], $container, $loader);
		}
				
		// view settings
		if (isset($configs['view'])) {
			$this->loadViewConfiguration($configs['view'], $container, $loader);
		}
				
		// router settings
		if (isset($configs['router'])) {
			$this->loadRouterConfiguration($configs['router'], $container, $loader);
		}
 	}
 	 	
 	private function loadSessionConfiguration($configs, $container, $loader) {
 		
 		$loader->load('session.yml');
 		$container->getDefinition('session')
 				  ->addMethodCall('start');
 	}
 	
 	private function loadCacheConfiguration($configs, $container, $loader) {
 		$loader->load('cache.yml');
 	}
 	
 	private function loadDoctrineConfiguration($configs, $container, $loader) {
 		$loader->load('doctrine.yml');
 	}
 	 	
 	private function loadUserConfiguration($configs, $container, $loader) {
 		$loader->load('user.yml');
 	}
 	 	
 	private function loadValidatorConfiguration($configs, $container, $loader) {
 		$loader->load('validator.yml');
 	}
 	 	
 	private function loadRequestConfiguration($configs, $container, $loader) { 		
 		$loader->load('request.yml');	
 	}

 	private function loadRouterConfiguration($configs, $container, $loader) {
 		$loader->load('router.yml');
 	}

 	private function loadTemplateConfiguration($configs, $container, $loader) { 		
 		
 		$loader->load('template.yml');
 		$container->getDefinition('template')
			 	  ->addMethodCall('setUser', array(new Reference('user')))
 				  ->addMethodCall('setDoctrine', array(new Reference('doctrine')));
 	} 	
 	
 	private function loadViewConfiguration($configs, $container, $loader) {
 		$loader->load('view.yml');
 	}	
 }