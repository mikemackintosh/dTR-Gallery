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
			
			return $app['twig']->render("admin/albums.twig", array(
				"albums" => $app['db']->fetchAll("SELECT * FROM dtr_gallery.albums order by display_order"),
			));
			
		})->bind("admin.albums");

		// @TODO: Auto-generated method stub
		$app->route("/albums/update", function( RequestManager $request, $response = array() ) use ( $app ){
		
			$app['db']->update("dtr_gallery.albums", array($request->post('field') ."=?"), array($request->post('val'),$request->post('albumid')), "id=?");
				
			$response['successful'] = true;
				
			return json_encode($response);
		
		})->bind("admin.album.update");
		// @TODO: Auto-generated method stub
		$app->route("/albums/create", function( RequestManager $request, $response = array() ) use ( $app ){
			$q = $app['db']->fetchAll("SELECT id FROM dtr_gallery.albums where name=?", array($request->post('val')));
			if(sizeof($q) > 0){
				$response['successful'] = false;
			}
			else{
				$app['db']->insert("dtr_gallery.albums", array("name=?"), array($request->post('val')));
				$app['db']->update("dtr_gallery.albums", array("display_order=?"), array($app['db']->lastInsertId(),$app['db']->lastInsertId()), "id=?");
				$response['successful'] = $app['db']->lastInsertId();
			}
			
			return json_encode($response);
		
		})->bind("admin.album.create");
		
		// @TODO: Auto-generated method stub
		$app->route("/albums/{album}", function(RequestManager $request, $album) use ( $app ){
				
			return $app['twig']->render("admin/album.edit.twig", array(
					"album" => $app['db']->fetchAssoc("SELECT * FROM dtr_gallery.albums where name=?", array($album)),
			));
				
		})->regex('album', '.*')->bind("admin.album.edit");
		
		
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