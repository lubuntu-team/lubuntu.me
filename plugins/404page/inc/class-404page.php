<?php

/**
 * The 404page core plugin class
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


// indicate that 404page plugin is active
if ( ! defined( 'PP_404' ) ) {
  define( 'PP_404', true );
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_404Page' ) ) {
  
  
  class PP_404Page {
    public $plugin_name;
    public $plugin_slug;
    public $version;
    private $_file;
    private $wp_url;
    private $my_url;
    private $dc_url;
    private $settings;
    private $template;
    private $postid;
    private $admin_handle;
    
    
    /**
	   * here we go
     */
    public function __construct( $file ) {
      $this->_file = $file;
      $this->plugin_name = '404page';
      $this->plugin_slug = '404page';
      $this->version = '3.3';
      $this->get_settings();
      $this->load();
    } 
    
    
    /**
     * get all settings
     * except 404page_method
     * the 404page_method setting is set in function set_mode() because it may be too early here and not everything is loaded properly
     */
    private function get_settings() {
      $this->settings = array();
      $this->settings['404page_page_id'] = $this->get_404page_id();
      $this->settings['404page_hide'] = $this->get_404page_hide();
      $this->settings['404page_fire_error'] = $this->get_404page_fire_error();
      $this->settings['404page_force_error'] = $this->get_404page_force_error();
      $this->settings['404page_no_url_guessing'] = $this->get_404page_no_url_guessing();
      $this->settings['404page_http410_if_trashed'] = $this->get_404page_http410_if_trashed();
      $this->settings['404page_native'] = false;
    }
    
    
    /**
     * Load
     * runs the init() function on firing of init action to ensure everything is loaded properly
     */
    private function load() {
      
      $this->wp_url = 'https://wordpress.org/plugins/' . $this->plugin_slug;
      $this->my_url = 'https://petersplugins.com/free-wordpress-plugins/' . $this->plugin_slug;
      $this->dc_url = 'https://petersplugins.com/docs/' . $this->plugin_slug;
      
      add_action( 'init', array( $this, 'add_text_domain' ) );
      add_action( 'init', array( $this, 'init' ) );
      
      add_action( 'admin_notices', array( $this, 'admin_notices' ) );
      add_action( 'wp_ajax_pp_404page_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
      
    }
   
   
    /**
     * do plugin init 
     * this runs after init action has fired to ensure everything is loaded properly
     */
    function init() {
      
      // as of v 2.2 always call set_mode
      // as of v 2.4 we do not need to add an init action hook
      
      if ( !is_admin() && $this->settings['404page_page_id'] > 0 ) {
        
        // as of v 3.0 we once check if there's a 404 page set and not in all functions separately
        $this->set_mode();
        add_action( 'pre_get_posts', array ( $this, 'exclude_404page' ) );
        add_filter( 'get_pages', array ( $this, 'remove_404page_from_array' ), 10, 2 );
        
        // Stop URL guessing if activated
        if ( $this->settings['404page_no_url_guessing'] ) {
          add_filter( 'redirect_canonical' ,array ( $this, 'no_url_guessing' ) );
        }
        
      } else {
        
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_head', array( $this, 'admin_style' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( $this->_file ), array( $this, 'add_settings_links' ) ); 
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_js' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
        
        // Remove 404 page from post list if activated
        if ( $this->settings['404page_hide'] and $this->settings['404page_page_id'] > 0 ) {
          add_action( 'pre_get_posts' ,array ( $this, 'exclude_404page' ) );
        }
        
      }
      
    }
    
    
    /**
     * add text domain
     */
    function add_text_domain() {  
    
      load_plugin_textdomain( '404page' );
      
    }
    
    
    /**
     * init filters 
     */
    function set_mode() {
      
      $this->settings['404page_method'] = $this->get_404page_method();
           
      if ( defined( 'CUSTOMIZR_VER' ) ) {
        
        // Customizr Compatibility Mode 

        // @since 3.1
        add_filter( 'body_class', array( $this, 'add_404_body_class_customizr_mode' ) );
        
        add_filter( 'tc_404_header_content', array( $this, 'show404title_customizr_mode' ), 999 );
        add_filter( 'tc_404_content', array( $this, 'show404_customizr_mode' ), 999 );
        add_filter( 'tc_404_selectors', array( $this, 'show404articleselectors_customizr_mode' ), 999 );
        
        // send http 410 instead of http 404 if requested resource is in trash
        // @since 3.2
        if ( $this->settings['404page_http410_if_trashed'] ) {
          
          add_action( 'template_redirect', array( $this, 'maybe_send_410' ) 	);
          
        }
        
      } elseif ( $this->settings['404page_method'] != 'STD' ) {
          
        // Compatibility Mode
        // as of v 2.4 we use the the_posts filter instead of posts_results, because the posts array is internally processed after posts_results fires
        add_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 );
        
        // as of v 2.5 we remove the filter if the DW Question & Answer plugin by DesignWall (https://www.designwall.com/wordpress/plugins/dw-question-answer/) is active and we're in the answers list
        add_filter( 'dwqa_prepare_answers', array( $this, 'remove_show404_compatiblity_mode' ), 999 );
          
      } else {
          
        // Standard Mode
        add_filter( '404_template', array( $this, 'show404_standard_mode' ), 999 );
        
        if ( $this->settings['404page_fire_error'] ) {
          
          add_action( 'template_redirect', array( $this, 'do_404_header_standard_mode' ) );
          
        }
        
        // send http 410 instead of http 404 if requested resource is in trash
        // @since 3.2
        if ( $this->settings['404page_http410_if_trashed'] ) {
          
          add_action( 'template_redirect', array( $this, 'maybe_send_410' ) 	);
          
        }
          
      }
      
    }
    
    
    /**
     * show 404 page 
     * Standard Mode
     */
    function show404_standard_mode( $template ) {
      
      global $wp_query;
      if ( ! $this->settings['404page_native'] ) {
        $wp_query = null;
        $wp_query = new WP_Query();
        $wp_query->query( 'page_id=' . $this->get_page_id() );
        $wp_query->the_post();
        $template = get_page_template();
        rewind_posts();
        add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
      }
      $this->maybe_force_404();
      $this->do_404page_action();
      return $template;
      
    }
    
    /**
     * show 404 page
     * Compatibility Mode
     */
    function show404_compatiblity_mode( $posts ) {
      
      // remove the filter so we handle only the first query - no custom queries
      remove_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 ); 
      
      $pageid = $this->get_page_id();
      if ( ! $this->settings['404page_native'] ) {
        if ( empty( $posts ) && is_main_query() && !is_robots() && !is_home() && !is_feed() && !is_search() && !is_archive() && ( !defined('DOING_AJAX') || !DOING_AJAX ) ) {
          
          // as of v2.1 we do not alter the posts argument here because this does not work with SiteOrigin's Page Builder Plugin, template_include filter introduced
          $this->postid = $pageid;
          
          // as of v 2.4 we use the the_posts filter instead of posts_results
          // therefore we have to reset $wp_query 
          // resetting $wp_query also forces us to remove the pre_get_posts action plus the get_pages filter
          
          remove_action( 'pre_get_posts', array ( $this, 'exclude_404page' ) );
          remove_filter( 'get_pages', array ( $this, 'remove_404page_from_array' ), 10, 2 );
          
          global $wp_query;
          $wp_query = null;
          $wp_query = new WP_Query();
          $wp_query->query( 'page_id=' . $pageid );
          $wp_query->the_post();

          $this->template = get_page_template();
          $posts = $wp_query->posts;
          $wp_query->rewind_posts();

          add_action( 'wp', array( $this, 'do_404_header' ) );
          add_filter( 'body_class', array( $this, 'add_404_body_class' ) );
          add_filter( 'template_include', array( $this, 'change_404_template' ), 999 );
          
          $this->maybe_force_404();
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
              $this->maybe_force_404();
              $this->do_404page_action();
            }
          }
          
        }
      } else {
        $this->maybe_force_404();
        $this->do_404page_action();
      }
      return $posts;
    }
    
    
    /**
     * for DW Question & Answer plugin
     * this function is called by the dwqa_prepare_answers filter
     */
    function remove_show404_compatiblity_mode( $args ) {
      remove_filter( 'the_posts', array( $this, 'show404_compatiblity_mode' ), 999 );
      return $args;
    }
    
    
    /**
     * this function overrides the page template in compatibilty mode
     */
    function change_404_template( $template ) {
      
      // we have to check if the template file is there because if the theme was changed maybe a wrong template is stored in the database
      $new_template = locate_template( array( $this->template ) );
      if ( '' != $new_template ) {
        return $new_template ;
      }
      return $template;
    }
    
    
    /**
     * send 404 HTTP header
     * Standard Mode
     */
    function do_404_header_standard_mode() {
      if ( is_page() && get_the_ID() == $this->settings['404page_page_id'] && !is_404() ) {
        status_header( 404 );
        nocache_headers();
        $this->maybe_force_404();
        $this->do_404page_action();
      }
    }
    
    
    /**
     * send 404 HTTP header 
     * Compatibility Mode
     */
    function do_404_header() {
      // remove the action so we handle only the first query - no custom queries
      remove_action( 'wp', array( $this, 'do_404_header' ) );
      
      // send http 410 instead of http 404 if requested resource is in trash
      // @since 3.2
      
      if ( $this->settings['404page_http410_if_trashed'] && $this->is_url_in_trash( rawurldecode ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) ) ) {
          
        status_header( 410 );
          
      } else {
      
        status_header( 404 );
        
      }
      nocache_headers();
    }
    
    
    /**
     * add body classes
     */
    function add_404_body_class( $classes ) {
      
      // as of v 3.1 we first check if the class error404 already exists
      if ( ! in_array( 'error404', $classes ) ) {
      
        $classes[] = 'error404';
      
      }
      
      // debug class
      // @since 3.1
      $debug_class = 'pp404-';
      if ( $this->settings['404page_native'] ) {
        $debug_class .= 'native';
      } elseif ( defined( 'CUSTOMIZR_VER' ) ) {
        $debug_class .= 'customizr';
      } elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        $debug_class .= 'wpml';
      } elseif ( $this->settings['404page_method'] != 'STD' ) {
        $debug_class .= 'wpml';
      } else {
        $debug_class .= 'std';
      }
      $classes[] = $debug_class;
      
      return $classes;
    }
    
    
    /**
     * add body classes customizr mode
     * @since 3.1
     */
    function add_404_body_class_customizr_mode( $classes ) {
      
      if ( is_404() ) {
        
        $classes = $this->add_404_body_class( $classes );
      
      }
      
      return $classes;
    }
    
    
    /**
     * show title
     * Customizr Compatibility Mode
     */ 
    function show404title_customizr_mode( $title ) {
      if ( ! $this->settings['404page_native'] ) {
        return '<h1 class="entry-title">' . get_the_title( $this->get_page_id() ) . '</h1>';
      } else {
        return $title;
      }
    }
    
    
    /**
     * show content
     * Customizr Compatibility Mode
     */
    function show404_customizr_mode( $content ) {
      if ( ! $this->settings['404page_native'] ) {
        return '<div class="entry-content">' . apply_filters( 'the_content', get_post_field( 'post_content', $this->get_page_id() ) ) . '</div>';
      } else {
        return $content;
      }
      $this->do_404page_action();
    }
    
    
    /**
     * change article selectors 
     * Customizr Compatibility Mode
     */
    function show404articleselectors_customizr_mode( $selectors ) {
      if ( ! $this->settings['404page_native'] ) {
        return 'id="post-' . $this->get_page_id() . '" ' . 'class="' . join( ' ', get_post_class( 'row-fluid', $this->get_page_id() ) ) . '"';
      } else {
        return $selectors;
      }
    }
    
    
    /**
     * do we have to force a 404 in wp_head?
     */
    function maybe_force_404() {
      if ( $this->settings['404page_force_error'] ) {
        add_action( 'wp_head', array( $this, 'force_404_start' ), 9.9 );
        add_action( 'wp_head', array( $this, 'force_404_end' ), 99 );
      }
    }
    
        
    /**
     * Force 404 in wp_head start
     * potentially dangerous!
     */
    function force_404_start() {
      global $wp_query;
      $wp_query->is_404 = true;
    }
    
    
    /**
     * Force 404 in wp_head end
     * potentially dangerous!
     */
    function force_404_end() {
      global $wp_query;
      $wp_query->is_404 = false;
    }
    
    
    /**
     * disable URL autocorrect guessing
     */
    function no_url_guessing( $redirect_url ) {
      if ( is_404() && !isset($_GET['p']) ) {
        $redirect_url = false;
      }  
      return $redirect_url;
    }
    
    
    /**
     * send http 410 instead of http 404 in case the requested URL can be found in trash
     * @since 3.2
     */
    function maybe_send_410() {
            
      // we don't do anything if there is no 404
      if ( is_404() ) {
        
        if ( $this->is_url_in_trash( rawurldecode ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ) ) ) {
          
          status_header( 410 );
          
        }
      }
      
    }
    
    
    /**
     * init admin 
     */
    function admin_init() {
      
      $this->settings['404page_method'] = $this->get_404page_method();
      
      
      add_settings_section( '404page-settings', null, null, '404page_settings_section' );
      add_settings_section( '404page-settings', null, null, '404page_settings_section_advanced' );
      register_setting( '404page_settings', '404page_page_id' );
      register_setting( '404page_settings', '404page_hide' );
      register_setting( '404page_settings', '404page_method', array( $this, 'handle_method' ) );
      register_setting( '404page_settings', '404page_fire_error' );
      register_setting( '404page_settings', '404page_force_error' );
      register_setting( '404page_settings', '404page_no_url_guessing' );
      register_setting( '404page_settings', '404page_http410_if_trashed' );
      add_settings_field( '404page_settings_404page', __( 'Page to be displayed as 404 page', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_select_page"></a>' , array( $this, 'admin_404page' ), '404page_settings_section', '404page-settings', array( 'label_for' => '404page_page_id' ) );
      add_settings_field( '404page_settings_hide', '' , array( $this, 'admin_hide' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_hide' ) );
      add_settings_field( '404page_settings_fire', '' , array( $this, 'admin_fire404' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_fire_error' ) );
      add_settings_field( '404page_settings_force', '' , array( $this, 'admin_force404' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_force_error' ) );
      add_settings_field( '404page_settings_noguess', '' , array( $this, 'admin_noguess' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_no_url_guessing' ) );
      add_settings_field( '404page_settings_http410', '' , array( $this, 'admin_http410' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_http410_if_trashed' ) );
      add_settings_field( '404page_settings_method', '', array( $this, 'admin_method' ), '404page_settings_section_advanced', '404page-settings', array( 'label_for' => '404page_method' ) );
    }
    
    
    /**
     * add admin css to header
     */
    function admin_style() {
      
      echo '<style type="text/css">#pp-plugin-info-404page { background-image: url(' . plugins_url( 'assets/pluginicon.png', $this->_file ) .'); }';
      
      if ( $this->settings['404page_page_id'] > 0 ) {
        
        foreach ( $this->get_all_page_ids() as $pid ) {
          
          echo ' #the-list #post-' . $pid . ' .column-title .row-title:before { content: "404"; background-color: #333; color: #FFF; display: inline-block; padding: 0 5px; margin-right: 10px; }';
          
        }
        
      }
      
      echo '</style>';
      
    }
    
    
    /**
     * handle the settings field page id
     */
    function admin_404page() {
      
      if ( $this->settings['404page_page_id'] < 0 ) {
        
        echo '<div class="error form-invalid" style="line-height: 3em">' . __( 'The page you have selected as 404 page does not exist anymore. Please choose another page.', '404page' ) . '</div>';
      }
      
      wp_dropdown_pages( array( 'name' => '404page_page_id', 'id' => 'select404page', 'echo' => 1, 'show_option_none' => __( '&mdash; NONE (WP default 404 page) &mdash;', '404page'), 'option_none_value' => '0', 'selected' => $this->settings['404page_page_id'] ) );
      
      echo '<div id="404page_edit_link" style="display: none">' . get_edit_post_link( $this->settings['404page_page_id'] )  . '</div>';
      echo '<div id="404page_test_link" style="display: none">' . get_site_url() . '/404page-test-' . md5( rand() ) . '</div>';
      echo '<div id="404page_current_value" style="display: none">' . $this->settings['404page_page_id'] . '</div>';
      echo '<p class="submit"><input type="button" name="edit_404_page" id="edit_404_page" class="button secondary" value="' . __( 'Edit Page', '404page' ) . '" />&nbsp;<input type="button" name="test_404_page" id="test_404_page" class="button secondary" value="' . __( 'Test 404 error', '404page' ) . '" /></p>';
      
    }
    
    
    /**
     * handle the settings field hide
     */
    function admin_hide() {
      
      echo '<p><input type="checkbox" id="404page_hide" name="404page_hide" value="1"' . checked( true, $this->settings['404page_hide'], false ) . '/>';
      echo '<label for="404page_hide" class="check"></label>' . __( 'Hide the selected page from the Pages list', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_hide_page"></a><br />';
      echo '<span class="dashicons dashicons-info"></span>&nbsp;' . __( 'For Administrators the page is always visible.', '404page' ) . '</p><div class="clear"></div>';
      
    }
    
    
    /**
     * handle the settings field fire 404 error
     */
    function admin_fire404() {
      
      echo '<p><input type="checkbox" id="404page_fire_error" name="404page_fire_error" value="1"' . checked( true, $this->settings['404page_fire_error'], false ) . '/>';
      echo '<label for="404page_fire_error" class="check"></label>' . __( 'Send an 404 error if the page is accessed directly by its URL', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_fire_404"></a><br />';
      echo '<span class="dashicons dashicons-info"></span>&nbsp;' . __( 'Uncheck this if you want the selected page to be accessible.', '404page' );
      
      if ( function_exists( 'wpsupercache_activate' ) ) {
        
        echo '<br /><span class="dashicons dashicons-warning"></span>&nbsp;<strong>' . __( 'WP Super Cache Plugin detected', '404page' ) . '</strong>. ' . __ ( 'If the page you selected as 404 error page is in cache, always a HTTP code 200 is sent. To avoid this and send a HTTP code 404 you have to exlcude this page from caching', '404page' ) . ' (<a href="' . admin_url( 'options-general.php?page=wpsupercache&tab=settings#rejecturi' ) . '">' . __( 'Click here', '404page' ) . '</a>).<br />(<a href="' . $this->dc_url . '/#wp_super_cache">' . __( 'Read more', '404page' ) . '</a>)';
        
      }
      
      echo '</p><div class="clear"></div>';
      
    }
    
    
    /**
     * handle the settings field to force an 404 error
     */
    function admin_force404() {
      
      echo '<p><input type="checkbox" id="404page_force_error" name="404page_force_error" value="1"' . checked( true, $this->settings['404page_force_error'], false ) . '/>';
      echo '<label for="404page_force_error" class="check warning"></label>' . __( 'Force 404 error after loading page', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_force_404"></a><br />';
      echo '<span class="dashicons dashicons-warning"></span>&nbsp;' . __( 'Generally this is not needed. It is not recommended to activate this option, unless it is necessary. Please note that this may cause problems with your theme.', '404page' ) . '</p><div class="clear"></div>';
      
    }
    
    
    /**
     * handle the settings field to stop URL guessing
     */
    function admin_noguess() {
      
      echo '<p><input type="checkbox" id="404page_no_url_guessing" name="404page_no_url_guessing" value="1"' . checked( true, $this->settings['404page_no_url_guessing'], false ) . '/>';
      echo '<label for="404page_no_url_guessing" class="check warning"></label>' . __( 'Disable URL autocorrection guessing', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_stop_guessing"></a><br />';
      echo '<span class="dashicons dashicons-warning"></span>&nbsp;' . __( 'This stops WordPress from URL autocorrection guessing. Only activate, if you are sure about the consequences.', '404page' ) . '</p><div class="clear"></div>';
    
    }
    
    
    /**
     * handle the settings field to send an http 410 error in case the object is trashed
     * @since 3.2
     */
    function admin_http410() {
      
      echo '<p><input type="checkbox" id="404page_http410_if_trashed" name="404page_http410_if_trashed" value="1"' . checked( true, $this->settings['404page_http410_if_trashed'], false ) . '/>';
      echo '<label for="404page_http410_if_trashed" class="check"></label>' . __( 'Send an HTTP 410 error instead of HTTP 404 in case the requested object is in trash', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_maybe_send_http410"></a><br />';
      echo '<span class="dashicons dashicons-info"></span>&nbsp;' . __( 'Check this if you want to inform search engines that the resource requested is no longer available and will not be available again so it can be removed from the search index immediately.', '404page' );
    
    }
    
    
    /**
     * handle the settings field method
     */
    function admin_method() {

      if ( $this->settings['404page_native'] || defined( 'CUSTOMIZR_VER' ) || defined( 'ICL_SITEPRESS_VERSION' ) ) {
        
        $dis = ' disabled="disabled"';
        
      } else {
        
        $dis = '';
      }
      
      echo '<p><input type="checkbox" id="404page_method" name="404page_method" value="CMP"' . checked( 'CMP', $this->settings['404page_method'], false ) . $dis . '/>';
      echo '<label for="404page_method" class="check"></label>' . __( 'Activate Compatibility Mode', '404page' ) . '&nbsp;<a class="dashicons dashicons-editor-help" href="' . $this->dc_url . '/#settings_operating_method"></a><br />';
      echo '<span class="dashicons dashicons-info"></span>&nbsp;';
      
      if ( $this->settings['404page_native'] ) {
        
        _e( 'This setting is not available because the Theme you are using natively supports the 404page plugin.', '404page' );
        echo ' (<a href="' . $this->dc_url . '/#native_mode">' . __( 'Read more', '404page' ) . '</a>)';
      
      } elseif ( defined( 'CUSTOMIZR_VER' ) ) {
      
        _e( 'This setting is not availbe because the 404page Plugin works in Customizr Compatibility Mode.', '404page' );
        echo  ' (<a href="' . $this->dc_url . '/#special_modes">' . __( 'Read more', '404page' ) . '</a>)';
      
      } elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
      
        _e( 'This setting is not availbe because the 404page Plugin works in WPML Mode.', '404page' );
        echo ' (<a href="' . $this->dc_url . '/#special_modes">' . __( 'Read more', '404page' ) . '</a>)';
        
      } else {
                
        _e( 'If you are using a theme or plugin that modifies the WordPress Template System, the 404page plugin may not work properly. Compatibility Mode maybe can fix the problem. Activate Compatibility Mode only if you have any problems.', '404page' );
     
      }
      
      echo '</p><div class="clear"></div>';

    }
    
    
    /**
     * handle the method setting
     */
    function handle_method( $method ) {
      
      if ( null === $method ) {
        
        $method = 'STD';
        
      }
      
      return $method;
      
    }
    
    
    /**
     * hide the 404 page from the list of pages 
     */
     
    function exclude_404page( $query ) {
      
      $pageid = $this->get_page_id();
      
      if ( $pageid > 0 ) {
        
        global $pagenow;
        
        $post_type = $query->get( 'post_type' );

        // as of v 2.3 we check the post_type on front end
        // as of v 2.5 we also hide the page from search results on front end
        if( ( is_admin() && ( 'edit.php' == $pagenow && !current_user_can( 'create_users' ) ) ) || ( ! is_admin() && ( is_search() || ( !empty( $post_type) && ( ('page' === $post_type || 'any' === $post_type) || ( is_array( $post_type ) && in_array( 'page', $post_type ) ) ) ) ) ) ) {
          
          // as of v 2.4 we hide all translations in admin for WPML
          // as of v 2.5 we hide all translations from search results on front end for WPML
          if ( is_admin() || ( ! is_admin() && is_search() ) ) {
            
            $pageids = $this->get_all_page_ids();
            
          } else {
            
            $pageids = array( $pageid );
            
          }
          
          // as of v 2.3 we add the ID of the 404 page to post__not_in
          // using just $query->set() overrides existing settings but not adds a new setting
          $query->set( 'post__not_in', array_merge( (array)$query->get( 'post__not_in', array() ), $pageids ) );
          
        }
        
      }
      
    }
    
    
    /**
     * remove the 404 page from get_pages result array
     */
    function remove_404page_from_array( $pages, $r ) {
      
      $pageid = $this->get_page_id();
      
      if ( $pageid > 0 ) {
        
        for ( $i = 0; $i < sizeof( $pages ); $i++ ) {			
        
          if ( $pages[$i]->ID == $pageid ) {
            
            unset( $pages[$i] );
            break;
            
          }
          
        }
      
      }
      
      return array_values( $pages );
      
    }
    
    
    /**
     * check if the requested url is found in trash
     * @since 3.2
     * based on WP core function url_to_postid()
     */
    function is_url_in_trash( $url ) {
	
      global $wp_rewrite;
      global $wp;
	
      // First, check to see if there is a 'p=N' or 'page_id=N' to match against
      if ( preg_match( '#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values ) ) {
        
        $id = absint( $values[2] );
        
        if ( $id ) {
          
          if ( 'trash' == get_post_status( $id ) ) {
            
            return true;
          
          } else {
            
            return false;
          
          }
          
        }
        
      }
      
      // Check to see if we are using rewrite rules
      $rewrite = $wp_rewrite->wp_rewrite_rules();
      
      // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
      if ( empty( $rewrite ) ) {
        
        return false;
        
      }
          
      // Get rid of the #anchor
      $url_split = explode('#', $url);
      $url = $url_split[0];
      
      // Get rid of URL ?query=string
      $url_split = explode('?', $url);
      $url = $url_split[0];
      
      // Add 'www.' if it is absent and should be there
      if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) ) {
      
        $url = str_replace('://', '://www.', $url);
      
      }
      
      // Strip 'www.' if it is present and shouldn't be
      if ( false === strpos( home_url(), '://www.' ) ) {
		
        $url = str_replace('://www.', '://', $url);
        
      }
	
      // Strip 'index.php/' if we're not using path info permalinks
      if ( !$wp_rewrite->using_index_permalinks() ) {
		
        $url = str_replace( $wp_rewrite->index . '/', '', $url );
        
      }
	
  
      if ( false !== strpos( trailingslashit( $url ), home_url( '/' ) ) ) {
		
        // Chop off http://domain.com/[path]
        $url = str_replace(home_url(), '', $url);
      
      } else {
		
        // Chop off /path/to/blog
        $home_path = parse_url( home_url( '/' ) );
        $home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
        $url = preg_replace( sprintf( '#^%s#', preg_quote( $home_path ) ), '', trailingslashit( $url ) );
      
      }
	
      // Trim leading and lagging slashes
      $url = trim($url, '/');
	
      $request = $url;
      $post_type_query_vars = array();
      
      foreach ( get_post_types( array() , 'objects' ) as $post_type => $t ) {
        
        if ( ! empty( $t->query_var ) ) {
          
          $post_type_query_vars[ $t->query_var ] = $post_type;
          
        }
      }
	
      // Look for matches.
      $request_match = $request;
      foreach ( (array)$rewrite as $match => $query) {
		
        // If the requesting file is the anchor of the match, prepend it
        // to the path info.
        if ( !empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) ) {
			
          $request_match = $url . '/' . $request;
          
        }
		
        if ( preg_match( "#^$match#", $request_match, $matches ) ) {
			
          if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
				
            // This is a verbose page match, let's check to be sure about it.
            if ( ! get_page_by_path( $matches[ $varmatch[1] ] ) ) {
					
              continue;
              
            }
          }

          // Got a match.
			
          // Trim the query of everything up to the '?'.
          $query = preg_replace( "!^.+\?!", '', $query );
			
          // Substitute the substring matches into the query.
          $query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );
			
          // Filter out non-public query vars
          parse_str( $query, $query_vars );
          $query = array();
          
          foreach ( (array) $query_vars as $key => $value ) {
          
            if ( in_array( $key, $wp->public_query_vars ) ) {
					
              $query[$key] = $value;
					
              if ( isset( $post_type_query_vars[$key] ) ) {
						
                $query['post_type'] = $post_type_query_vars[$key];
                $query['name'] = $value;
					
              }
              
            }
            
          }
          
          // Magic
          if ( isset( $query['pagename'] ) ) {
           
            $query['pagename'] .= '__trashed' ;
            
          }
          
          if ( isset( $query['name'] ) ) {

            $query['name'] .= '__trashed' ;
            
          }
          
          $query['post_status'] = array( 'trash' );
          
          // Resolve conflicts between posts with numeric slugs and date archive queries.
          $query = wp_resolve_numeric_slug_conflicts( $query );
          
          // Do the query
          $query = new WP_Query( $query );
          
          if ( $query->found_posts == 1 ) {
				
            return true;
            
          } else {
				
            return false;
            
          }
        
        }
      
      }
	
      return false;

    }
    
    
    /**
     * create the menu entry
     */
    function admin_menu() {
      $this->admin_handle = add_theme_page ( __( '404 Error Page', "404page" ), __( '404 Error Page', '404page' ), 'manage_options', '404pagesettings', array( $this, 'admin_page' ) );
    }
    
    
    /**
     * add admin css file
     */
    function admin_css() {
      
      if ( get_current_screen()->id == $this->admin_handle ) {
        
        wp_enqueue_style( '404pagecss', plugins_url( 'assets/css/404page-ui.css', $this->_file ) );
        
      }
      
    }
    
    
    /**
     * add admin js files
     */
    function admin_js() {
    
      wp_enqueue_script( '404pagejs', plugins_url( 'assets/js/404page.js', $this->_file ), 'jquery', $this->version, true );
      
      if ( get_current_screen()->id == $this->admin_handle ) {
        
        wp_enqueue_script( '404page-ui', plugins_url( 'assets/js/404page-ui.js', $this->_file ), 'jquery', $this->version, true );
      
      }
      
    }
   
   
    /**
     * show admin page
     */
    function admin_page() {
      
      if ( !current_user_can( 'manage_options' ) )  {
        
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        
      }
      ?>
      <div class="wrap" id="pp-404page-settings">
        <h1 id="pp-plugin-info-404page"><?php echo $this->plugin_name; ?> <?php _e( 'Settings', '404page' ); ?><span><a class="dashicons dashicons-wordpress" href="<?php echo $this->wp_url; ?>/" title="<?php _e( 'wordpress.org plugin directory', '404page' ); ?>"></a> <a class="dashicons dashicons-admin-home" href="https://petersplugins.com/" title="<?php _e( 'Author homepage', '404page' );?>"></a> <a class="dashicons dashicons-googleplus" href="https://plus.google.com/+petersplugins" title="<?php _e( 'Authors Google+ Page', '404page' ); ?>"></a> <a class="dashicons dashicons-facebook-alt" href="https://www.facebook.com/petersplugins" title="<?php _e( 'Authors facebook Page', '404page' ); ?>"></a> <a class="dashicons dashicons-book-alt" href="<?php echo $this->dc_url; ?>" title="<?php _e( 'Plugin Doc', '404page' ); ?>"></a> <a class="dashicons dashicons-editor-help" href="https://wordpress.org/support/plugin/<?php echo $this->plugin_slug; ?>/" title="<?php _e( 'Support', '404page'); ?>"></a> <a class="dashicons dashicons-admin-comments" href="https://petersplugins.com/contact/" title="<?php _e( 'Contact Author', '404page' ); ?>"></a></span></h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
          <?php settings_fields( '404page_settings' ); ?>
          <?php do_settings_sections( '404page_settings_section' ); ?>
          <div id="pp-seetings-advanced">
            <h3>Advanced Settings</h3>
            <?php do_settings_sections( '404page_settings_section_advanced' ); ?>
          </div>
          <?php submit_button(); ?>
        </form>
      </div>
      <?php
    }
    
    
    /**
     * show admin notices
     */
    function admin_notices() {
      
      // invite to follow me
      if ( current_user_can( 'manage_options' ) && get_user_meta( get_current_user_id(), 'pp-404page-admin-notice-1', true ) != 'dismissed' ) {
        ?>
        <div class="notice is-dismissible pp-404page-admin-notice" id="pp-404page-admin-notice-1">
          <p><img src="<?php echo plugins_url( 'assets/pluginicon.png', $this->_file ); ?>" style="width: 48px; height: 48px; float: left; margin-right: 20px" /><strong><?php _e( 'Do you like the 404page plugin?', '404page' ); ?></strong><br /><?php _e( 'Follow me:', '404page' ); ?> <a class="dashicons dashicons-googleplus" href="https://plus.google.com/+petersplugins" title="<?php _e( 'Authors Google+ Page', '404page' ); ?>"></a> <a class="dashicons dashicons-facebook-alt" href="https://www.facebook.com/petersplugins" title="<?php _e( 'Authors facebook Page', '404page' ); ?>"></a><div class="clear"></div></p>
        </div>
        <?php
      }
      
      // ask for rating
      // in 30 days at the earliest
      if ( ! get_option( 'pp-404page-admin-notice-2-start' ) ) {
        update_option( 'pp-404page-admin-notice-2-start', time() + 30 * 24 * 60 * 60 );
      }
      if ( get_option( 'pp-404page-admin-notice-2-start' ) <= time() ) {
        if ( current_user_can( 'manage_options' ) && get_user_meta( get_current_user_id(), 'pp-404page-admin-notice-2', true ) != 'dismissed' ) {
          ?>
          <div class="notice is-dismissible pp-404page-admin-notice" id="pp-404page-admin-notice-2">
            <p><img src="<?php echo plugins_url( 'assets/pluginicon.png', $this->_file ); ?>" style="width: 48px; height: 48px; float: left; margin-right: 20px" /><?php _e( 'If you like the 404page plugin please support my work with giving it a good rating so that other users know it is helpful for you. Thanks.', '404page' ); ?><br /><a href="https://wordpress.org/support/plugin/<?php echo $this->plugin_slug; ?>/reviews/#new-post" title="<?php _e( 'Please rate plugin', '404page' ); ?>"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a><div class="clear"></div></p>
          </div>
          <?php
        }
      }
            
    }
    
    
    /**
     * dismiss an admin notice
     */
    function dismiss_admin_notice() {
      
      if ( isset( $_POST['pp_404page_dismiss_admin_notice'] ) ) {
        
        update_user_meta( get_current_user_id(), $_POST['pp_404page_dismiss_admin_notice'], 'dismissed' );
        
      }
      
      wp_die();
      
    }
    
    
    /**
     * get the id of the 404 page
     * returns 0 if none is defined, returns -1 if the defined page id does not exist
     */
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
    
    
    /**
     * get the selected method
     */
    private function get_404page_method() {
      
      if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        
        // WPML is active
        return 'CMP';
        
      } else {
        
        return get_option( '404page_method', 'STD' );
        
      }
      
    }
    
    
    /**
     * do we have to hide the selected 404 page from the page list?
     */
    private function get_404page_hide() {
      
      return (bool)get_option( '404page_hide', false );
      
    }
    
    
    /**
     * do we have to fire an 404 error if the selected page is accessed directly?
     */
    private function get_404page_fire_error() {
      
      return (bool)get_option( '404page_fire_error', true );
      
    }
    
    
    /**
     * do we have to force the 404 error after loading the page?
     */
    private function get_404page_force_error() {
      
      return (bool)get_option( '404page_force_error', false );
      
    }
    
    
    /**
     * do we have to disable the URL guessing?
     */
    private function get_404page_no_url_guessing() {
      
      return (bool)get_option( '404page_no_url_guessing', false );
      
    }
    
    
    /**
     * do we have to send an http 410 error in case the object is in trash?
     * @since 3.2
     */
    private function get_404page_http410_if_trashed() {
      
      return (bool)get_option( '404page_http410_if_trashed', false );
      
    }
    
    
    /**
     * get the id of the 404 page in the current language if WPML or Polylang is active
     */
    private function get_page_id() {
      
      $pageid = $this->settings['404page_page_id'];
      
      if ( $pageid > 0 ) {
      
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
        
      }
      
      return $pageid;
      
    }
    
    /**
     * get 404 pages in all available languages
     * if WPML is active this function returns an array of all page ids in all available languages
     * otherwise it returns the page id as array
     * introduced in v 2.4
     */
    private function get_all_page_ids() {
      
      if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
            
        // WPML is active
        // get an array for all translations
        $pageid = $this->settings['404page_page_id'];
        $pages = array( $pageid );
        
        if ( $pageid > 0 ) {
          
          $languages = apply_filters( 'wpml_active_languages', NULL );
          
          if ( !empty( $languages ) ) {
            
            foreach( $languages as $l ) {
              
              $p = apply_filters( 'wpml_object_id', $pageid, 'page', false, $l['language_code'] ); 
              
              if ( $p ) {
                
                $pages[] = $p;
                
              }
              
            }
            
          }
          
        }
        
        $pageids = array_unique( $pages, SORT_NUMERIC );
          
      } else {
        
        $pageids = array( $this->get_page_id() );
        
      }
      
      return $pageids;
      
    }
    
    
    /**
     * fire 404page_after_404 hook to make plugin expandable
     */
    function do_404page_action() {
      
      do_action( '404page_after_404' );
      
    }
    
    
    /**
     * add links to plugins table
     */
    function add_settings_links( $links ) {
      
      return array_merge( $links, array( '<a href="' . admin_url( 'themes.php?page=404pagesettings' ) . '" title="' . __( 'Settings', '404page' ) . '">' . __( 'Settings', '404page' ) . '</a>', '<a href="https://wordpress.org/support/plugin/' . $this->plugin_slug . '/reviews/" title="' . __( 'Please rate plugin', '404page' ) . '">' . __( 'Please rate plugin', '404page' ) . '</a>' ) );
      
    }
    
    
    /**
     * uninstall plugin
     */
    function uninstall() {
      
      if( is_multisite() ) {
        
        $this->uninstall_network();
        
      } else {
        
        $this->uninstall_single();
        
      }
      
    }
    
    
    /**
     * uninstall network wide
     */
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
    
    
    /**
     * uninstall for a single blog
     */
    function uninstall_single() {
      
      foreach ( $this->settings as $key => $value) {
        
        delete_option( $key );
        
      }
      
    }
    
    
    /**
     * functions for theme usage
     */
    
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
        
        $title = get_the_title( $this->get_page_id() );
        
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
        
        $content = apply_filters( 'the_content', get_post_field( 'post_content', $this->get_page_id() ) );
        
      }
      
      return $content;
      
    }
    
    // print content - native theme support
    function pp_404_the_content() {
      
      echo $this->pp_404_get_the_content();
      
    }
    
  }
  
}

?>