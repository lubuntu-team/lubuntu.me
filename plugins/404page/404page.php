<?php
/*
Plugin Name: 404page - your smart custom 404 error page
Plugin URI: http://petersplugins.com/free-wordpress-plugins/404page/
Description: Custom 404 the easy way! Set any page as custom 404 error page. No coding needed. Works with (almost) every Theme.
Version: 2.3
Author: Peter's Plugins, smartware.cc
Author URI: http://petersplugins.com
Text Domain: 404page
License: GPL2+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/


if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PP_404', true );

class Smart404Page {
  public $plugin_name;
  public $plugin_slug;
  public $version;
  private $wp_url;
  private $my_url;
  private $dc_url;
  public $settings;
  private $template;
  private $postid;
  
	public function __construct() {
		$this->plugin_name = '404page';
    $this->plugin_slug = '404page';
		$this->version = '2.3';
    $this->get_settings();
    $this->init();
	} 
  
  // get all settings
  private function get_settings() {
    $this->settings = array();
    $this->settings['404page_page_id'] = $this->get_404page_id();
    $this->settings['404page_hide'] = $this->get_404page_hide();
    $this->settings['404page_fire_error'] = $this->get_404page_fire_error();
    // $this->settings['404page_method'] = $this->get_404page_method(); --> moved to set_mode in v 2.2 because this may be too early here
    $this->settings['404page_native'] = false;
  }
 
  // do plugin init
  private function init() {
    
    // as of v 2.2 always call set_mode
    add_action( 'init', array( $this, 'set_mode' ) );
    
    if ( !is_admin() ) {
      
      add_action( 'pre_get_posts', array ( $this, 'exclude_404page' ) );
      add_filter( 'get_pages', array ( $this, 'remove_404page_from_array' ), 10, 2 );
      
    } else {
      
      add_action( 'admin_init', array( $this, 'admin_init' ) );
      add_action( 'admin_menu', array( $this, 'admin_menu' ) );
      add_action( 'admin_head', array( $this, 'admin_css' ) );
      add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_settings_link' ) ); 
      
      if ( $this->settings['404page_hide'] and $this->settings['404page_page_id'] > 0 ) {
        add_action( 'pre_get_posts' ,array ( $this, 'exclude_404page' ) );
      }
      
    }
    
  }
  
  // init filters 
  function set_mode() {
    
    $this->settings['404page_method'] = $this->get_404page_method();
    
    if ( !is_admin() ) {
    
      if ( defined( 'CUSTOMIZR_VER' ) ) {
        
        // Customizr Compatibility Mode 
       
        add_filter( 'tc_404_header_content', array( $this, 'show404title_customizr_mode' ), 999 );
        add_filter( 'tc_404_content', array( $this, 'show404_customizr_mode' ), 999 );
        add_filter( 'tc_404_selectors', array( $this, 'show404articleselectors_customizr_mode' ), 999 );
        
      } elseif ( $this->settings['404page_method'] != 'STD' ) {
          
        // Compatibility Mode
        add_filter( 'posts_results', array( $this, 'show404_compatiblity_mode' ), 999 );
          
      } else {
          
        // Standard Mode
        add_filter( '404_template', array( $this, 'show404_standard_mode' ), 999 );
        if ( $this->settings['404page_fire_error'] ) {
          add_action( 'template_redirect', array( $this, 'do_404_header_standard_mode' ) );
        }
          
      }
    
    }
    
  }
  
  // show 404 page - Standard Mode
  function show404_standard_mode( $template ) {
    
    global $wp_query;
    $pageid = $this->settings['404page_page_id'];
    if ( $pageid > 0 ) {
      if ( ! $this->settings['404page_native'] ) {
        $wp_query = null;
        $wp_query = new WP_Query();
        $wp_query->query( 'page_id=' . $pageid );
        $wp_query->the_post();
        $template = get_page_template();
        rewind_posts();
        add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
      }
      $this->do_404page_action();
    }
    return $template;
    
  }
  
  // show 404 page - Compatibility Mode
  function show404_compatiblity_mode( $posts ) {
    
    // remove the filter so we handle only the first query - no custom queries
    remove_filter( 'posts_results', array( $this, 'show404_compatiblity_mode' ), 999 ); 
    
    $pageid = $this->settings['404page_page_id'];
    if ( $pageid > 0 && ! $this->settings['404page_native'] ) {
      if ( empty( $posts ) && is_main_query() && !is_robots() && !is_home() && !is_feed() && !is_search() && !is_archive() && ( !defined('DOING_AJAX') || !DOING_AJAX ) ) {
        // we need to get the 404 page
        
        $pageid = $this->get_page_id( $pageid );
        
        // as of v2.1 we do not alter the posts argument here because this does not work with SiteOrigin's Page Builder Plugin, template_include filter introduced
        $this->postid = $pageid;
        $this->template = get_page_template_slug( $pageid );
        if ( $this->template == '' ) {
          $this->template = get_page_template();
        }
        add_action( 'wp', array( $this, 'do_404_header' ) );
        add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
        add_filter( 'template_include', array( $this, 'change_404_template' ), 999 );
        
        $posts[] = get_post( $pageid );
        
        $this->do_404page_action();
        
      } elseif ( 1 == count( $posts ) && 'page' == $posts[0]->post_type ) {
        
        // Do a 404 if the 404 page is opened directly
        if ( $this->settings['404page_fire_error'] ) {
          $curpageid = $posts[0]->ID;
          
          if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
           
           // WPML is active - get the post ID of the default language
            global $sitepress;
            $curpageid = apply_filters( 'wpml_object_id', $curpageid, 'page', $sitepress->get_default_language() );
            $pageid = apply_filters( 'wpml_object_id', $pageid, 'page', $sitepress->get_default_language() );
            
          } elseif ( defined( 'POLYLANG_VERSION' ) ) {
            
            // Polylang is active - get the post ID of the default language
            $curpageid = pll_get_post( $curpageid, pll_default_language() );
            $pageid = pll_get_post( $pageid, pll_default_language() );
          
          }
          
          if ( $pageid == $curpageid ) {
            add_action( 'wp', array( $this, 'do_404_header' ) );
            add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
            $this->do_404page_action();
          }
        }
        
      }
    } elseif ( $pageid > 0 && $this->settings['404page_native'] ) {
      $this->do_404page_action();
    }
    return $posts;
  }
  
  // this function overrides the page template in compatibilty mode
  function change_404_template( $template ) {
    // we have to check if the template file is there because if the theme was changed maybe a wrong template is stored in the database
    $new_template = locate_template( array( $this->template ) );
    if ( '' != $new_template ) {
			return $new_template ;
		}
    return $template;
  }
  
  // send a 404 HTTP header - Standard Mode
  function do_404_header_standard_mode() {
    if ( is_page() && get_the_ID() == $this->settings['404page_page_id'] && !is_404() ) {
      status_header( 404 );
      nocache_headers();
      $this->do_404page_action();
    }
  }
  
  // send a 404 HTTP header - Compatibility Mode
  function do_404_header() {
    // remove the action so we handle only the first query - no custom queries
    remove_action( 'wp', array( $this, 'do_404_header' ) );
    status_header( 404 );
    nocache_headers();
  }
  
  // adds the error404 class to the body classes  
  function add_404_body_class( $classes ) {
    $classes[] = 'error404';
    return $classes;
  }
  
  // show title - Customizr Compatibility Mode
  function show404title_customizr_mode( $title ) {
    if ( ! $this->settings['404page_native'] ) {
      return '<h1 class="entry-title">' . get_the_title( $this->settings['404page_page_id'] ) . '</h1>';
    } else {
      return $title;
    }
  }
  
  // show content - Customizr Compatibility Mode
  function show404_customizr_mode( $content ) {
    if ( ! $this->settings['404page_native'] ) {
      return '<div class="entry-content">' . apply_filters( 'the_content', get_post_field( 'post_content', $this->settings['404page_page_id'] ) ) . '</div>';
    } else {
      return $content;
    }
    $this->do_404page_action();
  }
  
  // change article selectors - Customizr Compatibility Mode
  function show404articleselectors_customizr_mode( $selectors ) {
    if ( ! $this->settings['404page_native'] ) {
      return 'id="post-' . $this->settings['404page_page_id'] . '" ' . 'class="' . join( ' ', get_post_class( 'row-fluid', $this->settings['404page_page_id'] ) ) . '"';
    } else {
      return $selectors;
    }
  }
  
  // init the admin section
  function admin_init() {
    $this->wp_url = 'https://wordpress.org/plugins/' . $this->plugin_slug;
    $this->my_url = 'http://petersplugins.com/free-wordpress-plugins/' . $this->plugin_slug;
    $this->dc_url = 'http://petersplugins.com/docs/' . $this->plugin_slug;
    load_plugin_textdomain( '404page' );
    add_settings_section( '404page-settings', null, null, '404page_settings_section' );
    register_setting( '404page_settings', '404page_page_id' );
    register_setting( '404page_settings', '404page_hide' );
    register_setting( '404page_settings', '404page_method' );
    register_setting( '404page_settings', '404page_fire_error' );
    add_settings_field( '404page_settings_404page', __( 'Page to be displayed as 404 page', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_select_page"></a>' , array( $this, 'admin_404page' ), '404page_settings_section', '404page-settings', array( 'label_for' => '404page_page_id' ) );
    add_settings_field( '404page_settings_hide', __( 'Hide 404 page', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_hide_page"></a>' , array( $this, 'admin_hide' ), '404page_settings_section', '404page-settings', array( 'label_for' => '404page_hide' ) );
    add_settings_field( '404page_settings_fire', __( 'Fire 404 error', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_fire_404"></a>' , array( $this, 'admin_fire404' ), '404page_settings_section', '404page-settings', array( 'label_for' => '404page_fire_error' ) );
    add_settings_field( '404page_settings_method', __( 'Operating Method', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_operating_method"></a>' , array( $this, 'admin_method' ), '404page_settings_section', '404page-settings', array( 'label_for' => '404page_method' ) );
  }
  
  // add css
  function admin_css() {
    echo '<style type="text/css">#select404page {width: 100%; }';
    if ( $this->settings['404page_page_id'] > 0 ) {
      echo ' #the-list #post-' . $this->settings['404page_page_id'] . ' .column-title {min-height: 32px; background-position: left top; background-repeat: no-repeat; background-image: url(' . plugins_url( 'pluginicon.png', __FILE__ ) . '); padding-left: 40px;}';
    }
    echo '</style>';
  }
  
  // handle the settings field page id
  function admin_404page() {
    if ( $this->settings['404page_page_id'] < 0 ) {
      echo '<div class="error form-invalid" style="line-height: 3em">' . __( 'The page you have selected as 404 page does not exist anymore. Please choose another page.', '404page' ) . '</div>';
    }
    wp_dropdown_pages( array( 'name' => '404page_page_id', 'id' => 'select404page', 'echo' => 1, 'show_option_none' => __( '&mdash; NONE (WP default 404 page) &mdash;', '404page'), 'option_none_value' => '0', 'selected' => $this->settings['404page_page_id'] ) );
    echo '<div id="404page_edit_link" style="display: none">' . get_edit_post_link( $this->get_404page_id() )  . '</div>';
    echo '<div id="404page_test_link" style="display: none">' . get_site_url() . '/' . md5( rand() ) . '/' . md5( rand() ) . '/' . md5( rand() ) . '</div>';
    echo '<div id="404page_current_value" style="display: none">' . $this->get_404page_id() . '</div>';
    echo '<p class="submit"><input type="button" name="edit_404_page" id="edit_404_page" class="button secondary" value="' . __( 'Edit Page', '404page' ) . '" />&nbsp;<input type="button" name="test_404_page" id="test_404_page" class="button secondary" value="' . __( 'Test 404 error', '404page' ) . '" /></p>';
  }
  
  // handle the settings field hide
  function admin_hide() {
    echo '<p><input type="checkbox" id="404page_hide" name="404page_hide" value="1"' . checked( true, $this->settings['404page_hide'], false ) . '/>';
    echo '<label for="404page_hide">' . __( 'Hide the selected page from the Pages list', '404page' ) . '</label></p>';
    echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'For Administrators the page is always visible.', '404page' ) . '</p>';
  }
  
  // handle the settings field fire 404 error
  function admin_fire404() {
    echo '<p><input type="checkbox" id="404page_fire_error" name="404page_fire_error" value="1"' . checked( true, $this->settings['404page_fire_error'], false ) . '/>';
    echo '<label for="404page_fire_error">' . __( 'Send an 404 error if the page is accessed directly by its URL', '404page' ) . '</label></p>';
    echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'Uncheck this if you want the selected page to be accessible.', '404page' ) . '</p>';
    if ( function_exists( 'wpsupercache_activate' ) ) {
      echo '<p><span class="dashicons dashicons-warning"></span>&nbsp;<strong>' . __( 'WP Super Cache Plugin detected', '404page' ) . '</strong>. ' . __ ( 'If the page you selected as 404 error page is in cache, always a HTTP code 200 is sent. To avoid this and send a HTTP code 404 you have to exlcude this page from caching', '404page' ) . ' (<a href="' . admin_url( 'options-general.php?page=wpsupercache&tab=settings#rejecturi' ) . '">' . __( 'Click here', '404page' ) . '</a>).<br />(<a href="' . $this->dc_url . '/#wp_super_cache">' . __( 'Read more', '404page' ) . '</a>)</p>';
    }
  }
  
  // handle the settings field method
  function admin_method() {

    if ( $this->settings['404page_native'] ) {
      
      echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'This setting is not available because the Theme you are using natively supports the 404page plugin.', '404page' ) . ' (<a href="' . $this->dc_url . '/#native_mode">' . __( 'Read more', '404page' ) . '</a>)</p>';
    
    } elseif ( defined( 'CUSTOMIZR_VER' ) ) {
    
      echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'This setting is not availbe because the 404page Plugin works in Customizr Compatibility Mode.', '404page' ) . ' (<a href="' . $this->dc_url . '/#special_modes">' . __( 'Read more', '404page' ) . '</a>)</p>';
    
    } elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
    
      echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'This setting is not availbe because the 404page Plugin works in WPML Mode.', '404page' ) . ' (<a href="' . $this->dc_url . '/#special_modes">' . __( 'Read more', '404page' ) . '</a>)</p>';
      
    } elseif ( defined( 'POLYLANG_VERSION' ) ) {
    
      echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'This setting is not availbe because the 404page Plugin works in Polylang Mode.', '404page' ) . ' (<a href="' . $this->dc_url . '/#special_modes">' . __( 'Read more', '404page' ) . '</a>)</p>';
      
    } else {
      
      echo '<p><input type="radio" id="404page_settings_method_standard" name="404page_method" value="STD"' . checked( 'STD', $this->settings['404page_method'], false ) . ' />';
      echo '<label for="404page_settings_method_standard">' . __( 'Standard Mode', '404page' ) . '</label></p>';
     
      echo '<p><input type="radio" id="404page_settings_method_compatibility" name="404page_method" value="CMP"' . checked( 'CMP', $this->settings['404page_method'], false ) . '/>';
      echo '<label for="404page_settings_method_compatibility">' . __( 'Compatibility Mode', '404page' ) . '</label></p>';
      
      echo '<p><span class="dashicons dashicons-info"></span>&nbsp;' . __( 'Standard Mode uses the WordPress Template System and should work in most cases. If the 404page plugin does not work properly, probably you are using a theme or plugin that modifies the WordPress Template System. In this case the Compatibility Mode maybe can fix the problem, although it cannot be guaranteed that every possible configuration can be handled by Compatibility Mode. Standard Mode is the recommended method, only switch to Compatibility Mode if you have any problems.', '404page' ) . '</p>';
      
    }

  }
  
  // this function hides the selected page from the list of pages 
  function exclude_404page( $query ) {
    if ( $this->settings['404page_page_id'] > 0 ) {
      global $pagenow;
      
      $post_type = $query->get( 'post_type' );

      // as of v 2.3 we check the post_type on front end
      if( ( is_admin() && ( 'edit.php' == $pagenow && !current_user_can( 'create_users' ) ) ) || ( ! is_admin() && ( !empty( $post_type) && ( ('page' === $post_type || 'any' === $post_type) || ( is_array( $post_type ) && in_array( 'page', $post_type ) ) ) )) ) {
        $pageid = $this->settings['404page_page_id'];
        
        if ( ! is_admin() ) {
          $pageid = $this->get_page_id( $pageid );
        }
        
        // as of v 2.3 we add the ID of the 404 page to post__not_in
        // using just $query->set() overrides existing settings but not adds a new setting
        $query->set( 'post__not_in', array_merge( (array)$query->get( 'post__not_in', array() ), array( $pageid ) ) );
        
      }
    }
  }
  
  // this function removes the 404 page from get_pages result array
  function remove_404page_from_array( $pages, $r ) {
    if ( $this->settings['404page_page_id'] > 0 ) {
      $pageid = $this->get_page_id( $this->settings['404page_page_id'] );
      for ( $i = 0; $i < sizeof( $pages ); $i++ ) {			
        if ( $pages[$i]->ID == $pageid ) {
          unset( $pages[$i] );
          break;
        }
      }
    }
    return array_values( $pages );
  }
  
  // adds the options page to admin menu
  function admin_menu() {
    $page_handle = add_theme_page ( __( '404 Error Page', "404page" ), __( '404 Error Page', '404page' ), 'manage_options', '404pagesettings', array( $this, 'admin_page' ) );
    add_action( 'admin_print_scripts', array( $this, 'admin_js' ) );
  }
  
  // adds javascript to the 404page settings page
  function admin_js() {
    wp_enqueue_script( '404pagejs', plugins_url( '/404page.js', __FILE__ ), 'jquery', $this->version, true );
  }
 
  // creates the options page
  function admin_page() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    ?>    
    <div class="wrap">
      <?php screen_icon(); ?>
      <h2 style="min-height: 32px; line-height: 32px; padding-left: 40px; background-image: url(<?php echo plugins_url( 'pluginicon.png', __FILE__ ); ?>); background-repeat: no-repeat; background-position: left center"><a href="<?php echo $this->my_url; ?>">404page</a> <?php echo __( 'Settings', '404page' ); ?></h2>
      <?php settings_errors(); ?>
      <hr />
      <p>Plugin Version: <?php echo $this->version; ?> <a class="dashicons dashicons-editor-help" href="<?php echo $this->wp_url; ?>/changelog/"></a></p>
      <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
          <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
              <form method="post" action="options.php">
                <div class="postbox">
                  <div class="inside">
                    <?php 
                      settings_fields( '404page_settings' );
                      do_settings_sections( '404page_settings_section' ); 
                      submit_button(); 
                    ?>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <?php { $this->show_meta_boxes(); } ?>
        </div>
        <br class="clear">
      </div>    
    </div>
    <?php
  }
  
  // returns the id of the 404 page if one is defined, returns 0 if none is defined, returns -1 if the defined page id does not exist
  private function get_404page_id() {  
    $pageid = get_option( '404page_page_id', 0 );
    if ( $pageid != 0 ) {
      $page = get_post( $pageid );
      if ( !$page || $page->post_status != 'publish' ) {
        $pageid = -1;
      }
    }
    return $pageid;
  }
  
  // returns the selected method
  private function get_404page_method() {
    if ( defined( 'ICL_SITEPRESS_VERSION' ) || defined( 'POLYLANG_VERSION' ) ) {
      // WPML or Polylang is active
      return 'CMP';
    } else {
      return get_option( '404page_method', 'STD' );
    }
  }
  
  // should we hide the selected 404 page from the page list?
  private function get_404page_hide() {
    return (bool)get_option( '404page_hide', false );
  }
  
  // should we fire an 404 error if the selected page is accessed directly?
  private function get_404page_fire_error() {
    return (bool)get_option( '404page_fire_error', true );
  }
  
  // this function gets the id of the translated page if WPML or Polylang is active - otherwise the original pageid is returned
  private function get_page_id( $pageid ) {
    
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
          
      // WPML is active
      $pageid = apply_filters( 'wpml_object_id', $pageid, 'page', true );
      
    } elseif ( defined( 'POLYLANG_VERSION' ) ) {
      
      // Polylang is active
      $translatedpageid = pll_get_post( $pageid );
      if ( !empty( $translatedpageid ) && 'publish' == get_post_status( $translatedpageid ) ) {
        $pageid = $translatedpageid;
      }
      
    }
    
    return $pageid;
    
  }
  
  // make plugin expandable
  function do_404page_action() {
    do_action( '404page_after_404' );
  }
  
  // show meta boxes
  function show_meta_boxes() {
    ?>
    <div id="postbox-container-1" class="postbox-container">
      <div class="meta-box-sortables">
        <div class="postbox">
          <h3><span><?php _e( 'Like this Plugin?', '404page' ); ?></span></h3>
          <div class="inside">
            <ul>
              <li><div class="dashicons dashicons-wordpress"></div>&nbsp;&nbsp;<a href="<?php echo $this->wp_url; ?>/"><?php _e( 'Please rate the plugin', '404page' ); ?></a></li>
              <li><div class="dashicons dashicons-admin-home"></div>&nbsp;&nbsp;<a href="<?php echo $this->my_url; ?>/"><?php _e( 'Plugin homepage', '404page'); ?></a></li>
              <li><div class="dashicons dashicons-admin-home"></div>&nbsp;&nbsp;<a href="http://petersplugins.com/"><?php _e( 'Author homepage', '404page' );?></a></li>
              <li><div class="dashicons dashicons-googleplus"></div>&nbsp;&nbsp;<a href="http://g.petersplugins.com/"><?php _e( 'Authors Google+ Page', '404page' ); ?></a></li>
              <li><div class="dashicons dashicons-facebook-alt"></div>&nbsp;&nbsp;<a href="http://f.petersplugins.com/"><?php _e( 'Authors facebook Page', '404page' ); ?></a></li>
            </ul>
          </div>
        </div>
        <div class="postbox">
          <h3><span><?php _e( 'Need help?', '404page' ); ?></span></h3>
          <div class="inside">
            <ul>
              <li><div class="dashicons dashicons-book-alt"></div>&nbsp;&nbsp;<a href="<?php echo $this->dc_url; ?>"><?php _e( 'Take a look at the Plugin Doc', '404page' ); ?></a></li>
              <li><div class="dashicons dashicons-wordpress"></div>&nbsp;&nbsp;<a href="<?php echo $this->wp_url; ?>/faq/"><?php _e( 'Take a look at the FAQ section', '404page' ); ?></a></li>
              <li><div class="dashicons dashicons-wordpress"></div>&nbsp;&nbsp;<a href="http://wordpress.org/support/plugin/<?php echo $this->plugin_slug; ?>/"><?php _e( 'Take a look at the Support section', '404page'); ?></a></li>
              <li><div class="dashicons dashicons-admin-comments"></div>&nbsp;&nbsp;<a href="http://petersplugins.com/contact/"><?php _e( 'Feel free to contact the Author', '404page' ); ?></a></li>
            </ul>
          </div>
        </div>
        <div class="postbox">
          <h3><span><?php _e( 'Translate this Plugin', '404page' ); ?></span></h3>
          <div class="inside">
            <p><?php _e( 'It would be great if you\'d support the 404page Plugin by adding a new translation or keeping an existing one up to date!', '404page' ); ?></p>
            <p><a href="https://translate.wordpress.org/projects/wp-plugins/<?php echo $this->plugin_slug; ?>"><?php _e( 'Translate online', '404page' ); ?></a></p>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  
  // add a link to settings page in plugin list
  function add_settings_link( $links ) {
    return array_merge( $links, array( '<a href="' . admin_url( 'themes.php?page=404pagesettings' ) . '">' . __( 'Settings', '404page' ) . '</a>') );
  }
  
  // uninstall plugin
  function uninstall() {
    if( is_multisite() ) {
      $this->uninstall_network();
    } else {
      $this->uninstall_single();
    }
  }
  
  // uninstall network wide
  function uninstall_network() {
    global $wpdb;
    $activeblog = $wpdb->blogid;
    $blogids = $wpdb->get_col( esc_sql( 'SELECT blog_id FROM ' . $wpdb->blogs ) );
    foreach ($blogids as $blogid) {
      switch_to_blog( $blogid );
      $this->uninstall_single();
    }
    switch_to_blog( $activeblog );
  }
  
  // uninstall single blog
  function uninstall_single() {
    foreach ( $this->settings as $key => $value) {
      delete_option( $key );
    }
  }
  
  // *
  // * functions for theme usage
  // *
  
  // check if there's a custom 404 page set
  function pp_404_is_active() {
    return ( $this->settings['404page_page_id'] > 0 );
  }
  
  // activate the native theme support
  function pp_404_set_native_support() {
    $this->settings['404page_native'] = true;
  }
  
  // get the title - native theme support
  function pp_404_get_the_title() {
    $title = '';
    if ( $this->settings['404page_page_id'] > 0 && $this->settings['404page_native'] ) {
      $title = get_the_title( $this->settings['404page_page_id'] );
    }
    return $title;
  }
  
  // print title - native theme support
  function pp_404_the_title() {
    echo $this->pp_404_get_the_title();
  }
  
   // get the content - native theme support
  function pp_404_get_the_content() {
    $content = '';
    if ( $this->settings['404page_page_id'] > 0 && $this->settings['404page_native'] ) {
      $content = apply_filters( 'the_content', get_post_field( 'post_content', $this->settings['404page_page_id'] ) );
    }
    return $content;
  }
  
  // print content - native theme support
  function pp_404_the_content() {
    echo $this->pp_404_get_the_content();
  }
  
}

$smart404page = new Smart404Page();

// this function can be used by a theme to check if there's an active custom 404 page
function pp_404_is_active() {
  global $smart404page;
  return $smart404page->pp_404_is_active();
}

// this function can be used by a theme to activate native support
function pp_404_set_native_support() {
  global $smart404page;
  $smart404page->pp_404_set_native_support();
}

// this function can be used by a theme to get the title of the custom 404 page in native support
function pp_404_get_the_title() {
  global $smart404page;
  return $smart404page->pp_404_get_the_title();
}

// this function can be used by a theme to print out the title of the custom 404 page in native support
function pp_404_the_title() {
  global $smart404page;
  $smart404page->pp_404_the_title();
}

// this function can be used by a theme to get the content of the custom 404 page in native support
function pp_404_get_the_content() {
  global $smart404page;
  return $smart404page->pp_404_get_the_content();
}

// this function can be used by a theme to print out the content of the custom 404 page in native support
function pp_404_the_content() {
  global $smart404page;
  return $smart404page->pp_404_the_content();
}

?>