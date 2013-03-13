<?php

namespace dTRGallery\Controllers;

use Bakery\Application;
use Bakery\Interfaces\ControllerProviderInterface;
use Bakery\Manager\HTTP\RequestManager;

class DefaultWebViewController implements ControllerProviderInterface{

	/* (non-PHPdoc)
	 * @see \Bakery\Provider\ControllerProviderInterface::connect()
	 */
	public function connect( Application $app ) {
				
		$_meta = $app['config']->getSection("Gallery");
		$_galleryDir = getcwd()."/gallery/";

		$app->init("imager", function() use ($app){
			return new \dTRGallery\Provider\ImagerServiceProvider();
		});
				
		// @TODO: Auto-generated method stub
		$app->route("/", function(RequestManager $request) use ($app, $_meta, $_galleryDir){
			
			$albums = array();
			
			/*foreach(new \DirectoryIterator($_galleryDir) as $dir){
				
				if(!$dir->isDot() && $dir->getFilename() != "_thumbs"){
					
					$_albumMeta = new \stdClass;
					
					if(file_exists($_galleryDir.$dir->getFilename().'/meta.json')){
						
						$_albumMeta = json_decode(file_get_contents($_galleryDir.$dir->getFilename().'/meta.json'));

					}
					else{
						
						$_albumMeta->order = 0;
						
					}
					
					if(file_exists($_galleryDir.$dir->getFilename().'/link.jpg')){
					
						$image = "link.jpg";
						$images = "0";
					
					}
					else if(file_exists($_galleryDir.$dir->getFilename().'/thumb.jpg')){
					
						$image = "thumb.jpg";
					
					}
					else{
					
						$images = scandir($_galleryDir.$dir->getFilename());
						array_shift($images);
						array_shift($images);
						array_shift($images);
						$image = array_shift($images);
					}
					
					$albums[$dir->getFilename()] = array("img" => "/get_thumb/{$dir->getFilename()}/{$image}", "size" => sizeof($images));
					
				}
				
			}*/
			
			return $app['twig']->render("homepage.twig", array(
				
				"albums" => $albums,
					
			));
			
		})->bind("gallery_view");
		
		// @TODO: Auto-generated method stub
		$app->route("/album/{album_name}", function(RequestManager $request, $album_name) use ($app, $_meta, $_galleryDir){
			
			$album_name = urldecode($album_name);
			
			/*foreach(new \DirectoryIterator($_galleryDir.$album_name) as $dir){
			
				if(!$dir->isDot() && $dir->getFilename() != "_thumbs" && $dir->getFilename() != "thumb.jpg"){
						
					$_albumMeta = new \stdClass;
						
					if(file_exists($_galleryDir.$dir->getFilename().'/meta.json')){
			
						$_albumMeta = json_decode(file_get_contents($_galleryDir.$dir->getFilename().'/meta.json'));
			
					}
					
					$pictures[$dir->getFilename()] = array("img" => "/get_thumb/{$dir->getFilename()}/{$image}", "size" => filesize());
						
				}
			
			}
			*/
			return $app['twig']->render("album_view.twig", array(
				"album" => $album_name,
				"pictures" => $pictures,
			));
			
		})->regex("album_name", "[A-Za-z0-9-%_.]+")->bind("album_view");
		
		// @TODO: Auto-generated method stub
		$app->route("/album/{album_name}/{picture}", function(RequestManager $request, $album_name, $picture) use ($app, $_meta, $_galleryDir){
				
			$album_name = urldecode($album_name);
			$picture = urldecode($picture);
				
			return $app['twig']->render("picture_view.twig", array(
					"var" => "var",
			));
				
		})->regex("album_name", "[A-Za-z0-9-%_.]+")->regex("picture", "[A-Za-z0-9-_.]+")->bind("picture");
		
		// @TODO: Auto-generated method stub
		$app->route("/get_thumb/{album_name}/{picture}", function(RequestManager $request, $album_name, $picture) use ($app, $_meta, $_galleryDir){
			
			header( 'Content-Type: image/jpeg' );
			/*$_webPath = "web/_thumbs/";
			$path = urldecode("$_galleryDir$album_name/$picture");
			$hash = sha1($path);
			
			if(!is_dir($_webPath.$album_name)){
				
				mkdir($_webPath.$album_name);
				
			}
			
			if(!file_exists($_webPath . $album_name . $hash.".jpg")){
							
				$img = $app['imager']->load("$path");
				$img->resizeToWidth(768);
				$img->save($_webPath.$album_name.$hash."_768.jpg");

				$img->resizeToWidth(512);
				$img->save($_webPath.$album_name.$hash."_512.jpg");
					
				$img->resizeToWidth(256);
				$img->save($_webPath.$album_name.$hash."_256.jpg");
				
			}
			
			return file_get_contents($_webPath.$album_name.$hash."_256.jpg");*/

		})->regex("album_name", "[A-Za-z0-9-_%.]+")
		  ->regex("picture", "[A-Za-z0-9-_.]+")
		  ->bind("rawpicture");
		
		// @TODO: Auto-generated method stub
		$app->route("/get_thumb/{album_name}/{picture}/{size}", function(RequestManager $request, $album_name, $picture, $size) use ($app, $_meta, $_galleryDir){
			
			header( 'Content-Type: image/jpeg' );
			$_webPath = "web/_thumbs/";
			$path = urldecode("$_galleryDir$album_name/$picture");
			$hash = sha1($path);
			
			return file_get_contents($_webPath.$album_name.$hash."_". $size .".jpg");
			
		})->regex("album_name", "[A-Za-z0-9-_%.]+")
		  ->regex("picture", "[A-Za-z0-9-_.]+")
		  ->regex("size", "[0-9x._]+")
		  ->bind("getsizedpic");
		
		return $app;
		
	}
	
}