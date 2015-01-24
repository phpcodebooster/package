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

use PCB\Helpers\FileHelper;
use PCB\Helpers\FormHelper;
use PCB\Exception\PCBException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewService {
	
	private 
		$request,
		$template,
		$css = array(),
		$js  = array(),
		$meta = array(),
	    $links = array();
	
	public function __construct(TemplateService $template, Request $request = null) {
		
		// set template and request
		$this->template = $template;
		$this->request  = $request;
		
		// set session to request
		$this->template->setIPAdress($this->request);
		$this->request->setSession($this->template->getSession());
	}
	
	public function getTemplateEngine() {
		return $this->template->getTemplate();
	}
	
	public function getRequest() {
		return $this->request;
	}
	
	public function getSession() {
		return $this->request->getSession();
	}
	
	public function setCSS($val) {
		$this->css[] = $val;
	}
	
	public function setJS($val) {
		$this->js[] = $val;
	}
	
	public function addMetaTag($tags=array()) {
		if ( is_array($tags) ) {
			foreach ( $tags as $tag ) {
				if ( is_array($tag) ) {
					 $tmp = "<meta";
					 foreach ( $tag as $name => $val ) {
						$tmp .= " {$name}='".addslashes($val)."'";
					 }
					 $this->meta[] = $tmp. " />";
				}
			}
		}
	}
	
	public function addLinkTag($tags=array()) {
		if ( is_array($tags) ) {
			foreach ( $tags as $tag ) {
				if ( is_array($tag) ) {
					$tmp = "<link";
					foreach ( $tag as $name => $val ) {
						$tmp .= " {$name}='".addslashes($val)."'";
					}
					$this->links[] = $tmp. " />";
				}
			}
		}
	}
		
	public function forward404IfInvalidAjax() {
		try {
			if (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
				throw new PCBException( "<b>Page Not Found</b>. Invalid Server Response." );
			}
		}
		catch ( PCBException $e ) {
			$e->display_error();
		}
	}
	 
	public function redirect($path='home', $status = 302) {
	
		if ( !headers_sent() ) {
	
			try {
				 
				$target = trim(strtolower($this->getWebURL()), '/'). '/' .trim(strtolower($path), '/');
				header("Location: {$target}/", $status);
			}
			catch( \PCB\Component\PCBException $e ) {
				$e->display_error();
			}
		  
			exit();
		}
	}
		
	public function exists($view) {
		return file_exists($this->template->getRootDir(). '/Views/' .$view);
	}
	
	public function render($view, array $parameters = array()) {
		
		try {
			
			// get template engine
			$php_engine = $this->getTemplateEngine();
			
			// make sure view exist
			if ( file_exists($this->template->getRootDir(). '/Views/' .$view) ) {
		
				// get assets helper
				$assetsHelper = $php_engine->get('assets');
		
				// we need to set needed helpers
				$php_engine->set(new FormHelper($this->request));
				$php_engine->set(new FileHelper($assetsHelper, $this->template->getConfig(), $this->css, $this->js, $this->meta, $this->links));
		
				// get contents of the master view
				$content = $php_engine->render(trim($view, '.php'). '.php', $parameters);
			}
			else throw new PCBException("View: <b>{$view}</b> does not exist.");
		}
		catch( PCBException $e) {
			echo $e->display_error();
		}
		
		return new Response($content);
	}
	
	public function forceDownload($file=null) {
	
		try {
	
			// check to see if file is on remote server
			if ( strpos($file, 'http://') !== FALSE || strpos($file, 'https://') !== FALSE )
			{
				// requert CURL
				$ch = curl_init();
	
				// set url
				curl_setopt($ch, CURLOPT_URL, $file);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
				// get output
				$file = curl_exec($ch);
	
				// close the request
				curl_close($ch);
	
				// check to see which file type
				$mimeType = 'application/' .strtolower(substr(strrchr($file, '.'), 1));
	
				// force file download
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mimeType);
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: '.filesize($file));
				header('Connection: close');
				readfile($file);
				exit();
			}
			else if( is_file($file) ) {
	
				// For IE
				if(ini_get('zlib.output_compression')) {
					ini_set('zlib.output_compression', 'Off');
				}
	
				// check to see which file type
				$mimeType = 'application/' .strtolower(substr(strrchr($file, '.'), 1));
	
				// force file download
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mimeType);
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: '.filesize($file));
				header('Connection: close');
				readfile($file);
				exit();
			}
			else throw new PCBException("Download File Not found.");
		}
		catch( PCBException $e) {
			echo $e->display_error();
		}
	}	
	
	private function getWebURL() {
		return $this->request->isSecure() ? 'https://' .$this->request->getHttpHost(). '/' : 'http://' .$this->request->getHttpHost(). '/';
	}	
}