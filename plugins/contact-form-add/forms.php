<?php
/**
Plugin Name: Contact Form Add
Description: Most beautiful WordPress form builder plugin. A plugin you'll love creating forms.
Author: Web-Settler
Author URI: http://web-settler.com/form-builder/
Plugin URI: http://web-settler.com/form-builder/
Version: 1.6
License: GPL V2+
**/

require plugin_dir_path( __FILE__ ) . 'config.php';

require plugin_dir_path( __FILE__ ) . 'core_functions.php';

/**
Store the first installed version of the plugin in database.
The value will not change with plugin updates.

To get up-to-date version number use constant SMUZFORM_PLUGIN_VERSION
**/
if ( is_admin() )
	add_option( 'smuzform_plugin_version', SMUZFORM_PLUGIN_VERSION );


load_smuzform();


register_activation_hook( __FILE__, 'smuzform_create_db_tables'  );
function smuzform_create_db_tables() {
	
	global $wpdb;

	$entry_table_name = $wpdb->prefix . SMUZFORM_ENTRY_DATABASE_TABLE_NAME;

	$entry_data_table_name = $wpdb->prefix . SMUZFORM_ENTRY_DATA_DATABASE_TABLE_NAME;

	$charset_collate = $wpdb->get_charset_collate();
	
	$sql = "CREATE TABLE $entry_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		form_id mediumint(9) NOT NULL,
		status varchar(100),
		user_ip varchar(100),
		user_agent varchar(200),
		created_at TIMESTAMP,
		updated_at DATETIME,
		UNIQUE KEY (id)
	); $charset_collate";

	$sql2 = "CREATE TABLE $entry_data_table_name (
		id INT NOT NULL AUTO_INCREMENT,
		form_id mediumint(9) NOT NULL,
		entry_id mediumint(9) NOT NULL,
		field_id varchar(100) NOT NULL,
		created_at TIMESTAMP,
		value TEXT,
		UNIQUE KEY (id)
	); $charset_collate";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql );

    dbDelta( $sql2 );

    update_option( 'smuzform_db_version', '1.0' );
}

/**
Executes WordPress action hooks attached to smuzform_loaded

->After this plugin is loaded.

Hook to this function using the plugins_loaded hook.
**/
smuzform_loaded();