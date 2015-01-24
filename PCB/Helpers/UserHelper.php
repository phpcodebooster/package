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
	
	use PCB\Services\UserService;
	use Symfony\Component\Templating\Helper\Helper;
	
	class UserHelper extends Helper {
		
		private $user = null;
		
		public function __construct(UserService $user) {
			$this->user = $user;
		}
		
		public function getMember() {
			return $this->user->getMember();
		}
				
		public function get_member_token() {
			return $this->user->get_member_token();
		}
				
		public function getName() {
			return 'user';
		}
	}