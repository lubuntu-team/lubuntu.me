<script type="text/javascript">
	jQuery(document).ready(function(){
		postboxes.add_postbox_toggles(pagenow);
	});
</script>
<?php
	$ISOCountryCode = $WP_Statistics->get_country_codes();
	
	$_var = 'agent';
	$_get = '%';
	$title = 'All';
	
	if( array_key_exists( 'agent', $_GET ) ) {
		$_var = 'agent';
		$_get = '%' . $_GET['agent'] . '%';
		$title = htmlentities( $_GET['agent'], ENT_QUOTES );
	}
	
	if( array_key_exists( 'ip', $_GET ) ) {
		$_var = 'ip';
		$_get = '%' . $_GET['ip'] . '%';
		$title = htmlentities( $_GET['ip'], ENT_QUOTES );
	}
		
	$total_visitor = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}statistics_visitor`");

	if( $_get != '%' ) {
		$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `{$wpdb->prefix}statistics_visitor` WHERE `{$_var}` LIKE %s", $_get));
	} else {
		$total = $total_visitor;
	}
	
?>
<div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2><?php _e('Recent Visitors', 'wp_statistics'); ?></h2>
	<ul class="subsubsub">
		<li class="all"><a <?php if($_get == '%') { echo 'class="current"'; } ?>href="?page=<?php echo WP_STATISTICS_VISITORS_PAGE; ?>"><?php _e('All', 'wp_statistics'); ?> <span class="count">(<?php echo $total_visitor; ?>)</span></a></li>
		<?php
			if( isset( $_var ) ) {
				$spacer = " | ";
				
				if($_var == 'agent') {
					$Browsers = wp_statistics_ua_list();
					$i = 0;
					$Total = count( $Browsers );
					
					foreach( $Browsers as $Browser ) {
						if($Browser == null) continue;
						
						$i++;
						if($title == $Browser) { $current = 'class="current" '; } else { $current = ""; }
						if( $i == $Total ) { $spacer = ""; }
						echo $spacer . "<li><a " . $current . "href='?page=" . WP_STATISTICS_VISITORS_PAGE . "&agent=" . $Browser . "'> " . __($Browser, 'wp_statistics') ." <span class='count'>(" . number_format_i18n(wp_statistics_useragent($Browser)) .")</span></a></li>";
					}
				} else {
					if($_get != '%') { $current = 'class="current" '; } else { $current = ""; }
					echo $spacer . "<li><a {$current} href='?page=" . WP_STATISTICS_VISITORS_PAGE . "&{$_var}={$_get}'>{$title} <span class='count'>({$total})</span></a></li>";
				}
			}
		?>
	</ul>
	<div class="postbox-container" id="last-log">
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="<?php _e('Click to toggle', 'wp_statistics'); ?>"><br /></div>
					<h3 class="hndle"><span><?php _e('Recent Visitor Statistics', 'wp_statistics'); if($_get != '%') { echo ' [' . __('Filtered by', 'wp_statistics') . ': ' . $title . ']'; } ?></span></h3>
					
					<div class="inside">
							<?php
								// Instantiate pagination object with appropriate arguments
								$pagesPerSection = 10;
								$options = array(25, "All");
								$stylePageOff = "pageOff";
								$stylePageOn = "pageOn";
								$styleErrors = "paginationErrors";
								$styleSelect = "paginationSelect";

								$Pagination = new WP_Statistics_Pagination($total, $pagesPerSection, $options, false, $stylePageOff, $stylePageOn, $styleErrors, $styleSelect);
								
								$start = $Pagination->getEntryStart();
								$end = $Pagination->getEntryEnd();

								// Retrieve MySQL data
								if( $_get != '%' ) {
									$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}statistics_visitor` WHERE `{$_var}` LIKE %s ORDER BY `{$wpdb->prefix}statistics_visitor`.`ID` DESC  LIMIT {$start}, {$end}", $_get));
								} else {
									$result = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}statistics_visitor` ORDER BY `{$wpdb->prefix}statistics_visitor`.`ID` DESC  LIMIT {$start}, {$end}");
								}
								
								// Check to see if User Agent logging is enabled.
								$DisplayUA = $WP_Statistics->get_option( "store_ua" );
								
								echo "<div class='log-latest'>";
								
								$dash_icon = wp_statistics_icons('dashicons-visibility', 'visibility');
								
								foreach($result as $items) {
									if( substr( $items->ip, 0, 6 ) == '#hash#' ) { 
										$ip_string = __('#hash#', 'wp_statistics'); 
										$map_string = "";
									} 
									else { 
										$ip_string = "<a href='?page=" . WP_STATISTICS_VISITORS_PAGE . "&ip={$items->ip}'>{$dash_icon}{$items->ip}</a>"; 
										$map_string = "<a class='show-map' href='http://www.geoiptool.com/en/?IP={$items->ip}' target='_blank' title='".__('Map', 'wp_statistics')."'>".wp_statistics_icons('dashicons-location-alt', 'map')."</a>";
									}

									echo "<div class='log-item'>";
									echo "<div class='log-referred'>{$ip_string}</div>";
									echo "<div class='log-ip'>" . date(get_option('date_format'), strtotime($items->last_counter)) . "</div>";
									echo "<div class='clear'></div>";
									echo "<div class='log-url'>";
									echo $map_string;
									
									if($WP_Statistics->get_option('geoip')) {
										echo "<img src='".plugins_url('wp-statistics/assets/images/flags/' . $items->location . '.png')."' title='{$ISOCountryCode[$items->location]}' class='log-tools'/>";
									}
									
									if( array_search( strtolower( $items->agent ), array( "chrome", "firefox", "msie", "opera", "safari" ) ) !== FALSE ){
										$agent = "<img src='".plugins_url('wp-statistics/assets/images/').$items->agent.".png' class='log-tools' title='{$items->agent}'/>";
									} else {
										$agent = wp_statistics_icons('dashicons-editor-help', 'unknown');
									}
									
									echo "<a href='?page=" . WP_STATISTICS_VISITORS_PAGE . "&agent={$items->agent}'>{$agent}</a>";
									
									echo "<a href='" . htmlentities($items->referred,ENT_QUOTES) . "' title='" . htmlentities($items->referred,ENT_QUOTES) . "'>" . wp_statistics_icons('dashicons-admin-links', 'link') . " " . htmlentities($items->referred,ENT_QUOTES) . "</a></div>";
									echo "</div>";
								}
								
								echo "</div>";
							?>
					</div>
				</div>
				
				<div class="pagination-log">
					<?php echo $Pagination->display(); ?>
					<p id="result-log"><?php echo ' ' . __('Page', 'wp_statistics') . ' ' . $Pagination->getCurrentPage() . ' ' . __('From', 'wp_statistics') . ' ' . $Pagination->getTotalPages(); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>