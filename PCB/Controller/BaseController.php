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
	namespace PCB\Controller;
		
	use Symfony\Component\DependencyInjection\ContainerAware;
	
	class BaseController extends ContainerAware {
		
		public function getUser() {
			return $this->container->get('user');
		}
		
		public function signout($token = null) {
			return $this->getUser()->signout($token) && $this->redirectIfUserNotLoggedIn() ? true : false;
		}
		
		public function redirectIfUserNotLoggedIn($redirectTo='member/login/') {
			if ( !$this->getUser()->isAuthenticated() && $redirectTo ) {
				 $this->redirect($redirectTo);
			}
			return false;
		}
				
		public function redirect($url, $status = 302) {
			return $this->container->get('view')->redirect($url, $status);
		}
				
		public function forceDownload($file) {
			return $this->container->get('view')->forceDownload($file);
		}
		
		public function render($view, array $parameters = array()) {
			return $this->container->get('view')->render($view, $parameters);
		}		
				
		public function getDoctrine() {
			return $this->container->get('doctrine');
		}		
		
		public function getCacheManager() {
			return $this->container->get('cache');
		}		
		
		public function getValidator() {
			return $this->container->get('validator')->getValidator();
		}
				
		public function getRequest() {
			return $this->container->get('view')->getRequest();
		}
		
		public function setCSS($val) {
			$this->container->get('view')->setCSS($val);
		}
		
		public function setJS($val) {
			$this->container->get('view')->setJS($val);
		}
		
		public function addLinkTag($tags=array()) {
			$this->container->get('view')->addLinkTag($tags);
		}
				
		public function addMetaTag($tags=array()) {
			$this->container->get('view')->addMetaTag($tags);
		}
				
		public function has($id) {
			return $this->container->has($id);
		}
		
		public function get($id) {
			return $this->container->get($id);
		}
	}