<?php

/*
 * This file is part of the Bakery framework.
 *
 * (c) Mike Mackintosh <mike@bakeryframework.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = include_once 'boot.php';

/**
 * Register Twig
 * 
 * @Sets $app['twig'];
 *
 */
$app->register(new Bakery\Provider\TwigServiceProvider( array(
	'twig.path' => __DIR__.'/views',
)));


/**
 * Initilizes the DB Pantry
 *
 * @Sets $app['db'];
 *
 */
$app->init('db', function() use ($app){
	return new Bakery\Provider\DatabasePantryProvider( $app );
});


/**
 * Set Default Root
 * 
 */
$app->mount("", new \dTRGallery\Controllers\DefaultWebViewController());
$app->mount("/admin", new \dTRGallery\Controllers\DefaultAdminWebViewController());

/**
 * Security Definition
 *
 *
 */
$app->security(array(
		"user" => "\\dTRGallery\\Manager\\User",
		"login" => array(
				"handler" => "/login",
				"auth_type" => "DB", // LDAP, DB or LDAP+DB
				"check_path" => "/login/validate"
		),
		"logout" => array(
				"handler" => "/logout",
		),
		"admin_protected" => array(
				"/admin/.*$",
		),
		"user_protected" => array(
				"/.*$",
		),
));


/**
 * Register the URLGenerator class
 *
 * @Sets $app['url_generator']
 *
*/
$app->register(new Bakery\Provider\URLGeneratorProvider( $app ));


/**
 * Run the application
 * 
 */
$app->run();
