<?php

namespace dTRGallery\Controllers;

// ini_set('display_errors',1);
// error_reporting(E_ALL);

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
			
			$error = ($_SESSION['error'] ? $_SESSION['error'] : '');
			$name = ($_SESSION['name'] ? $_SESSION['name'] : '');
				
			unset($_SESSION['error']);
			unset($_SESSION['name']);
				
			return $app['twig']->render("admin/albums.twig", array(
				"error" => $error,
				"name" => $name,
				"albums" => $app['db']->fetchAll("SELECT * FROM dtr_gallery.albums order by display_order"),
			));
			
		})->bind("admin.albums");

		// @TODO: Auto-generated method stub
		$app->route("/albums/update", function( RequestManager $request, $response = array() ) use ( $app ){

			if( $request->posted('delete') ){
				
				$app['db']->executeQuery("DELETE FROM dtr_gallery.albums WHERE id=?", array($request->post('albumid')));
				
				header('Location: '.$app['url_generator']->generate('admin.albums'));
				
			}else{
				$fields = array();
				$fields[] = "name=?";
				$fields[] = "slug=?";
				$fields[] = "description=?";
				$fields[] = "feature=?";
				$fields[] = "update_date=".time();
				$fields[] = "permissions=?";
				//$fields[] = "display_order=?";
				$fields[] = "visible=?";
				
				$values = array();
				$values[] = $request->post('name');
				$values[] = b_filter("create_slug", $request->post('name'));
				$values[] = $request->post('description');
				$values[] = str_replace("/64/", "/256/", $request->post('feature'));
				$values[] = $request->post('permissions');
				//$values[] = $request->post('display_order');
				$values[] = $request->post('visible');
				$values[] = $request->post('id');
				
				try{
					$_SESSION['response'] = 'success';
					$app['db']->update("dtr_gallery.albums", $fields, $values, "id=?");
				}
				catch(\PDOException $e){
					$_SESSION['response'] = 'fail';
					$_SESSION['error'] = $e->getMessage();
				}
				
				header('Location: '.$app['url_generator']->generate('admin.album.edit', array($request->post('name'))));
			
			}

		
		})->method('POST')->bind("admin.album.update");
		
		$app->route("/albums/update_display_order", function( RequestManager $request, $response = array() ) use ( $app ){
		
			$order = $request->request('data');
			
			try{
				if(sizeof($order) > 0){
					foreach($order as $o => $album){
						
						$app['db']->executeQuery("UPDATE dtr_gallery.albums set display_order=$o WHERE id=$album");
						
					}
					
				}
			}
			catch(\Exception $e){
				
				$error = $e->getMessage();
				
			}
			
			if(empty($error)){
				return json_encode(array('status' => true, 'response' => 'Display Order Updated!'));
			}
			
			return json_encode(array('status' => false, 'response' => $error));
			
		})->method('POST')->bind("admin.album.update_display");
				
		// @TODO: Auto-generated method stub
		$app->route("/albums/create", function( RequestManager $request, $response = array() ) use ( $app ){
			
			$q = $app['db']->fetchAll("SELECT id FROM dtr_gallery.albums where name=?", array($request->post('val')));
			
			if(sizeof($q) > 0){
				
				$_SESSION['error'] = 'Album name already exists!';
				$_SESSION['name'] = $request->post('val');
				
				header("Location: ".$app['url_generator']->generate('admin.albums'));
				
			}
			else{
				
				$app['db']->insert("dtr_gallery.albums", array("name=?", "slug=?", "hash=?", "create_date=".time(), "update_date=".time()), array($request->post('val'), b_filter("create_slug", $request->post('val')), md5($request->post('val'))));
				
				$app['db']->update("dtr_gallery.albums", array("display_order=?"), array($app['db']->lastInsertId(),$app['db']->lastInsertId()), "id=?");
				
				$response['successful'] = $app['db']->lastInsertId();

				$sizes = array('fullsize', '512', '256', '64');
					
				$album = $request->post('val');
					
				$album_dir = GALLERY_PATH.b_filter("create_slug", $album)."/";

				if(!is_dir($album_dir)){
				
					mkdir($album_dir);
				
					foreach($sizes as $size){
							
						if(!is_dir($album_dir."/".$size)){
				
							mkdir($album_dir."/".$size);
				
						}
							
					}
				
				}
				
				header("Location: ".$app['url_generator']->generate('admin.album.edit', array($request->post('val'))));
				
			}
			
			
		
		})->method('POST')->bind("admin.album.create");
		
		// @TODO: Auto-generated method stub
		$app->route("/albums/{album}", function(RequestManager $request, $album) use ( $app ){
			
			$album = urldecode($album);
			
			$response = array('response' => '', 'error' => '');
			
			//($_SESSION ? $_SESSION : );
			
			unset($_SESSION['response']);
			unset($_SESSION['error']);
			
			$albums = $app['db']->fetchAssoc("SELECT * FROM dtr_gallery.albums where name=?", array($album));
			
			if(empty($albums)){
				header("Location: ".$app['url_generator']->generate('admin.albums'));
			}
			
			$webPath = "_thumbs/{$albums['slug']}/256/";
			$fsPath = WEB_PATH . $webPath;
				
			$images = array();
			
			try{
				$di = new \DirectoryIterator($fsPath);
				foreach($di as $image){
					if(!$image->isDir()){
						$images[] =  $image->getFilename();
					}
				}
			}
			catch(\Exception $e){
				$response['response'] = 'fail';
				$response['error'] = $e->getMessage();
			}
			
			$albums['image_path'] = $webPath;
			
			return $app['twig']->render("admin/album.edit.twig", array(
					"album" => $albums,
					"images" => $images,
					"response" => $response['response'],
					"error" => $response['error'],
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
		$app->route("/upload/start", function(RequestManager $request) use ( $app ){

			$response = array();
			
			$sizes = array('fullsize', '512', '256', '64');
			
			$album = $request->post('album');
			
			$album_dir = GALLERY_PATH. b_filter("create_slug", $album)."/";

			if(!is_dir($album_dir)){
								
				mkdir($album_dir);
				
				foreach($sizes as $size){
					
					if(!is_dir($album_dir."/".$size)){
						
						mkdir($album_dir."/".$size);
						
					}
					
				}
				
				$response[] = "Makign Album Directory";
			}
			
			foreach($_FILES['images']['name'] as $key => $file){
				
				/*$extension = substr($file, strrpos($file, "."));

				$destfile = substr(sha1($file), 0, 9).$extension;*/
				//echo "Uploading File: $file\n";
				if( move_uploaded_file( $_FILES['images']['tmp_name'][$key], "{$album_dir}fullsize/{$file}" )){
					//echo "Uploaded File: $file\n";
					$images[] = $file;
				}
				
				$img = $app['imager']->load("{$album_dir}fullsize/{$file}");
				
				foreach($sizes as $size){
				
					if(is_numeric($size)){
						//echo "Resized File: $file to {$album_dir}{$size}/{$file}\n";
						
						$img->resizeToWidth($size);
						$img->save("{$album_dir}{$size}/{$file}");
					}
				}
				
			}
			
			return json_encode(array("album" => b_filter("create_slug", $album)."/", "imgs" => $images));
			
			// need to resize image
							
		})->method('POST')->bind("admin.upload");
		
		
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
