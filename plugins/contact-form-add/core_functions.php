<?php

function load_smuzform() {


	load_smuzform_classes();

}

function load_smuzform_classes() {

	load_smuzform_admin_classes();

	load_smuzform_public_classes();
	
	smuzform_public( 'core/class/class-smuzform-public-storage.php' );

	
	$storage = new SmuzForm_Public_Storage(); 
	
	$storage->init();
	
}

function load_smuzform_admin_classes() {

	/**
	Loading UI code or other admin classes can impact frontend performance.
	Admin code will not affect frontend performance.
	**/
	if ( ! is_admin() )
		return;

	if ( ! class_exists( 'phpUserAgentStringParser' ) )
		smuzform_admin( 'core/lib/phpUserAgentStringParser.php' );

	if ( ! class_exists( 'SmuzForm_Form' ) )
		smuzform_public( 'core/class/class-smuzform-form.php' );

	smuzform_admin( 'core/class/class-smuzform-error-message.php' );

	smuzform_admin( 'core/class/class-smuzform-admin.php' );
	smuzform_admin( 'core/class/class-smuzform-model.php' );
	smuzform_admin( 'core/class/class-smuzform-entry-model.php' );
	smuzform_admin( 'core/class/class-smuzform-notification-model.php' );

	smuzform_admin( 'core/class/class-smuzform-entry.php' );
	smuzform_admin( 'core/class/class-smuzform-notification-manager.php' );
	smuzform_admin( 'core/class/class-smuzform-style-manager.php' );

	$Admin = new SmuzForm_Admin();

	$Admin->createUI();


	$Model = new SmuzForm_Model();

	$Model->init();

	$Entry_Model = new SmuzForm_Entry_Model();

	$Entry_Model->init();

	$Notification_Model = new SmuzForm_Notification_Model();

	$Notification_Model->init();
}

function load_smuzform_public_classes() {

	/**
	Repeating some code is better then loading all of the public code on admin.
	Shared hosting sites don't have many resources.
	**/
	if ( is_admin() )
		return;

	smuzform_public( 'core/class/class-smuzform-public.php' );
	

	$Public = new SmuzForm_Public();

	$Public->loadClasses();

	$Public->createUI();
	
	
}
 
function smuzform_loaded() {

	do_action( 'smuzform_loaded' );

}

function smuzform_admin( $file_name, $require = true ) {

	if ( $require )
		require SMUZFORM_PLUGIN_ADMIN_DIRECTORY . $file_name;
	else
		include SMUZFORM_PLUGIN_ADMIN_DIRECTORY . $file_name;

}

function smuzform_admin_view( $file_name, $require = true ) {

	if ( $require )
		return SMUZFORM_PLUGIN_ADMIN_DIRECTORY . 'views/' . $file_name;
	else
		return SMUZFORM_PLUGIN_ADMIN_DIRECTORY . 'views/' . $file_name;

}

function smuzform_public( $file_name, $require = true ) {

	if ( $require )
		require_once SMUZFORM_PLUGIN_PUBLIC_DIRECTORY . $file_name;
	else
		include_once SMUZFORM_PLUGIN_PUBLIC_DIRECTORY . $file_name;

}

function smuzform_public_view( $file_name, $require = true ) {

	if ( $require )
		return SMUZFORM_PLUGIN_PUBLIC_DIRECTORY . 'views/' . $file_name;
	else
		return SMUZFORM_PLUGIN_PUBLIC_DIRECTORY . 'views/' . $file_name;

}

function smuzform_url( $file_name ) {

	return SMUZFORM_PLUGIN_URL . $file_name;

}

function smuzform_admin_asset( $file_name ) {

	return smuzform_url( '/admin/assets/'. $file_name );

}

function smuzform_public_asset( $file_name ) {

	return smuzform_url( '/public/assets/'. $file_name );

}

function smuzform_translate( $text ) {

	$text_domain = 'smuzform';

	return __( $text, $text_domain );

}

function smuzform_translate_e( $text ) {

	echo smuzform_translate( $text );
}

function smuzform_render_form( $form_id, $args = array() ) {

	$shortcode = sprintf( "[%s id=%d]", SMUZFORM_SHORTCODE, intval( $form_id ) );
	echo do_shortcode( $shortcode ); 

}

function smuzform_get_forms() {

	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'category'         => '',
		'category_name'    => '',
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'smuzform',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'author'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);

	$forms = get_posts( $args );

	return $forms;
}