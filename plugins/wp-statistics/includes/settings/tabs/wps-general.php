<?php
$selist                       = wp_statistics_searchengine_list( true );
$permalink                    = get_option( 'permalink_structure' );
$disable_strip_uri_parameters = false;

if ( $permalink == '' || strpos( $permalink, '?' ) !== false ) {
	$disable_strip_uri_parameters = true;
}

if ( $wps_nonce_valid ) {
	foreach ( $selist as $se ) {
		$se_post = 'wps_disable_se_' . $se['tag'];

		if ( array_key_exists( $se_post, $_POST ) ) {
			$value = $_POST[ $se_post ];
		} else {
			$value = '';
		}
		$new_option = str_replace( "wps_", "", $se_post );
		$WP_Statistics->store_option( $new_option, $value );
	}

	$wps_option_list = array(
		'wps_useronline',
		'wps_visits',
		'wps_visitors',
		'wps_pages',
		'wps_track_all_pages',
		'wps_disable_column',
		'wps_show_hits',
		'wps_display_hits_position',
		'wps_check_online',
		'wps_menu_bar',
		'wps_coefficient',
		'wps_chart_totals',
		'wps_store_ua',
		'wps_hide_notices',
		'wps_hash_ips',
		'wps_all_online',
		'wps_strip_uri_parameters',
		'wps_addsearchwords',
	);

	// If the IP hash's are enabled, disable storing the complete user agent.
	if ( array_key_exists( 'wps_hash_ips', $_POST ) ) {
		$_POST['wps_store_ua'] = '';
	}

	// We need to check the permalink format for the strip_uri_parameters option, if the permalink is the default or contains uri parameters, we can't strip them.
	if ( $disable_strip_uri_parameters ) {
		$_POST['wps_strip_uri_parameters'] = '';
	}

	foreach ( $wps_option_list as $option ) {
		if ( array_key_exists( $option, $_POST ) ) {
			$value = $_POST[ $option ];
		} else {
			$value = '';
		}
		$new_option = str_replace( "wps_", "", $option );
		$WP_Statistics->store_option( $new_option, $value );
	}
}
?>
    <script type="text/javascript">
        function ToggleShowHitsOptions() {
            jQuery('[id^="wps_show_hits_option"]').fadeToggle();
        }
    </script>

    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'IP Addresses', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="useronline"><?php _e( 'Hash IP Addresses', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="hash_ips" type="checkbox" value="1"
                       name="wps_hash_ips" <?php echo $WP_Statistics->get_option( 'hash_ips' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="hash_ips"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e(
						'This feature will not store IP addresses in the database but instead used a unique hash.  The "Store entire user agent string" setting will be disabled if this is selected.  You will not be able to recover the IP addresses in the future to recover location information if this is enabled.',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Online Users', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="useronline"><?php _e( 'Online User', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="useronline" type="checkbox" value="1"
                       name="wps_useronline" <?php echo $WP_Statistics->get_option( 'useronline' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="useronline"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="check_online"><?php _e( 'Check for online users every', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input type="text" class="small-text code" id="check_online" name="wps_check_online"
                       value="<?php echo htmlentities( $WP_Statistics->get_option( 'check_online' ), ENT_QUOTES ); ?>"/>
				<?php _e( 'Seconds', 'wp-statistics' ); ?>
                <p class="description"><?php echo sprintf(
						__( 'Time for the check accurate online user in the site. Now: %s Seconds', 'wp-statistics' ),
						$WP_Statistics->get_option( 'check_online' )
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="useronline"><?php _e( 'Record all user', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="allonline" type="checkbox" value="1"
                       name="wps_all_online" <?php echo $WP_Statistics->get_option( 'all_online' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="allonline"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e(
						'Ignores the exclusion settings and records all users that are online (including self referrals and robots).  Should only be used for troubleshooting.',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Visits', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="visits"><?php _e( 'Visits', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="visits" type="checkbox" value="1" name="wps_visits" <?php echo $WP_Statistics->get_option(
					'visits'
				) == true ? "checked='checked'" : ''; ?>>
                <label for="visits"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Visitors', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="visitors"><?php _e( 'Visitors', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="visitors" type="checkbox" value="1"
                       name="wps_visitors" <?php echo $WP_Statistics->get_option( 'visitors' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="visitors"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="visitors"><?php _e( 'Store entire user agent string', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="store_ua" type="checkbox" value="1"
                       name="wps_store_ua" <?php echo $WP_Statistics->get_option( 'store_ua' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="store_ua"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Only enabled for debugging', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="coefficient"><?php _e( 'Coefficient per visitor', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input type="text" class="small-text code" id="coefficient" name="wps_coefficient"
                       value="<?php echo htmlentities( $WP_Statistics->get_option( 'coefficient' ), ENT_QUOTES ); ?>"/>

                <p class="description"><?php echo sprintf(
						__( 'For each visit to account for several hits. Currently %s.', 'wp-statistics' ),
						$WP_Statistics->get_option( 'coefficient' )
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Pages and Posts', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="pages"><?php _e( 'Pages', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="pages" type="checkbox" value="1" name="wps_pages" <?php echo $WP_Statistics->get_option(
					'pages'
				) == true ? "checked='checked'" : ''; ?>>
                <label for="pages"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="all_pages"><?php _e( 'Track all pages', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="all_pages" type="checkbox" value="1"
                       name="wps_track_all_pages" <?php echo $WP_Statistics->get_option( 'track_all_pages' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="all_pages"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

		<?php
		if ( ! $disable_strip_uri_parameters ) {
			?>
            <tr valign="top">
                <th scope="row">
                    <label for="strip_uri_parameters"><?php _e( 'Strip parameters from URI', 'wp-statistics' ); ?>
                        :</label>
                </th>

                <td>
                    <input id="strip_uri_parameters" type="checkbox" value="1"
                           name="wps_strip_uri_parameters" <?php echo $WP_Statistics->get_option(
						'strip_uri_parameters'
					) == true ? "checked='checked'" : ''; ?>>
                    <label for="strip_uri_parameters"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                    <p class="description"><?php _e(
							'This will remove anything after the ? in a URL.',
							'wp-statistics'
						); ?></p>
                </td>
            </tr>
			<?php
		}
		?>
        <tr valign="top">
            <th scope="row">
                <label for="disable_column"><?php _e( 'Disable hits column in post/pages list', 'wp-statistics' ); ?>
                    :</label>
            </th>

            <td>
                <input id="disable_column" type="checkbox" value="1"
                       name="wps_disable_column" <?php echo $WP_Statistics->get_option( 'disable_column' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="disable_column"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable this feature', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="show_hits"><?php _e( 'Show hits in posts/pages in the site', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="show_hits" type="checkbox" value="1"
                       name="wps_show_hits" <?php echo $WP_Statistics->get_option( 'show_hits' ) == true
					? "checked='checked'" : ''; ?> onClick='ToggleShowHitsOptions();'>
                <label for="show_hits"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e( 'Enable or disable show hits in content', 'wp-statistics' ); ?></p>
            </td>
        </tr>

		<?php if ( $WP_Statistics->get_option( 'show_hits' ) ) {
			$hidden = "";
		} else {
			$hidden = " style='display: none;'";
		} ?>
        <tr valign="top"<?php echo $hidden; ?> id='wps_show_hits_option'>
            <td scope="row" style="vertical-align: top;">
                <label for="display_hits_position"><?php _e( 'Display position', 'wp-statistics' ); ?>:</label>
            </td>

            <td>
                <select name="wps_display_hits_position" id="display_hits_position">
                    <option value="0" <?php selected( $WP_Statistics->get_option( 'display_hits_position' ), '0' ); ?>><?php _e(
							'Please select',
							'wp-statistics'
						); ?></option>
                    <option value="before_content" <?php selected( $WP_Statistics->get_option( 'display_hits_position' ), 'before_content' ); ?>><?php _e(
							'Before Content',
							'wp-statistics'
						); ?></option>

                    <option value="after_content" <?php selected( $WP_Statistics->get_option( 'display_hits_position' ), 'after_content' ); ?>><?php _e(
							'After Content',
							'wp-statistics'
						); ?></option>
                </select>

                <p class="description"><?php _e(
						'Choose the position to show Hits.',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Miscellaneous', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="menu-bar"><?php _e( 'Show stats in menu bar', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <select name="wps_menu_bar" id="menu-bar">
                    <option value="0" <?php selected( $WP_Statistics->get_option( 'menu_bar' ), '0' ); ?>><?php _e(
							'No',
							'wp-statistics'
						); ?></option>
                    <option value="1" <?php selected( $WP_Statistics->get_option( 'menu_bar' ), '1' ); ?>><?php _e(
							'Yes',
							'wp-statistics'
						); ?></option>
                </select>

                <p class="description"><?php _e( 'Show stats in admin menu bar', 'wp-statistics' ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="hide_notices"><?php _e( 'Hide admin notices about non active features', 'wp-statistics' ); ?>
                    :</label>
            </th>

            <td>
                <input id="hide_notices" type="checkbox" value="1"
                       name="wps_hide_notices" <?php echo $WP_Statistics->get_option( 'hide_notices' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="store_ua"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e(
						'By default WP Statistics displays an alert if any of the core features are disabled on every admin page, this option will disable these notices.',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Search Engines', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="hide_notices"><?php _e( 'Add page title to empty search words', 'wp-statistics' ); ?>
                    :</label>
            </th>

            <td>
                <input id="addsearchwords" type="checkbox" value="1"
                       name="wps_addsearchwords" <?php echo $WP_Statistics->get_option( 'addsearchwords' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="addsearchwords"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e(
						'If a search engine is identified as the referrer but it does not include the search query this option will substitute the page title in quotes preceded by "~:" as the search query to help identify what the user may have been searching for.',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" colspan="2">
                <p class="description"><?php _e(
						'Disabling all search engines is not allowed, doing so will result in all search engines being active.',
						'wp-statistics'
					); ?></p>
            </th>
        </tr>
		<?php
		$se_option_list = '';

		foreach ( $selist as $se ) {
			$option_name    = 'wps_disable_se_' . $se['tag'];
			$store_name     = 'disable_se_' . $se['tag'];
			$se_option_list .= $option_name . ',';
			?>

            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo $option_name; ?>"><?php _e( $se['name'], 'wp-statistics' ); ?>:</label>
                </th>
                <td>
                    <input id="<?php echo $option_name; ?>" type="checkbox" value="1"
                           name="<?php echo $option_name; ?>" <?php echo $WP_Statistics->get_option( $store_name ) == true
						? "checked='checked'" : ''; ?>><label for="<?php echo $option_name; ?>"><?php _e(
							'Disable',
							'wp-statistics'
						); ?></label>

                    <p class="description"><?php echo sprintf(
							__( 'Disable %s from data collection and reporting.', 'wp-statistics' ),
							$se['name']
						); ?></p>
                </td>
            </tr>
		<?php } ?>

        <tr valign="top">
            <th scope="row" colspan="2"><h3><?php _e( 'Charts', 'wp-statistics' ); ?></h3></th>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="chart-totals"><?php _e( 'Include totals', 'wp-statistics' ); ?>:</label>
            </th>

            <td>
                <input id="chart-totals" type="checkbox" value="1"
                       name="wps_chart_totals" <?php echo $WP_Statistics->get_option( 'chart_totals' ) == true
					? "checked='checked'" : ''; ?>>
                <label for="chart-totals"><?php _e( 'Enable', 'wp-statistics' ); ?></label>

                <p class="description"><?php _e(
						'Add a total line to charts with multiple values, like the search engine referrals',
						'wp-statistics'
					); ?></p>
            </td>
        </tr>

        </tbody>
    </table>

<?php submit_button( __( 'Update', 'wp-statistics' ), 'primary', 'submit' );