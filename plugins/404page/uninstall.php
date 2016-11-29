<?php 
  // 404page uninstall
  
  if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ||	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
    status_header( 404 );
    exit;
  }

  include_once plugin_dir_path( __FILE__ ) . '404page.php';
  
  $smart404page->uninstall();
?>