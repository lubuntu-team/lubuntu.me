<?php

/**
 * Class WP_Statistics_Shortcode
 */
class WP_Statistics_Shortcode {

	/**
	 * @param $atts
	 *
	 * WP-Statistics shortcode is in the format of:
	 * [wpstatistics stat=xxx time=xxxx provider=xxxx format=xxxxxx id=xxx]
	 * Where:
	 * stat = the statistic you want.
	 * time = is the timeframe, strtotime() (http://php.net/manual/en/datetime.formats.php) will be used to calculate
	 * it. provider = the search provider to get stats on. format = i18n, english, none. id = the page/post id to get
	 * stats on.
	 *
	 * @return array|false|int|null|object|string|void
	 */
	static function shortcodes( $atts ) {

		if ( ! is_array( $atts ) ) {
			return;
		}
		if ( ! array_key_exists( 'stat', $atts ) ) {
			return;
		}

		if ( ! array_key_exists( 'time', $atts ) ) {
			$atts['time'] = null;
		}
		if ( ! array_key_exists( 'provider', $atts ) ) {
			$atts['provider'] = 'all';
		}
		if ( ! array_key_exists( 'format', $atts ) ) {
			$atts['format'] = null;
		}
		if ( ! array_key_exists( 'id', $atts ) ) {
			$atts['id'] = - 1;
		}

		$formatnumber = array_key_exists( 'format', $atts );

		switch ( $atts['stat'] ) {
			case 'usersonline':
				$result = wp_statistics_useronline();
				break;

			case 'visits':
				$result = wp_statistics_visit( $atts['time'] );
				break;

			case 'visitors':
				$result = wp_statistics_visitor( $atts['time'], null, true );
				break;

			case 'pagevisits':
				$result = wp_statistics_pages( $atts['time'], null, $atts['id'] );
				break;

			case 'searches':
				$result = wp_statistics_searchengine( $atts['provider'], $atts['time'] );
				break;

			case 'postcount':
				$result = wp_statistics_countposts();
				break;

			case 'pagecount':
				$result = wp_statistics_countpages();
				break;

			case 'commentcount':
				$result = wp_statistics_countcomment();
				break;

			case 'spamcount':
				$result = wp_statistics_countspam();
				break;

			case 'usercount':
				$result = wp_statistics_countusers();
				break;

			case 'postaverage':
				$result = wp_statistics_average_post();
				break;

			case 'commentaverage':
				$result = wp_statistics_average_comment();
				break;

			case 'useraverage':
				$result = wp_statistics_average_registeruser();
				break;

			case 'lpd':
				$result       = wp_statistics_lastpostdate();
				$formatnumber = false;
				break;
		}

		if ( $formatnumber ) {
			switch ( strtolower( $atts['format'] ) ) {
				case 'i18n':
					$result = number_format_i18n( $result );

					break;
				case 'english':
					$result = number_format( $result );

					break;
			}
		}

		return $result;
	}

	/**
	 *
	 */
	static function shortcake() {
		// ShortCake support if loaded.
		if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			$se_list = wp_statistics_searchengine_list();

			$se_options = array( '' => 'None' );

			foreach ( $se_list as $se ) {
				$se_options[ $se['tag'] ] = $se['translated'];
			}

			shortcode_ui_register_for_shortcode(
				'wpstatistics',
				array(

					// Display label. String. Required.
					'label'         => 'WP Statistics',

					// Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
					'listItemImage' => '<img src="' .
					                   WP_Statistics::$reg['plugin-url'] .
					                   'assets/images/logo-250.png" width="128" height="128">',

					// Available shortcode attributes and default values. Required. Array.
					// Attribute model expects 'attr', 'type' and 'label'
					// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
					'attrs'         => array(
						array(
							'label'       => __( 'Statistic', 'wp-statistics' ),
							'attr'        => 'stat',
							'type'        => 'select',
							'description' => __( 'Select the statistic you wish to display.', 'wp-statistics' ),
							'value'       => 'usersonline',
							'options'     => array(
								'usersonline'    => __( 'Online Users', 'wp-statistics' ),
								'visits'         => __( 'Visits', 'wp-statistics' ),
								'visitors'       => __( 'Visitors', 'wp-statistics' ),
								'pagevisits'     => __( 'Page Visits', 'wp-statistics' ),
								'searches'       => __( 'Searches', 'wp-statistics' ),
								'postcount'      => __( 'Post Count', 'wp-statistics' ),
								'pagecount'      => __( 'Page Count', 'wp-statistics' ),
								'commentcount'   => __( 'Comment Count', 'wp-statistics' ),
								'spamcount'      => __( 'Spam Count', 'wp-statistics' ),
								'usercount'      => __( 'User Count', 'wp-statistics' ),
								'postaverage'    => __( 'Post Average', 'wp-statistics' ),
								'commentaverage' => __( 'Comment Average', 'wp-statistics' ),
								'useraverage'    => __( 'User Average', 'wp-statistics' ),
								'lpd'            => __( 'Last Post Date', 'wp-statistics' ),
							),
						),
						array(
							'label'       => __( 'Time Frame', 'wp-statistics' ),
							'attr'        => 'time',
							'type'        => 'url',
							'description' => __(
								'The time frame to get the statistic for, strtotime() (http://php.net/manual/en/datetime.formats.php) will be used to calculate it. Use "total" to get all recorded dates.',
								'wp-statistics'
							),
							'meta'        => array( 'size' => '10' ),
						),
						array(
							'label'       => __( 'Search Provider', 'wp-statistics' ),
							'attr'        => 'provider',
							'type'        => 'select',
							'description' => __( 'The search provider to get statistics on.', 'wp-statistics' ),
							'options'     => $se_options,
						),
						array(
							'label'       => __( 'Number Format', 'wp-statistics' ),
							'attr'        => 'format',
							'type'        => 'select',
							'description' => __(
								'The format to display numbers in: i18n, english, none.',
								'wp-statistics'
							),
							'value'       => 'none',
							'options'     => array(
								'none'    => __( 'None', 'wp-statistics' ),
								'english' => __( 'English', 'wp-statistics' ),
								'i18n'    => __( 'International', 'wp-statistics' ),
							),
						),
						array(
							'label'       => __( 'Post/Page ID', 'wp-statistics' ),
							'attr'        => 'id',
							'type'        => 'number',
							'description' => __( 'The post/page id to get page statistics on.', 'wp-statistics' ),
							'meta'        => array( 'size' => '5' ),
						),
					),
				)
			);
		}

	}
}
