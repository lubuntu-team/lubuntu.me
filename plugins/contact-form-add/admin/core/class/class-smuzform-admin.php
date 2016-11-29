<?php

class SmuzForm_Admin {

	/**
	Create the forms plugin admin UI
	**/
	public function createUI() {

		add_action( 'admin_init', array( $this, 'addAssetsFormBuilder' )  );

		add_action( 'admin_init', array( $this, 'addAssetsEntryManager' )  );

		add_action( 'admin_init', array( $this, 'addAssetsNotificationManager' )  );

		add_action( 'admin_init', array( $this, 'addAssetsStyleManager' ) );

		add_action( 'admin_menu', array( $this, 'createMainMenu' ) );

		add_action( 'admin_menu', array( $this, 'createMenu' ) );

		add_action( 'admin_menu', array( $this, 'createEntryMenu' ) );

		add_action( 'admin_menu', array( $this, 'createNotificationMenu' ) );

		add_action( 'admin_menu', array( $this, 'createStyleMenu' ) );

		add_action( 'init', array( $this, 'registerFormPost' ) );

		add_filter( 'post_row_actions',
			array( $this, 'remove_post_row_actions' ), 10, 2 );

		add_action( 'admin_init', array( $this, 'setup_upload_dir' ) );

	}

	/**
	New Menu item with Title $menu_title is added on wp-admin dashboard
	**/
	function createMainMenu() {

		$page_title = 'Form Builder';

		$menu_title = 'Contact Form';

		$capability = 'manage_options';

		$menu_slug = 'smuz-forms-main';

		$screen_id = add_menu_page( $page_title, $menu_title, $capability, $menu_slug,
			array( $this, 'formMainSettingsPage' ) );

		add_submenu_page( $menu_slug, 'Add-Ons', 'Add-Ons', $capability, 'smuzform-addons' , array( $this, 'createAddonsPage' ) );

	}

	/**
	New Menu item with Title $menu_title is added on wp-admin dashboard
	**/
	function createMenu() {

		$page_title = 'Form Builder';

		$menu_title = 'Form Builder';

		$capability = 'manage_options';

		$menu_slug = 'smuz-forms';

		$screen_id = add_submenu_page( null, $page_title, $menu_title, $capability, $menu_slug,
			array( $this, 'settingsPage' ) );

	}

	function createAddonsPage() {

		smuzform_admin( 'views/add-ons/main.php' );

	}

