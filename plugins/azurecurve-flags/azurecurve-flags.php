<?php
/*
Plugin Name: azurecurve Flags
Plugin URI: http://wordpress.azurecurve.co.uk/plugins/flagse

Description: Allows a 16x16 flag to be displayed in a post of page using a shortcode.
Version: 1.1.0

Author: Ian Grieve
Author URI: http://wordpress.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

function azc_f_flag($atts, $content = null) {
	if (empty($atts)){
		$flag = 'none';
	}else{
		$attribs = implode('',$atts);
		$flag = str_replace("'", '', str_replace('"', '', substr ( $attribs, 1)));
	}
	return "<img class='azc_flags' src='".plugin_dir_url(__FILE__)."images/$flag.png' />";
}
add_shortcode( 'flag', 'azc_f_flag' );
add_shortcode( 'FLAGS', 'azc_f_flag' );

function azc_f_load_css(){
	wp_enqueue_style( 'azurecurve-flags', plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'azc_f_load_css');

?>