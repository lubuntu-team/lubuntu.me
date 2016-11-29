<?php 
if( $wps_nonce_valid ) {

	$wps_option_list = array('wps_schedule_dbmaint', 'wps_schedule_dbmaint_days', 'wps_schedule_dbmaint_visitor', 'wps_schedule_dbmaint_visitor_hits');
	
	foreach( $wps_option_list as $option ) {
		$new_option = str_replace( "wps_", "", $option );
		if( array_key_exists( $option, $_POST ) ) { $value = $_POST[$option]; } else { $value = ''; }
		$WP_Statistics->store_option($new_option, $value);
	}
}

?>
<script type="text/javascript">
	function DBMaintWarning() {
		var checkbox = jQuery('#wps_schedule_dbmaint');
		
		if( checkbox.attr('checked') == 'checked' )
			{
			if(!confirm('<?php _e('This will permanently delete data from the database each day, are you sure you want to enable this option?', 'wp_statistics'); ?>'))
				checkbox.attr('checked', false);
			}
		

	}
</script>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" colspan="2"><h3><?php _e('Purge Old Data Daily', 'wp_statistics'); ?></h3></th>
		</tr>

		<tr valign="top">
			<th scope="row">
				<label for="wps_schedule_dbmaint"><?php _e('Enabled', 'wp_statistics'); ?>:</label>
			</th>
			
			<td>
				<input id="wps_schedule_dbmaint" type="checkbox" name="wps_schedule_dbmaint" <?php echo $WP_Statistics->get_option('schedule_dbmaint')==true? "checked='checked'":'';?> onclick='DBMaintWarning();'>
				<label for="wps_schedule_dbmaint"><?php _e('Active', 'wp_statistics'); ?></label>
				<p class="description"><?php _e('A WP Cron job will be run daily to purge any data older than a set number of days.', 'wp_statistics'); ?></p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">
				<label for="wps_schedule_dbmaint_days"><?php _e('Purge data older than', 'wp_statistics'); ?>:</label>
			</th>
			
			<td>
				<input type="text" class="small-text code" id="wps_schedule_dbmaint_days" name="wps_schedule_dbmaint_days" value="<?php echo htmlentities( $WP_Statistics->get_option('schedule_dbmaint_days', "365"), ENT_QUOTES ); ?>"/>
				<?php _e('Days', 'wp_statistics'); ?>
				<p class="description"><?php echo __('The number of days to keep statistics for.  Minimum value is 30 days.  Invalid values will disable the daily maintenance.', 'wp_statistics'); ?></p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row" colspan="2"><h3><?php _e('Purge High Hit Count Visitors Daily', 'wp_statistics'); ?></h3></th>
		</tr>
		
		<tr valign="top">
			<th scope="row">
				<label for="wps_schedule_dbmaint_visitor"><?php _e('Enabled', 'wp_statistics'); ?>:</label>
			</th>
			
			<td>
				<input id="wps_schedule_dbmaint_visitor" type="checkbox" name="wps_schedule_dbmaint_visitor" <?php echo $WP_Statistics->get_option('schedule_dbmaint_visitor')==true? "checked='checked'":'';?> onclick='DBMaintWarning();'>
				<label for="wps_schedule_dbmaint_visitor"><?php _e('Active', 'wp_statistics'); ?></label>
				<p class="description"><?php _e('A WP Cron job will be run daily to purge any users statistics data where the user has more than the defined number of hits in a day (aka they are probably a bot).', 'wp_statistics'); ?></p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">
				<label for="wps_schedule_dbmaint_visitor_hits"><?php _e('Purge visitors with more than', 'wp_statistics'); ?>:</label>
			</th>
			
			<td>
				<input type="text" class="small-text code" id="wps_schedule_dbmaint_visitor_hits" name="wps_schedule_dbmaint_visitor_hits" value="<?php echo htmlentities( $WP_Statistics->get_option('schedule_dbmaint_visitor_hits', '50'), ENT_QUOTES ); ?>"/>
				<?php _e('Hits', 'wp_statistics'); ?>
				<p class="description"><?php echo __('The number of hits required to delete the visitor.  Minimum value is 10 hits.  Invalid values will disable the daily maintenance.', 'wp_statistics'); ?></p>
			</td>
		</tr>

	</tbody>
</table>

<?php submit_button(__('Update', 'wp_statistics'), 'primary', 'submit'); ?>