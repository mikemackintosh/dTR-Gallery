<?php
/**
 * File for the class dtrws.php
 *
 * @category   Utility
 * @package    dTR-WebStats
 * @copyright  Copyright (c) 2013 Mike Mackintosh
 * @author     Mike Mackintosh <mike@bakeryframework.com>
 * @version    $Id:$
 */
 
/**
 * SHORT_DESCRIPTION
 *
 * LONG DESCRIPTION
 *
 * @category   Utility
 * @package    dTR-WebStats
 * @copyright  Copyright (c) 2013 Mike Mackintosh
 */

file_put_contents("/usr/local/www/mackintoshfamily/dev.log", print_r($_REQUEST, true), FILE_APPEND);
file_put_contents("/usr/local/www/mackintoshfamily/dev.log", print_r($_SERVER, true), FILE_APPEND);

die("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7");