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
 
 use PCB\Exception\PCBException;
 
 /**
 * PCB Console Application COntroller
 * 
 * @package    TemplateMaker
 * @subpackage TemplateMaker
 **/ 
 class TemplateMaker {
	 	
 	 /**
 	  * 
 	  * @var unknown_type
 	  */
 	 private $template;
 	 private $values = array();
 	 
 	/**
 	 * 
 	 * @param unknown_type $template
 	 */
	 public function setTemplate( $template = null )
	 {
	 	  $this->template = __DIR__. '/Templates/' .ucfirst($template). '.tpl';
	 }
	 
	 /**
	  * 
	  * @param unknown_type $vars
	  */
     public function set($key, $value) {
          $this->values[$key] = $value;
     }
	 
	 /**
	  * 
	  * @param unknown_type $filename
	  * @param unknown_type $ext
	  */
	 public function create( $path=null, $overwrite=false )
	 {
	 	  try {
		 	  // check file and verify filename
		 	  if ( file_exists($this->template) && $path ) {
		 	  	
		 	  	   // get file contents
		 	  	   $output = file_get_contents($this->template);
		 	  	   
		 	  	   // replace values
		 	  	   foreach ($this->values as $key => $value) {
		 	  	   	    $output = str_replace('${' .$key. '}', $value, $output);
		 	  	   }	 	  	

		 	  	   // auto create folders if does not exist
		 	  	   $folderExists = preg_match("/^(.*)\/([^\/]+)$/", $path, $file_paths);
		 	  	   if( $folderExists && !is_dir($file_paths[1]) ) {
			 	  	   mkdir($file_paths[1], 0777, true);
		 	  	   }
		 	  	   
		 	  	   // add data to file
		 	  	   if ( !file_exists($path) || $overwrite ) {
		 	  	   	    file_put_contents($path, $output);
		 	  	   }
		 	  }
		 	  else throw new PCBException("File not found: {$this->template}.");
	 	  }
	 	  catch ( PCBException $e ) {
	 	  	  $e->display_error();
	 	  }	 	  
	 }
 }