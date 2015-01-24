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
namespace PCB\Exception;

class PCBException extends \Exception {
		
	protected
	$_message,
	$_status,
	$_code,
	$_mode;

	public function __construct($message=null, $code='404') {
		$this->_message = $message;
		$this->_code    = $code;
		$this->_mode    = strtoupper(php_sapi_name());
	}

	public function display_error() {

		if ( $this->_mode == 'CLI' ) {
			 exit("\n" .$this->_message. "\n\n");
		}
		else {
			 $path =  __DIR__. "/Errors/{$this->_code}.php";
			 include $path;
			 exit();
		}
	}
}