<?php

namespace dTRGallery\Manager;

use \Bakery\Application;
use \Bakery\Provider\ArrayAccessProvider;

class UserEntity extends ArrayAccessProvider implements \Bakery\Interfaces\UserEntity, \ArrayAccess {
	
	private
		$data = array();
	
	/**
	 * @param Application $app
	 */
	public function __construct(Application $app ){

		$this['app'] = $app;
		$this['db'] = $app['db'];
		
		$this->data['token_time'] = time();
		
	}
	
	/**
	 * @param unknown_type $val
	 */
	public function setUsername( $val ){
		$this->data['username'] = $val;
	}
	
	/**
	 * @param unknown_type $val
	 */
	public function setEmail( $val ){
		$this->data['email'] = $val;
	}
	
	/**
	 * 
	 */
	public function saveOrUpdate( $ignore = false ){
		
		$this->data['authed'] = true;
		
		$this->data['user.roles'] = array('ADMIN');
		 
		return $this->data;
		
	}
}

?>