<?php

/**
 * The 404page Plugin
 *
 * 404page allows creation of 404 error pages in WordPress admin
 *
 * @wordpress-plugin
 * Plugin Name: 404page - your smart custom 404 error page
 * Plugin URI: https://petersplugins.com/free-wordpress-plugins/404page/
 * Description: Custom 404 the easy way! Set any page as custom 404 error page. No coding needed. Works with (almost) every Theme.
 * Version: 3.3
 * Author: Peter Raschendorfer
 * Author URI: https://petersplugins.com
 * Text Domain: 404page
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load core plugin class and run the plugin
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-404page.php' );
$pp_404page = new PP_404Page( __FILE__ );


/**
 * Theme functions
 */

// this function can be used by a theme to check if there's an active custom 404 page
function pp_404_is_active() {
  global $pp_404page;
  return $pp_404page->pp_404_is_active();
}

// this function can be used by a theme to activate native support
function pp_404_set_native_support() {
  global $pp_404page;
  $pp_404page->pp_404_set_native_support();
}

// this function can be used by a theme to get the title of the custom 404 page in native support
function pp_404_get_the_title() {
  global $pp_404page;
  return $pp_404page->pp_404_get_the_title();
}

// this function can be used by a theme to print out the title of the custom 404 page in native support
function pp_404_the_title() {
  global $pp_404page;
  $pp_404page->pp_404_the_title();
}

// this function can be used by a theme to get the content of the custom 404 page in native support
function pp_404_get_the_content() {
  global $pp_404page;
  return $pp_404page->pp_404_get_the_content();
}

// this function can be used by a theme to print out the content of the custom 404 page in native support
function pp_404_the_content() {
  global $pp_404page;
  return $pp_404page->pp_404_the_content();
}

?>