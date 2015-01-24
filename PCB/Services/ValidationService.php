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
  
 use Symfony\Component\Validator\Validation;
 class ValidationService {
 	
 	private $validator;
 	
 	public function __construct() {
 		$this->validator = Validation::createValidator();
 	}
 	
 	public function getValidator() {
 		return $this->validator;
 	}
 }