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
 namespace PCB\Console;
 
 use PCB\Console\Commands\ViewCommand;
 use PCB\Console\Commands\CacheCommand;
 use PCB\Console\Commands\BatchCommand;
 use PCB\Console\Commands\AssetsCommand;
 use PCB\Console\Commands\ControllerCommand;
 
 /**
 * PCB Console Application COntroller
 * 
 * @package    Runner
 * @subpackage Runner
 **/ 
 class Runner {
	 	
 	static function run($container) {
 		$application = new \Symfony\Component\Console\Application();
 		$application->add(new ViewCommand(null, $container));
 		$application->add(new BatchCommand(null, $container));
 		$application->add(new AssetsCommand(null, $container));
 		$application->add(new ControllerCommand(null, $container));
 		$application->add(new CacheCommand(null, $container));
 		$application->run();
 	}
 }