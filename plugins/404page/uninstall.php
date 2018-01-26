<?php 
  
/**
 * The 404page Plugin Uninstall
 */
  
  
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
  die;
}


// If this is somehow accessed withou plugin uninstall is requested, abort
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ||	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
  die;
}


/**
 * Load core plugin class and run uninstall
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-404page.php' );
$pp_404page = new PP_404Page( __FILE__ );
$pp_404page->uninstall();

?>