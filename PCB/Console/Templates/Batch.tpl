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
 require dirname(__DIR__). '/../../../System/bootstrap.php';
	
 // bootsrap
 $bootstrap = new PCBBootStrap(dirname(__DIR__));
 $bootstrap->console();