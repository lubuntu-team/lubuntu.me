<script type="text/javascript">
	jQuery(document).ready(function(){
		postboxes.add_postbox_toggles(pagenow);
	});
</script>
<?php 
	$ISOCountryCode = $WP_Statistics->get_country_codes();
	include_once( dirname( __FILE__ ) . '/widgets/top.visitors.php' );
?>
<div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2><?php _e('Top 100 Visitors Today', 'wp_statistics'); ?></h2>
<?php 
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style("jquery-ui-smoothness-css", $WP_Statistics->plugin_url . "assets/css/jquery-ui-smoothness' . WP_STATISTICS_MIN_EXT . '.css");
	wp_enqueue_style("jquery-ui-smoothness-css");
	
	$current = 0;

	$statsdate = $WP_Statistics->Current_Date('m/d/Y', '-' . $current);
	
	if( array_key_exists( 'statsdate', $_GET ) ) { $statsdate = $_GET['statsdate']; } 

	echo '<br><form method="get">' . "\r\n";
		
	echo ' ' . __('Date', 'wp_statistics' ) . ': ';

	echo '<input type="hidden" name="page" value="' . WP_STATISTICS_TOP_VISITORS_PAGE . '">' . "\r\n";
	echo '<input type="text" size="10" name="statsdate" id="statsdate" value="' . htmlentities( $statsdate, ENT_QUOTES ) . '" placeholder="' . __('MM/DD/YYYY', 'wp_statistics') .'"> <input type="submit" value="'.__('Go', 'wp_statistics').'" class="button-primary">' . "\r\n";

	echo '</form>' . "\r\n";
	
	echo '<script>jQuery(function() { jQuery( "#statsdate" ).datepicker(); } );</script>' . "\r\n";
	
?>
	<div class="postbox-container" id="last-log" style="width: 100%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="<?php _e('Click to toggle', 'wp_statistics'); ?>"><br /></div>
					<h3 class="hndle"><span><?php _e('Top Visitors', 'wp_statistics'); ?></span></h3>
					<div class="inside">
						
						<?php wp_statistics_generate_top_visitors_postbox_content($ISOCountryCode, $statsdate, 100, false); ?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>