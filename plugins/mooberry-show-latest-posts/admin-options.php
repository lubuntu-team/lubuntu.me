<?php

	$mbdslp_options = get_option('mbdslp_options');
	
	add_action('admin_menu', 'mbdslp_add_options_page');
	function mbdslp_add_options_page() {
		add_options_page(__('Show Latest Posts Options', 'mooberry-show-latest-posts'), __('Show Latest Posts', 'mooberry-show-latest-posts'), 'manage_options', 'mbdslp-show-latests-posts', 'mbdslp_options_page');
	}
	
	function mbdslp_options_page() {
	?>
		<div>
		<h2><?php _e('Mooberry Show Latest Posts Options', 'mooberry-show-latest-posts'); ?></h2>
		<form action="options.php" method="post">
		<?php settings_fields('mbdslp_options'); ?>
		<?php do_settings_sections('mbdslp_options'); ?>
		<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes', 'mooberry-show-latest-posts'); ?>" />
		</form></div>
		<?php
	}
		
	add_action('admin_init', 'mbdslp_options_init');
	function mbdslp_options_init(){
		register_setting( 'mbdslp_options', 'mbdslp_options', 'mbdslp_options_validate' );
		add_settings_section('mbdslp_options_main', '', 'mbdslp_main_settings_display', 'mbdslp_options');
		add_settings_field('mbdslp_title', __('Section Title' ,'mooberry-show-latest-posts'), 'mbdslp_options_title', 'mbdslp_options', 'mbdslp_options_main');
		// v1.2 added category option
		add_settings_field('mbdslp_category', __('Filter By Category', 'mooberry-show-latest-posts'), 'mbdslp_options_category', 'mbdslp_options', 'mbdslp_options_main');
		add_settings_field('mbdslp_number', __('Number of Posts to Display', 'mooberry-show-latest-posts'), 'mbdslp_options_number', 'mbdslp_options', 'mbdslp_options_main');
		add_settings_field('mbdslp_display', __('Display the Posts', 'mooberry-show-latest-posts'), 'mbdslp_options_display', 'mbdslp_options', 'mbdslp_options_main');
		add_settings_field('mbdslp_readmore', __('"Read More" Link Text', 'mooberry-show-latest-posts'), 'mbdslp_options_readmore', 'mbdslp_options', 'mbdslp_options_main');
		//v1.3 added number of words
		add_settings_field('mbdslp_words', __('Number of words in Excerpt', 'mooberry-show-latest-posts'), 'mbdslp_options_words', 'mbdslp_options', 'mbdslp_options_main');
	}
	
	function mbdslp_main_settings_display() {
		//echo '<p>Main description of this section here.</p>';
	}
	
	function mbdslp_options_title() {
		global $mbdslp_options;
		echo "<input id='mbdslp_title' name='mbdslp_options[mbdslp_title]' size='40' type='text' value='" . esc_attr($mbdslp_options['mbdslp_title']) . "' />";
	}
	
	// v1.2 added category option
	function mbdslp_options_category() {
		global $mbdslp_options;
		$args = array(
			'show_option_all'    => '',
			'show_option_none'   => 'Don\'t Filter',
			'option_none_value'  => '-1',
			'orderby'            => 'NAME', 
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => 1, 
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 1,
			'selected'           => esc_attr($mbdslp_options['mbdslp_category']),
			'hierarchical'       => 0, 
			'name'               => 'mbdslp_options[mbdslp_category]',
			'id'                 => 'mbdslp_category',
			'class'              => 'postform',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'category',
			'hide_if_empty'      => true,
			'value_field'	     => 'term_id',	
		);
		
		wp_dropdown_categories( $args );
		
		
	}
	
	function mbdslp_options_number() {
		global $mbdslp_options;
		echo "<input id='mbdslp_number' name='mbdslp_options[mbdslp_number]' size='3' type='number' value='" . esc_attr($mbdslp_options['mbdslp_number']) . "' />";
	}
	
	// v1.3 Added number of words
	function mbdslp_options_words() {
		global $mbdslp_options;
		echo "<input id='mbdslp_words' name='mbdslp_options[mbdslp_words]' size='3' type='number' value='" . esc_attr($mbdslp_options['mbdslp_words']) . "' />";
	}
	
	function mbdslp_options_display() {
		global $mbdslp_options;
		?>
		<select id='mbdslp_display' name='mbdslp_options[mbdslp_display]'>
		<option value='horizontal' <?php echo ($mbdslp_options['mbdslp_display']=='horizontal' ? "selected" : "a") ?>><?php _e('Horizontally', 'mooberry-show-latest-posts'); ?></option>
		<option value='vertical' <?php echo ($mbdslp_options['mbdslp_display']=='vertical' ? "selected" : "b") ?>><?php _e('Vertically', 'mooberry-show-latest-posts'); ?></option>
		</select>
		<?php
	}
	
	function mbdslp_options_readmore() {
		global $mbdslp_options;
		echo "<input id='mbdslp_readmore' name='mbdslp_options[mbdslp_readmore]' size='40' type='text' value='" . esc_attr($mbdslp_options['mbdslp_readmore']) . "' />";
	}
	
	
	function mbdslp_options_validate( $input ) {
		$options = get_option('mbdslp_options');
		// explicitly set only the options we want. anything else sent in with $input is ignored
		$options['mbdslp_title'] = $input['mbdslp_title'];
		// v1.2 added category option
		$options['mbdslp_category'] = $input['mbdslp_category'];
		$options['mbdslp_number'] = $input['mbdslp_number'];
		$options['mbdslp_display'] = $input['mbdslp_display'];
		$options['mbdslp_readmore'] = $input['mbdslp_readmore'];
		//v1.3 added words
		$options['mbdslp_words'] = $input['mbdslp_words'];
		
		$options = mbdslp_options_set_defaults( $options );
		return $options;
	}
	
	function mbdslp_options_set_defaults( $options ) {
	
		// if title doesn't exist, add the default.
		// if it does exist but is blank, we'll leave it blank
		if (!array_key_exists('mbdslp_title', $options)) {
			$options['mbdslp_title'] = __('Latest Posts', 'mooberry-show-latest-posts');
		}
		
		// v1.2 added category option
		// set category to Don't Filter if doesn't exist
		if (!array_key_exists('mbdslp_category', $options)) {
			$options['mbdslp_category'] = -1;
		}
		
		// validate to a number and default to 3 if necessary
		if (!array_key_exists('mbdslp_number', $options) || !preg_match('/^[0-9]+$/', $options['mbdslp_number'])) {
			$options['mbdslp_number'] = 3;
		}
		// make sure vertical or horizontal
		if (!array_key_exists('mbdslp_display', $options) || ($options['mbdslp_display'] != 'vertical' && $options['mbdslp_display'] != 'horizontal')) {
			$options['mbdslp_display'] = 'vertical';
		}
		
		// set default text if blank
		if (!array_key_exists('mbdslp_readmore', $options) || trim($options['mbdslp_readmore']) == '' ) {
			$options['mbdslp_readmore'] = __('READ MORE', 'mooberry-show-latest-posts');
		}
		
		// v1.3 added words option
		if (!array_key_exists('mbdslp_words', $options) || trim($options['mbdslp_words']) == '' || !preg_match('/^[0-9]+$/', $options['mbdslp_words'])) {
			$options['mbdslp_words'] = 55;
		}
		
		return $options;
	}
	