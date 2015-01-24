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

use PCB\Exception\PCBException;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\Asset\PathPackage;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class TemplateService {
	
	private $session, $root_dir;
	private $configs = array();
	
	public function __construct(Session $session = null, $configs = array(), $root_dir=null) {
		
		// set parameters
		$this->configs  = $configs;
		$this->root_dir = $root_dir;
		$this->session  = $session;
		
		// load template
		$this->template = $this->loadTemplate();
		
		// add helpers
		$this->template->set(new SlotsHelper());
		$this->template->set(new AssetsHelper());
		
		// add custom helpers
		$customHelpers = $this->getHelpers();
		if ( @count($customHelpers) > 0 ) {
			foreach ( $customHelpers as $helper ) {
					
				try {
		
					$namespace = '\\Libraries\\Helpers\\' .ucfirst($helper). 'Helper';
					if ( class_exists($namespace) ) {
						$this->template->set(new $namespace());
					}
					else throw new PCBException(ucfirst($helper). 'Helper class not found.');
				}
				catch( PCBException $e ) {
					$e->display_error();
				}
			}
		}		
		
		// path packages
		$this->template->get('assets')->addPackage('js', new PathPackage('/js/'));
		$this->template->get('assets')->addPackage('css', new PathPackage('/css/'));
		$this->template->get('assets')->addPackage('images', new PathPackage('/images/'));		
	}
	
	public function setDoctrine(DoctrineService $doctrine) {
		$this->template->addGlobal('doctrine', $doctrine);
	}
		
	public function setUser(UserService $user) {
		$this->template->addGlobal('pcb_user', $user);
	}
	
	public function setIPAdress(Request $request) {
		$this->template->addGlobal('ip_address', $request->getClientIp());
	}
		
	public function getTemplate() {
		return $this->template;		
	}
	
	public function getRootDir() {
		return $this->root_dir;
	}
		
	public function getSession() {
		return $this->session;
	}
		
	public function getConfig() {
		return $this->configs;
	}
		
	private function loadTemplate() {
		return new PhpEngine(new TemplateNameParser(), new FilesystemLoader( $this->root_dir. '/Views/%name%' ));
	}
	
	private function getHelpers() {
		return isset($this->configs["helpers"]) && is_array($this->configs["helpers"]) ? $this->configs["helpers"] : array();
	}	
}