<script type="text/javascript">
	jQuery(document).ready(function(){
		postboxes.add_postbox_toggles(pagenow);
	});
</script>
<?php
	if( array_key_exists( 'page-uri', $_GET ) ) { $pageuri = $_GET['page-uri']; } else { $pageuri = null; }
	if( array_key_exists( 'page-id', $_GET ) ) { $pageid = (int) $_GET['page-id']; } else { $pageid = null; }

	if( $pageuri && !$pageid ) { $pageid = wp_statistics_uri_to_id( $pageuri ); }
	
	$post = get_post($pageid);
	if( is_object($post) ) { $title = $post->post_title; } else { $title = ""; }
	
	$urlfields = "&page-id={$pageid}";
	if( $pageuri ) { $urlfields .= "&page-uri={$pageuri}"; }
	
	$daysToDisplay = 20; 
	if( array_key_exists('hitdays',$_GET) ) { $daysToDisplay = intval($_GET['hitdays']); }
	
	if( array_key_exists('rangestart', $_GET ) ) { $rangestart = $_GET['rangestart']; } else { $rangestart = ''; }
	if( array_key_exists('rangeend', $_GET ) ) { $rangeend = $_GET['rangeend']; } else { $rangeend = ''; }
?>
<div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2><?php echo __('Page Trend for Post ID', 'wp_statistics') . ' ' .  $pageid . ' - ' . $title; ?></h2>

	<?php wp_statistics_date_range_selector( WP_STATISTICS_PAGES_PAGE, $daysToDisplay, null, null, $urlfields ); ?>

	<div class="postbox-container" id="last-log">
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="<?php _e('Click to toggle', 'wp_statistics'); ?>"><br /></div>
					<h3 class="hndle"><span><?php _e('Page Trend', 'wp_statistics'); ?></span></h3>
					<div class="inside">
						<?php include_once( dirname( __FILE__ ) . '/widgets/page.php'); wp_statistics_generate_page_postbox_content( $pageuri, $pageid, $daysToDisplay, null, $rangestart, $rangeend ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>