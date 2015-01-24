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
	
	class FileHelper extends Helper {
		
		private $helper = null;
		private $css = array();
		private $js = array();
		private $meta = array();
		private $links = array();		
		private $settings = array();
		
		public function __construct($helper=null, $settings=array(), $css_files=array(), $js_files=array(), $meta_tags=array(), $links = array()) {
			
			// set asset helper
			$this->helper = $helper;
			$this->settings = $settings;
			
			// get default css and js
			$default_js  = $this->getJsSettings();
			$default_css = $this->getCssSettings();
			
			// set default css files
			if ( isset($default_css) && !empty($default_css) ) {
				 if( is_array($default_css) ) {
					foreach( $default_css as $css ) {
						$this->css[] = $css;
					}
				 }
				 else $this->css[] = $default_css;
			}
			
			// set default js files
			if ( isset($default_js) && !empty($default_js) ) {
				 if( is_array($default_js) ) {
					foreach( $default_js as $js ) {
						$this->js[] = $js;
					}
				 }
				 else $this->js[] = $default_js;
			}
						
			// final settings
			$this->links = $links;
			$this->meta = $meta_tags;
			$this->js   = @array_merge($this->js,  $js_files);
			$this->css  = @array_merge($this->css, $css_files);
		}
		
		public function include_stylesheets() {
			
			$cssFiles  = Array();
			foreach($this->css as $str) {
				if (strrpos ($str, 'http') !== FALSE) {
					$cssFiles[] = "<link rel='StyleSheet' type='text/css' href='" .trim($str). "' media='all' />";
				}
				else if (strrpos ($str, '.css') !== FALSE) {
					$cssFiles[] = "<link rel='StyleSheet' type='text/css' href='" .$this->helper->getUrl($str). "' media='all' />";
				}
				else {
					$cssFiles[] = "<link rel='StyleSheet' type='text/css' href='" .$this->helper->getUrl($str. '.css'). "' media='all' />";
				}
			}
			$cssFiles[] = "<link rel='shortcut icon' href='favicon.ico'>";
			return implode("\n\t", $cssFiles). "\n";			
		}

		public function include_javascripts() {
			
			$jsFiles = Array();
			foreach($this->js as $str) {
				if (strrpos ($str, 'http') !== FALSE) {
					$jsFiles[] = "<script type='text/javascript' src='" .trim($str). "'></script>";
				}
				else if (strrpos ($str, '.js') !== FALSE) {
					$jsFiles[] = "<script type='text/javascript' src='" .$this->helper->getUrl($str). "'></script>";
				}
				else {
					$jsFiles[] = "<script type='text/javascript' src='" .$this->helper->getUrl($str. '.js'). "'></script>";
				}
			}
			return implode("\n\t", $jsFiles). "\n";				
		}
		
		public function include_linktags() {
			return implode("\n\t", $this->links). "\n";
		}
				
		public function include_metatags() {
			return implode("\n\t", $this->meta). "\n";
		}
		
		public function getCssSettings() {
			return isset($this->settings["css"]) ? $this->settings["css"] : false;
		}
		
		public function getJsSettings() {
			return isset($this->settings["js"]) ? $this->settings["js"] : false;
		}
				
		public function getName() {
			return 'file';
		}
	}