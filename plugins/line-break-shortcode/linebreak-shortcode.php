<?php
/*
Plugin Name: Line Break Shortcode
Plugin URI: http://briteskies.com/
Description: Adds a [br] shortcode. Plugin modeled after the w4-internal-link-shortcode plugin. 
Version: 1.0.1
Author: Tim Reynolds
Author URI: http://briteskies.com
*/
define( 'BR_DIR', plugin_dir_path(__FILE__)) ;
define( 'BR_URL', plugin_dir_url(__FILE__)) ;
define( 'BR_BASENAME', plugin_basename( __FILE__ )) ;
define( 'BR_VERSION', '1.0.1' ) ;
define( 'BR_NAME', 'Line Break Shortcode' ) ;
define( 'BR_SLUG', strtolower(str_replace(' ', '-', BR_NAME ))) ;


class BR{
	
	function BR(){
		add_filter('the_content', array(&$this, 'add_linebreaks'));
		add_filter('the_excerpt', array(&$this, 'add_linebreaks'));
		add_filter('get_the_content', array(&$this, 'add_linebreaks'));
		add_filter('get_the_excerpt', array(&$this, 'add_linebreaks'));
		add_filter('category_description', array(&$this, 'add_linebreaks'));
	}

	function add_linebreaks($text){
		$pattern = '/\[\s*br\s*\]/sm' ;
		return preg_replace( $pattern, '<br />', $text ) ;
	}
	


}

$BR = new BR();
?>