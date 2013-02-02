<?php

/*
 * This file is part of the Bakery framework.
 *
 * (c) Mike Mackintosh <mike@bakeryframework.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dTRGallery\Manager;

use \Bakery\Application;
use \Bakery\Provider\ArrayAccessProvider;
use \Bakery\Provider\LdapPantryProvider;
use \dTRGallery\Manager\UserEntity;

/**
 * @author mackmi4
 *
 */
class User extends ArrayAccessProvider implements \Bakery\Interfaces\User, \ArrayAccess {
	
	private 
		$roles = array(
				'SUPERADMIN' => 1024 ,
				'ADMIN' => 512,
				'VENDOR' => 32,
				'USER' => 16
				);
	
	/**
	 * @param Application $app
	 */
	public function __construct( Application $app ){
		
		$this['config'] = $app['security.options'];
		$this['app'] = $app;
		$this['isAuthed'] = false;
		
		if(	isset($_SESSION['bakery.user']) ){
			$_data = unserialize($_SESSION['bakery.user']);
			if( time() - $_data['token_time'] > 3600 ){
				
				return;
				
			}else{
				
				$this['isAuthed'] = true;
				
				$_data['token_time'] = time();
				
				return $this['security.user.data'] = $_data;
				
			}
		
		}
		
	}
	
	/**
	 * @return \Bakery\Provider\LdapPantryProvider|boolean
	 */
	public function authenticate( ){
		$this['app']['hologram']->setModule("AUTH")->log(\Bakery\Utilities\Hologram::NORMAL, "Authentication Request Recvd");
		
		$_authType = $this['config']['login']['auth_type'];
		
		$username = $_POST['username']; 
		$password = sha1($_POST['password']);
		
		
		if( $_authType == "DB" ){
			
			$this['app']['hologram']->setModule("AUTH")->log(\Bakery\Utilities\Hologram::NORMAL, "[$username] Authing LDAP");
							
			// Authenticate
			$results = $this['app']['db']->fetchAll('SELECT * FROM dtr_gallery.users WHERE username=? and password=?', array($username, $password));
			
			if(sizeof($results) == 0 ){
				
				$this['app']['security.user.error'] = "Invalid username or password";
				
				return;
								
			}
			
			$this['app']['hologram']->setModule("AUTH")->log(\Bakery\Utilities\Hologram::NORMAL, "[$username] Authentication Successful");
											
			// Authentication Successful
			$user = new UserEntity( $this['app'] );
			$user->setUsername( $details['username'] );
			$user->setEmail( $details['email'] );
					
			$this['security.user.data'] = $user->saveOrUpdate();
		}

		$_SESSION['bakery.user'] = serialize($this['security.user.data']);
			
		return $this['security.user.data'];		
		
	}
	
	
	/**
	 * @return \Bakery\Manager\User
	 */
	public function isAuthed(){
		
		return $this['isAuthed'];
		
	}
	
	/**
	 * 
	 */
	public function checkUserRoles( $role = "USER" ){
		
		foreach($this['security.user.data']['user.roles'] as $userRole){
			
			if($this->roles[$role] <= $this->roles[$userRole]){

				return true;
				
			}
			
		}
		
		return false;
		
	}


}