	/**
	New Sub Menu item with Title $menu_title is added on wp-admin dashboard
	**/
	function createEntryMenu() {

		$page_title = 'Entry Manager';

		$menu_title = 'Entry Manager';

		$capability = 'manage_options';

		$menu_slug = 'smuz-forms-entry';

		$parent_slug = 'smuz-forms';

		$screen_id = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, array( $this, 'entrySettingsPage' ) );

	}

	/**
	New Sub Menu item with Title $menu_title is added on wp-admin dashboard
	**/
	function createNotificationMenu() {

		$page_title = 'Form Notifications';

		$menu_title = 'Notifications';

		$capability = 'manage_options';

		$menu_slug = 'smuz-forms-notifications';

		$parent_slug = 'smuz-forms';

		$screen_id = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, array( $this, 'notificationSettingsPage' ) );

	}

	/**
	New Menu item with Title $menu_title is added on wp-admin dashboard
	**/
	function createStyleMenu() {

		$page_title = 'Form Style Manager';

		$menu_title = 'Style';

		$capability = 'manage_options';

		$menu_slug = 'smuz-forms-style';

		$parent_slug = 'smuz-forms';

		$screen_id = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, array( $this, 'styleSettingsPage' ) );

	}

	/**
	Settings page for menu $menu_slug above
	**/
	function formMainSettingsPage() {

		if ( ! class_exists( 'Smuz_Form' ) )
			smuzform_public( 'core/class/class-smuzform-form.php' );

		if ( isset( $_GET['action'] ) ) {

			if ( $_GET['action'] === 'delete_form' ) {

				wp_delete_post( intval( $_GET['post_id'] ), true );

			}
		}

		smuzform_admin( 'views/forms-main.php' );

	}


	/**
	Settings page for menu $menu_slug above
	**/
	function settingsPage() {

		smuzform_admin( 'views/settings-page.php' );

	}

	/**
	Settings page for entry menu.
	**/
	function entrySettingsPage() {

		if ( ! isset( $_GET['form_id'] ) )
			wp_die( 'Use the form builder page to get here.' );

		$entryManager = new SmuzForm_Entry( intval( $_GET['form_id'] ) );

		include smuzform_admin_view( '/entry/settings-page.php' );

	}

	/**
	Settings page for notification menu.
	**/
	function notificationSettingsPage() {

		if ( ! isset( $_GET['form_id'] ) )
			wp_die( 'Use the form builder page to get here.' );

		$notificationManager = new SmuzForm_Notification_Manager( intval( $_GET['form_id'] ) );

		include smuzform_admin_view( '/notification/settings-page.php' );

	}

	/**
	Settings page for style menu.
	**/
	function styleSettingsPage() {

		if ( ! isset( $_GET['form_id'] ) )
			wp_die( 'Use the form builder page to get here.' );

		$styleManager = new SmuzForm_Style_Manager( intval( $_GET['form_id'] ) );

		include smuzform_admin_view( '/style/settings-page.php' );

	}

	/**
	Add scripts, styles on settings page.
	**/
	function addAssetsFormBuilder() {

		if ( ! $this->isSettingsPage( array( 'smuz-forms' ) ) )
			return false;

		//CSS

		$bootstrap_url = smuzform_admin_asset( 'css/bootstrap.min.css' );

		$style_url = smuzform_admin_asset( 'css/style.css' );

		wp_enqueue_style( 'smuzform-bootstrap', $bootstrap_url  );

		wp_enqueue_style( 'smuzform-main', $style_url  );

		//JS

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui' );

		wp_enqueue_script( 'jquery-ui-core' );

		wp_enqueue_script( 'jquery-ui-tabs' );

		wp_enqueue_script( 'jquery-ui-draggable' );

		wp_enqueue_script( 'jquery-ui-resizable' );

		wp_enqueue_script( 'jquery-ui-droppable' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		

		$bootstrap_js_url = smuzform_admin_asset( 'js/bootstrap.min.js' );

		$underscore_url = smuzform_admin_asset( 'js/underscore-min.js' );

		$backbone_url = smuzform_admin_asset( 'js/backbone.min.js' );

		$script_url = smuzform_admin_asset( 'js/script.js' );


		wp_enqueue_script( 'smuzform-underscore', $underscore_url, array(), null, true );

		wp_enqueue_script( 'smuzform-backbone', $backbone_url, array(), null, true );

		wp_enqueue_script( 'smuzform-bootstrap', $bootstrap_js_url, array(), null, true );

		wp_enqueue_script( 'smuzform-script', $script_url, array(), null, true );

		$nonce_form_model = wp_create_nonce( 'smuzform_form_model' );

		$js = array(
			'admin_url' => admin_url(),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'form_model_nonce' => $nonce_form_model
		);		 

		wp_localize_script( 'smuzform-script', 'smuzform', $js );

	}

	/**
	Add scripts, styles on entry settings page.
	**/
	function addAssetsEntryManager() {

		if ( ! $this->isSettingsPage( array( 'smuz-forms-entry' ) ) )
			return false;

		//CSS

		$bootstrap_url = smuzform_admin_asset( 'css/bootstrap.min.css' );

		$style_url = smuzform_admin_asset( 'css/style.css' );

		$entry_style_url = smuzform_admin_asset( 'css/entry/entry-manager.css' );

		$datatable_style_url = smuzform_admin_asset( 'css/entry/datatables.min.css' );

		wp_enqueue_style( 'smuzform-bootstrap', $bootstrap_url  );

		wp_enqueue_style( 'smuzform-main', $style_url  );

		wp_enqueue_style( 'smuzform-entry-main', $entry_style_url  );

		wp_enqueue_style( 'smuzform-datatables', $datatable_style_url  );

		//JS

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui' );

		wp_enqueue_script( 'jquery-ui-core' );

		wp_enqueue_script( 'jquery-ui-tabs' );

		wp_enqueue_script( 'jquery-ui-draggable' );

		wp_enqueue_script( 'jquery-ui-resizable' );

		wp_enqueue_script( 'jquery-ui-droppable' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		

		$bootstrap_js_url = smuzform_admin_asset( 'js/bootstrap.min.js' );

		$underscore_url = smuzform_admin_asset( 'js/underscore-min.js' );

		$backbone_url = smuzform_admin_asset( 'js/backbone.min.js' );

		$datatable_script_url = smuzform_admin_asset( 'js/entry/datatables.min.js' );

		$script_url = smuzform_admin_asset( 'js/entry/entry-manager.js' );


		wp_enqueue_script( 'smuzform-underscore', $underscore_url, array(), null, true );

		wp_enqueue_script( 'smuzform-backbone', $backbone_url, array(), null, true );

		wp_enqueue_script( 'smuzform-bootstrap', $bootstrap_js_url, array(), null, true );

		wp_enqueue_script( 'smuzform-entry-script', $script_url, array(), null, true );

		wp_enqueue_script( 'smuzform-datatables-script', $datatable_script_url, array(), null, true );

		$nonce_entry_model = wp_create_nonce( 'smuzform_entry_model' );

		$js_form_id = null;

		if ( isset( $_GET['form_id'] ) )
			$js_form_id = intval( $_GET['form_id'] );

		$js = array(
			'admin_url' => admin_url(),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'entry_model_nonce' => $nonce_entry_model,
			'form_id' => $js_form_id
		);		 

		wp_localize_script( 'smuzform-entry-script', 'smuzform', $js );


	}

	/**
	Add scripts, styles on notification settings page.
	**/
	function addAssetsNotificationManager() {

		if ( ! $this->isSettingsPage( array( 'smuz-forms-notifications' ) ) )
			return false;

		//CSS

		$bootstrap_url = smuzform_admin_asset( 'css/bootstrap.min.css' );

		$style_url = smuzform_admin_asset( 'css/style.css' );

		$notification_style_url = smuzform_admin_asset( 'css/notification/notification-manager.css' );

		wp_enqueue_style( 'smuzform-bootstrap', $bootstrap_url  );

		wp_enqueue_style( 'smuzform-main', $style_url  );

		wp_enqueue_style( 'smuzform-entry-main', $notification_style_url  );

		//JS

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui' );

		wp_enqueue_script( 'jquery-ui-core' );

		wp_enqueue_script( 'jquery-ui-tabs' );

		wp_enqueue_script( 'jquery-ui-draggable' );

		wp_enqueue_script( 'jquery-ui-resizable' );

		wp_enqueue_script( 'jquery-ui-droppable' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		

		$bootstrap_js_url = smuzform_admin_asset( 'js/bootstrap.min.js' );

		$underscore_url = smuzform_admin_asset( 'js/underscore-min.js' );

		$backbone_url = smuzform_admin_asset( 'js/backbone.min.js' );


		$script_url = smuzform_admin_asset( 'js/notification/notification-manager.js' );


		wp_enqueue_script( 'smuzform-underscore', $underscore_url, array(), null, true );

		wp_enqueue_script( 'smuzform-backbone', $backbone_url, array(), null, true );

		wp_enqueue_script( 'smuzform-bootstrap', $bootstrap_js_url, array(), null, true );

		wp_enqueue_script( 'smuzform-notification-script', $script_url, array(), null, true );

		$nonce_notification_model = wp_create_nonce( 'smuzform_notification_model' );

		$js_form_id = null;

		if ( isset( $_GET['form_id'] ) )
			$js_form_id = intval( $_GET['form_id'] );

		$js = array(
			'admin_url' => admin_url(),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'notification_model_nonce' => $nonce_notification_model,
			'form_id' => $js_form_id
		);	 

		wp_localize_script( 'smuzform-notification-script', 'smuzform', $js );


	}

	/**
	Add scripts, styles on form style manager settings page.
	**/
	function addAssetsStyleManager() {

		if ( ! $this->isSettingsPage( array( 'smuz-forms-style' ) ) )
			return false;

		//CSS

		$bootstrap_url = smuzform_admin_asset( 'css/bootstrap.min.css' );

		$style_url = smuzform_admin_asset( 'css/style.css' );

		//style manager
		$_style_url = smuzform_admin_asset( 'css/style/style-manager.css' );

		wp_enqueue_style( 'smuzform-bootstrap', $bootstrap_url  );

		wp_enqueue_style( 'smuzform-main', $style_url  );

		wp_enqueue_style( 'smuzform-style-main', $_style_url  );

		wp_enqueue_style( 'wp-color-picker' );

		//JS

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui' );

		wp_enqueue_script( 'jquery-ui-core' );

		wp_enqueue_script( 'jquery-ui-tabs' );

		wp_enqueue_script( 'jquery-ui-draggable' );

		wp_enqueue_script( 'jquery-ui-resizable' );

		wp_enqueue_script( 'jquery-ui-droppable' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		

		$bootstrap_js_url = smuzform_admin_asset( 'js/bootstrap.min.js' );

		$underscore_url = smuzform_admin_asset( 'js/underscore-min.js' );

		$backbone_url = smuzform_admin_asset( 'js/backbone.min.js' );


		$script_url = smuzform_admin_asset( 'js/style/style-manager.js' );


		wp_enqueue_script( 'smuzform-underscore', $underscore_url, array(), null, true );

		wp_enqueue_script( 'smuzform-backbone', $backbone_url, array(), null, true );

		wp_enqueue_script( 'smuzform-bootstrap', $bootstrap_js_url, array(), null, true );

		wp_enqueue_script( 'smuzform-style-script', $script_url, array( 'wp-color-picker' ), null, true );

		$nonce_style_model = wp_create_nonce( 'smuzform_style_model' );

		$js_form_id = null;

		if ( isset( $_GET['form_id'] ) )
			$js_form_id = intval( $_GET['form_id'] );

		$js = array(
			'admin_url' => admin_url(),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'style_model_nonce' => $nonce_style_model,
			'form_model_nonce' => wp_create_nonce( 'smuzform_form_model' ),
			'form_id' => $js_form_id
		);	 

		wp_localize_script( 'smuzform-style-script', 'smuzform', $js );


	}

	/**
	Checks if the admin page is part of forms plugin.
	**/
	function isSettingsPage( $pages = null ) {

		if ( ! is_admin() )
			return false;

		if ( ! isset( $_GET['page'] ) )
			return false;

		if ( ! $pages )
			$pages = array( 'smuz-forms', 'smuz-forms-entry' );

		if ( ! in_array( $_GET['page'], $pages ) )
			return false;


		return true;

	}

	function registerFormPost() {

		$post_type = 'smuzform';

		$args = array(
				'label' => 'Forms',
				'show_ui' => false
			);

		register_post_type( $post_type, $args );

	}

	function remove_post_row_actions( $actions, $post ) {

		if ( get_post_type() !== 'smuzform' )
			return $actions;

		unset( $actions['view'] );
		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['pgcache_purge'] );

		$edit_url = admin_url( 'admin.php?page=smuz-forms&form_id='.$post->ID );

		$actions['edit'] = '<a href="'.$edit_url.'">Edit</a>';

		return $actions;

	}

	function setup_upload_dir() {

		if ( get_option( 'smuzform_upload_dir' ) )
			return false;

		$uniq = SMUZFORM_UPLOAD_DIR_NAME . wp_generate_password(25, false, false);

		$directory = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $uniq;

		$upload_dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads';

		$empty_php = $directory . DIRECTORY_SEPARATOR . 'index.php';

		if ( ! file_exists( $upload_dir ) )
			@mkdir( $upload_dir );

		@mkdir( $directory );

		if ( ! file_exists( $empty_php ) )
			@fopen( $empty_php , 'w' );

		add_option( 'smuzform_upload_dir', $directory );

	}

	function __construct() {  }
	private function __clone() { }
	private function __wakeup() { }

}