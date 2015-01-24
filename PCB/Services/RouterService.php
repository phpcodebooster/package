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
  
 use PCB\Console\Runner;
 use PCB\Exception\PCBException;
 use Symfony\Component\HttpFoundation\Response;
 
 class RouterService {
 	
 	private
 		$view,
 		$action,
 		$doctrine,
 		$controller,
 		$parameters = array();
 	 	
 	public function __construct(ViewService $view = null, DoctrineService $doctrine) {
 		$this->view = $view;
 		$this->doctrine = $doctrine; 		
 	} 	
 	
 	public function route_model() {
 		
 		// GET ENTITY MANAGER
 		$em = $this->doctrine->getEntityManager();
 		
 		// CALL CONSOLE RUNNER
 		$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
 				'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
 				'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
 		));
 		 
 		// GET CONSOLE RUNNER
 		return \Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet); 		
 	}
 	
 	public function route($container, $model_console=false) {

 		try {
 			
 			// handle console
 			if ( strtoupper(php_sapi_name()) == 'CLI' ) {
 				
 				 // handle model console
 				 if ( $model_console ) {
 					return $this->route_model();
 				 }
 				
 				 echo "--------------------------------------------------------------------\n";
 				 echo "	PHP CODE BOOSTER  by Sandip Patel \n";
 				 echo "--------------------------------------------------------------------\n\n";
 				 
 				return Runner::run($container);
 			}
 			
	 		// get things we want
	 		list($this->controller, $this->action, $this->parameters) = $this->parseURL();
 		
	 		// default controller and action
	 		$this->controller = empty($this->controller) ? 'Home'  : $this->controller;
	 		$this->action     = empty($this->action)     ? 'index' : strtolower($this->action);
			$this->parameters = empty($this->parameters) ? array() : $this->parameters;
	 		
 			// get class namespace
 			$controller_namespace = '\\Controllers\\' .$this->controller. 'Controller';

 			// check if controller exist
 			if ( class_exists($controller_namespace) ) {

 				 // initialize controller
 				 $controller = new $controller_namespace();

 				 // get app config
 				 $controller->setContainer($container);

 				 // get response from the controller
 				 $response = call_user_func_array(array($controller, $this->action), @$this->parameters); 			

 				 // we need response object
 				 if (!$response instanceof Response) {
 				 	 throw new PCBException("<b>Invalid Response:</b> Did you forget to return response object?");
 				 }
 				 $response->send(); 				 
 			}
 			else throw new PCBException("<b>Controller Not Found:</b> {$this->controller}Controller.");
 		}
 		catch(PCBException $e ) {
 			$e->display_error();
 		}
 	}
 	
 	private function parseURL() {
 		
 		$request = $this->view->getRequest();
 		$params = explode('/', trim($request->getPathInfo(), '/'));
 		return array(
 				$this->getPreparedURL(array_shift($params)),
 				$this->getPreparedURL(array_shift($params), '_'),
 				$params
 		);
 	}
 	
 	private function getPreparedURL( $url, $delimit = '' ) {
 		return str_replace(' ', $delimit, ucwords(preg_replace('/[^a-zA-Z0-9]/s', ' ', trim($url))));
 	} 	
 }