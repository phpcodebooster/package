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
	
	use PCB\Services\DoctrineService;
	use Symfony\Component\HttpFoundation\Session\Session;
	
	class UserService {
		
		private $doctrine = null;
		private $session = null;
		
		public function __construct(DoctrineService $doctrine, Session $session) {
			$this->doctrine = $doctrine;
			$this->session = $session;
		}

		public function generate_random_pass() {
			return substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?" ), 0, 10 );
		}
		
		public function getMember() {
			return $this->session->get('PCB_USER', array());
		}		
		
		public function isAuthenticated() {
			return $this->session->has('PCB_USER');
		}		
		
		public function get_member_token() {
			$member = $this->getMember();
			return ( count($member) > 0 && $member['member_token']) ? $member['member_token'] : false;
		}
				
		public function signout( $token = null ) {
			return (!is_null($token) && $token == $this->get_member_token()) ? $this->logout() : false;
		}
				
		public function authenticate($user=null, $pass=null) {
			
			try {
				
				// return if already logged
				if ( $this->isAuthenticated() ) {
					 return true;
				}
				
				// get doctrine connection
				$em = $this->doctrine->getEntityManager();
				
				// get createQueryBuilder
				$qb = $em->createQueryBuilder();
				
				// find member
				$member = $qb->select('m')
					->from('Entity\Member', 'm')
					->where('m.username = ?1 AND m.password = ?2')
					->setParameter(1, $user)
					->setParameter(2, $pass)
					->getQuery()
					->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
				
				// get member token
				if ( $member && count($member) > 0 ) {
					 
					 // merge member token
					 $member = @array_merge(@$member[0], array(
					 	 'member_token' => $this->session->getId()
					 ));
					 
					 // create a session
					 $this->session->set('PCB_USER', $member);
				}
				
				// get member now
				return $this->isAuthenticated();
			}
			catch( \PCB\Component\PCBException $e ) {
				$e->display_error();
			}
		}		
		
		private function logout() {
			  $this->session->clear();
			return true;
		}
	}