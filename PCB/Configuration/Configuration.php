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
 
 use Symfony\Component\Config\Definition\Builder\TreeBuilder;
 use Symfony\Component\Config\Definition\ConfigurationInterface;
 
 class Configuration implements ConfigurationInterface {
 	
 	public function getConfigTreeBuilder() {
 		
 		$treeBuilder = new TreeBuilder();
 		$rootNode = $treeBuilder->root('framework');

 		$rootNode
	 		->children()
		 		->scalarNode('environment')
		 			->defaultValue('production')
		 		->end()
				->arrayNode('database')
					->useAttributeAsKey('name')
		            ->prototype('array')		            	
		                ->children()
			                ->scalarNode('dbname')->end()
			                ->scalarNode('user')->end()
			                ->scalarNode('password')->end()
			                ->scalarNode('host')->end()
			                ->scalarNode('driver')
			                	->isRequired()
			                    ->validate()
			                    ->ifNotInArray(array('pdo_mysql'))
			                        ->thenInvalid('Invalid database driver "%s"')
			                    ->end()
			                ->end()
		                ->end()
		            ->end()
		        ->end()
				->arrayNode('memcache')
					->useAttributeAsKey('name')
		            ->prototype('array')		            	
		                ->children()
			                ->scalarNode('host')->end()
			                ->scalarNode('port')->end()
		                ->end()
		            ->end()
		        ->end()	        
		 		->arrayNode('js')
		            ->prototype('scalar')
		            ->end()
		        ->end()
		        ->arrayNode('css')
			        ->prototype('scalar')
			        ->end()
		        ->end()	
		        ->arrayNode('helpers')
			        ->prototype('scalar')
			        ->end()
		        ->end()		        	        	 		
	 		->end()
 		;

 		$this->addCacheSettings($rootNode);
 		$this->addDoctrineSettings($rootNode);
 		$this->addUserSettings($rootNode);
 		$this->addRouterSettings($rootNode);
 		$this->addSessionSettings($rootNode);
 		$this->addValidatorSettings($rootNode);
 		$this->addRequestSettings($rootNode);
 		$this->addTemplateSettings($rootNode);
 		$this->addViewSettings($rootNode);
 		
 		return $treeBuilder;
 	}
 	
 	private function addCacheSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('cache')
			 		->info('cache configuration')
			 		->canBeUnset()
				 		->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		;	
 	}

 	private function addDoctrineSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('doctrine')
			 		->info('doctrine configuration')
			 		->canBeUnset()
				 		->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		;	
 	} 	
 	
 	private function addUserSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('user')
			 		->info('user configuration')
			 		->canBeUnset()
				 		->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		;	
 	}
 	 	
 	private function addRouterSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('router')
			 		->info('router configuration')
			 		->canBeUnset()
				 		->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		;	
 	}
 	
 	private function addSessionSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('session')
			 		->info('session configuration')
				 		->canBeUnset()
				 			->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		; 			
 	}

 	private function addValidatorSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('validator')
			 		->info('validator configuration')
				 		->canBeUnset()
				 			->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		; 	
 	}
 	 	
 	private function addRequestSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('request')
			 		->info('request configuration')
				 		->canBeUnset()
				 			->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		; 				
 	}

 	private function addTemplateSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('template')
			 		->info('template configuration')
				 		->canBeUnset()
				 			->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		; 				
 	} 

 	private function addViewSettings($rootNode) {
 		$rootNode
	 		->children()
		 		->arrayNode('view')
			 		->info('view configuration')
				 		->canBeUnset()
				 			->children()
				 		->end()
			 		->end()
		 		->end()
	 		->end()
 		; 	
 	} 	
 }