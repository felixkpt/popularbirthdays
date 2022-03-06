<?php
/**
 *Plugin Name: Popular Birthdays Plugin
 *Description: The Popular People Birthdays Database plugin
 * Version: 2.6.3
 *Author: Felix Kiptoo Biwott
 * Author URI: https://sharasolutions.com
 * Plugin URI: https://sharasolutions.com
 **/

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

remove_action('template_redirect', 'redirect_canonical');

$file = __FILE__;

global $wpdb;
global $post;

require_once 'my-custom-functions.php';
include 'popular-birthdays-config.php';
include_once 'popular-birthdays-admin.php';

if (!preg_match("#wp-admin#", $_SERVER['REQUEST_URI']) || @$_GET['cron'] == 'true'){
    include_once 'popular-birthdays-functions.php';
    include_once 'popularity.php';
    include 'check-customs.php';

}

?>