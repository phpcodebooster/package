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
 namespace PCB;
 
 use PCB\Configuration\FrameWorkExtension;
 use Symfony\Component\DependencyInjection\Scope;
 use Symfony\Component\DependencyInjection\ContainerAware;
 use Symfony\Component\DependencyInjection\ContainerBuilder;
 
 class FrameWork extends ContainerAware {
 	
 	public function boot() {

 	}
 	
 	public function build(ContainerBuilder $container) {
 		
 		// add request scope
 		$container->addScope(new Scope('request'));
 	}
 	
 	public function getExtension() {
 		return new FrameWorkExtension();
 	}
 }