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
 
 use Symfony\Component\HttpFoundation\Request;
 
 class RequestService {
 	
 	private $request;
 	
 	public function __construct() {
 		$this->request = Request::createFromGlobals();
 	} 	
 	
 	public function getRequest() {
 		return $this->request;
 	}
 }