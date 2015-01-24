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
	namespace PCB\Helpers;
	
	use Symfony\Component\Templating\Helper\Helper;
	
	class FormHelper extends Helper {
		
		private $request;
		private $elements=array();
		
		public function __construct($request) {
			$this->request = $request;
		}
				
		public function getName() {
			return 'form';
		}		
	}