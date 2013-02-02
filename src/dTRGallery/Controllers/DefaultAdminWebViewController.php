<?php

namespace dTRGallery\Controllers;

use Bakery\Application;
use Bakery\Interfaces\ControllerProviderInterface;
use Bakery\Manager\HTTP\RequestManager;

class DefaultAdminWebViewController implements ControllerProviderInterface{

	/* (non-PHPdoc)
	 * @see \Bakery\Provider\ControllerProviderInterface::connect()
	 */
	public function connect( Application $app ) {
				
		// @TODO: Auto-generated method stub
		$app->route("/", function(RequestManager $request) use ( $app ){
			
			return $app['twig']->render("admin/homepage.twig", array(
				
			));

		})->bind("admin.homepage");
		
		// @TODO: Auto-generated method stub
		$app->route("/albums", function(RequestManager $request) use ( $app ){
						
		})->bind("admin.albums");
		
		
		/**
		 * Uploader Interface
		 * 
		 */
		$app->route("/uploader", function(RequestManager $request) use ( $app ){
		
			return $app['twig']->render("admin/uploader.twig", array(
				
			));
			
		})->bind("admin.uploader");
		
		
		/**
		 * Upload packages
		 *
		 */
		$app->route("/uploader/start", function(RequestManager $request) use ( $app ){
		
			print_r($request->requestAll());
				
		})->bind("admin.upload");
		
		
		/**
		 * Manage posted comments
		 * 
		 */
		$app->route("/comments", function(RequestManager $request) use ( $app ){
		
		})->bind("admin.comments");
		
		
		/**
		 * Manage the page settings
		 * 
		 */
		$app->route("/settings", function(RequestManager $request) use ( $app ){
		
		})->bind("admin.settings");
		
		/**
		 * Manage the admin account details
		 * 
		 */
		$app->route("/account", function(RequestManager $request) use ( $app ){

		})->bind("admin.account");
		
		return $app;
		
	}
	
}