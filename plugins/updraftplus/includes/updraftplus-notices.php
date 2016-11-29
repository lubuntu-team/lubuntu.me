<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

class UpdraftPlus_Notices {

	private static $initialized = false;

	private static $notices_content = array();

	private static function populate_notices_content() {
	
		// These variables are just short-hands to be used below.
		$dashboard_top = array('top');
		$dashboard_top_or_report = array('top', 'report', 'report-plain');
		$dashboard_bottom_or_report = array('bottom', 'report', 'report-plain');
		$anywhere = array('top', 'bottom', 'report', 'report-plain');
		$autobackup = array('autobackup');
		$autobackup_bottom_or_report = array('autobackup', 'bottom', 'report', 'report-plain');
	
		return array(
			1 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('support', 'updraftplus'), 
				'text' => __('Enjoy professional, fast, and friendly help whenever you need it.', 'updraftplus'),
				'image' => 'notices/support.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			2 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('UpdraftVault storage', 'updraftplus'),
				'text' => __('The ultimately secure and convenient place to store your backups.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/landing/vault',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			3 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('enhanced remote storage options', 'updraftplus'), 
				'text' => __('Enhanced storage options for Dropbox, Google Drive and S3. Plus many more options.', 'updraftplus'),
				'image' => 'notices/multiplestorage_destinations.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			4 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('advanced options', 'updraftplus'),
				'text' => __('Secure multisite installation, advanced reporting and much more.', 'updraftplus'),
				'image' => 'notices/reporting.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			5 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('secure your backups', 'updraftplus'), 
				'text' => __('Add SFTP to send your data securely, lock settings and encrypt your database backups for extra security.', 'updraftplus'),
				'image' => 'notices/locksettings.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			6 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('easily migrate or clone your site in minutes', 'updraftplus'), 
				'text' => __('Copy your site to another domain directly. Includes find-and-replace tool for database references.', 'updraftplus'),
				'image' => 'notices/migrator.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $anywhere,
			),
			7 => array(
				'prefix' => '',
				'title' => __('Introducing UpdraftCentral', 'updraftplus'), 
				'text' => __('UpdraftCentral is a highly efficient way to manage, update and backup multiple websites from one place.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftcentral.com',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $dashboard_top_or_report,
			),
			8 => array(
				'prefix' => '',
				'title' => __('Like UpdraftPlus and can spare one minute?', 'updraftplus'), 
				'text' => __('Please help UpdraftPlus by giving a positive review at wordpress.org.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://wordpress.org/support/plugin/updraftplus/reviews/?rate=5#new-post',
				'button_meta' => 'review',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $anywhere,
			),
			9 => array(
				'prefix' => '',
				'title' => __('Do you use UpdraftPlus on multiple sites?', 'updraftplus'), 
				'text' => __('Control all your WordPress installations from one place using UpdraftCentral remote site management!', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftcentral.com',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $anywhere,
			),
			'translation_needed' => array(
				'prefix' => '',
				'title' => 'Can you translate? Want to improve UpdraftPlus for speakers of your language?',
				'text' => self::url_start(true,'updraftplus.com/translate/')."Please go here for instructions - it is easy.".self::url_end(true,'updraftplus.com/translate/'),
				'text_plain' => self::url_start(false,'updraftplus.com/translate/')."Please go here for instructions - it is easy.".self::url_end(false,'updraftplus.com/translate/'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'dismiss_time' => false,
				'supported_positions' => $anywhere,
				'validity_function' => 'translation_needed',
			),
			'social_media' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus is on social media - check us out!', 'updraftplus'), 
				'text' => self::url_start(true,'twitter.com/updraftplus', true).__('Twitter', 'updraftplus').self::url_end(true,'twitter.com/updraftplus', true).' - '.self::url_start(true,'facebook.com/updraftplus', true).__('Facebook', 'updraftplus').self::url_end(true,'facebook.com/updraftplus', true).' - '.self::url_start(true,'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).__('Google+', 'updraftplus').self::url_end(true,'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).' - '.self::url_start(true,'www.linkedin.com/company/updraftplus', true).__('LinkedIn', 'updraftplus').self::url_end(true,'www.linkedin.com/company/updraftplus', true),
				'text_plain' => self::url_start(false,'twitter.com/updraftplus', true).__('Twitter', 'updraftplus').self::url_end(false,'twitter.com/updraftplus', true).' - '.self::url_start(false,'facebook.com/updraftplus', true).__('Facebook', 'updraftplus').self::url_end(false,'facebook.com/updraftplus', true).' - '.self::url_start(false,'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).__('Google+', 'updraftplus').self::url_end(false,'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).' - '.self::url_start(false,'www.linkedin.com/company/updraftplus', true).__('LinkedIn', 'updraftplus').self::url_end(false,'www.linkedin.com/company/updraftplus', true),
				'image' => 'notices/updraft_logo.png',
				'dismiss_time' => false,
				'supported_positions' => $anywhere,
			),
			'newsletter' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Newsletter', 'updraftplus'),
				'text' => __("Follow this link to sign up for the UpdraftPlus newsletter.", 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/newsletter-signup',
				'button_meta' => 'signup',
				'supported_positions' => $anywhere,
				'dismiss_time' => false
			),
			'subscribe_blog' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Blog - get up-to-date news and offers', 'updraftplus'),
				'text' => self::url_start(true,'updraftplus.com/news/').__("Blog link",'updraftplus').self::url_end(true,'updraftplus.com/news/').' - '.self::url_start(true,'feeds.feedburner.com/UpdraftPlus').__("RSS link",'updraftplus').self::url_end(true,'feeds.feedburner.com/UpdraftPlus'),
				'text_plain' => self::url_start(false,'updraftplus.com/news/').__("Blog link",'updraftplus').self::url_end(false,'updraftplus.com/news/').' - '.self::url_start(false,'feeds.feedburner.com/UpdraftPlus').__("RSS link",'updraftplus').self::url_end(false,'feeds.feedburner.com/UpdraftPlus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'supported_positions' => $anywhere,
				'dismiss_time' => false
			),
			'check_out_updraftplus_com' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Blog - get up-to-date news and offers', 'updraftplus'),
				'text' => self::url_start(true,'updraftplus.com/news/').__("Blog link",'updraftplus').self::url_end(true,'updraftplus.com/news/').' - '.self::url_start(true,'feeds.feedburner.com/UpdraftPlus').__("RSS link",'updraftplus').self::url_end(true,'feeds.feedburner.com/UpdraftPlus'),
				'text_plain' => self::url_start(false,'updraftplus.com/news/').__("Blog link",'updraftplus').self::url_end(false,'updraftplus.com/news/').' - '.self::url_start(false,'feeds.feedburner.com/UpdraftPlus').__("RSS link",'updraftplus').self::url_end(false,'feeds.feedburner.com/UpdraftPlus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'supported_positions' => $dashboard_bottom_or_report,
				'dismiss_time' => false
			),
			'autobackup' => array(
				'prefix' => '',
				'title' => __('Be safe with an automatic backup', 'updraftplus'),
				'text' => __('UpdraftPlus Premium can automatically backup your plugins/themes/database before you update, without you needing to remember.', 'updraftplus'),
				'image' => 'automaticbackup.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismissautobackup',
				'supported_positions' => $autobackup_bottom_or_report,
			),
			
			//The sale adverts content starts here
			'blackfriday' => array(
				'prefix' => '',
				'title' => __('Black Friday - 20% off UpdraftPlus Premium until November 30th', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/black_friday.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'blackfridaysale2016',
				'valid_from' => '2016-11-23 00:00:00',
				'valid_to' => '2016-11-30 23:59:59',
				'supported_positions' => $dashboard_top_or_report,
			),
			'christmas' => array(
				'prefix' => '',
				'title' => __('Christmas sale - 20% off UpdraftPlus Premium until December 25th', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/christmas.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'christmassale2016',
				'valid_from' => '2016-12-01 00:00:00',
				'valid_to' => '2016-12-25 23:59:59',
				'supported_positions' => $dashboard_top_or_report,
			),
			'newyear' => array(
				'prefix' => '',
				'title' => __('Happy New Year - 20% off UpdraftPlus Premium until January 1st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/new_year.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'newyearsale2017',
				'valid_from' => '2016-12-26 00:00:00',
				'valid_to' => '2017-01-01 23:59:59',
				'supported_positions' => $dashboard_top_or_report,
			),
			'spring' => array(
				'prefix' => '',
				'title' => __('Spring sale - 20% off UpdraftPlus Premium until April 31st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/spring.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'springsale2017',
				'valid_from' => '2017-04-01 00:00:00',
				'valid_to' => '2017-04-30 23:59:59',
				'supported_positions' => $dashboard_top_or_report,
			),
			'summer' => array(
				'prefix' => '',
				'title' => __('Summer sale 20% off UpdraftPlus Premium until July 31st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/summer.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'summersale2017',
				'valid_from' => '2017-07-01 00:00:00',
				'valid_to' => '2017-07-31 23:59:59',
				'supported_positions' => $dashboard_top_or_report,
			),
		);
	}
	
		// Call this method to setup the notices
	public static function notices_init() {
		if (self::$initialized) return;
		self::$initialized = true;
		self::$notices_content = (defined('UPDRAFTPLUS_NOADS_B') && UPDRAFTPLUS_NOADS_B) ? array() : self::populate_notices_content();
		global $updraftplus;
		$our_version = @constant('SCRIPT_DEBUG') ? $updraftplus->version.'.'.time() : $updraftplus->version;
		wp_enqueue_style('updraftplus-notices-css',  UPDRAFTPLUS_URL.'/css/updraftplus-notices.css', array(), $our_version);
	}

	private static function translation_needed() {
		$wplang = get_locale();
		if (strlen($wplang) < 1 || $wplang == 'en_US' || $wplang == 'en_GB') return false;
		if (defined('WP_LANG_DIR') && is_file(WP_LANG_DIR.'/plugins/updraftplus-'.$wplang.'.mo')) return false;
		if (is_file(UPDRAFTPLUS_DIR.'/languages/updraftplus-'.$wplang.'.mo')) return false;
		return true;
	}
	
	private static function url_start($html_allowed = false, $url, $https = false) {
		$proto = ($https) ? 'https' : 'http';
		if (strpos($url, 'updraftplus.com') !== false){
			return $html_allowed ? "<a href=".apply_filters('updraftplus_com_link',$proto.'://'.$url).">" : "";
		}else{
			return $html_allowed ? "<a href=\"$proto://$url\">" : "";	
		}
	}

	private static function url_end($html_allowed, $url, $https = false) {
		$proto = ($https) ? 'https' : 'http';
		return $html_allowed ? '</a>' : " ($proto://$url)";
	}

	public static function do_notice($notice = false, $position = 'top', $return_instead_of_echo = false) {
	
		self::notices_init();
	
		if (false === $notice) $notice = defined('UPDRAFTPLUS_NOTICES_FORCE_ID') ? UPDRAFTPLUS_NOTICES_FORCE_ID : $notice;

		$notice_content = self::get_notice_data($notice, $position);
		
		if (false != $notice_content) {
			return self::render_specified_notice($notice_content, $return_instead_of_echo, $position);
		}
	}

	/*
		This method will return a notice ready for display.
	*/
	private static function get_notice_data($notice = false, $position = 'top') {

		/*
			We need to check the database to see if any notices have been dismissed and if they have check if the time they have been dismissed for has passed, otherwise we shouldn't display the notices
		 */
		 
		$time_now = defined('UPDRAFTPLUS_NOTICES_FORCE_TIME') ? UPDRAFTPLUS_NOTICES_FORCE_TIME : time();
		 
		$notice_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('dismissed_general_notices_until', 0));
		$seasonal_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('dismissed_season_notices_until', 0));
		$autobackup_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('updraftplus_dismissedautobackup', 0));

		// If a specific notice has been passed to this method then return that notice.
		if ($notice) {
			if (!isset(self::$notices_content[$notice])) return false;
		
			// Does the notice support the position specified? 
			if (isset(self::$notices_content[$notice]['supported_positions']) && !in_array($position, self::$notices_content[$notice]['supported_positions'])) return false;

			/*
				first check if the advert passed can be displayed and hasn't been dismissed, we do this by checking what dismissed value we should be checking.
			 */
			$dismiss_time = self::$notices_content[$notice]['dismiss_time'];

			if ('dismiss_notice' == $dismiss_time) $dismiss = $notice_dismiss;
			if ('dismiss_season' == $dismiss_time) $dismiss = $seasonal_dismiss;
			if ('dismissautobackup' == $dismiss_time) $dismiss = $autobackup_dismiss;

			if (!empty($dismiss)) return false;

			return self::$notices_content[$notice];
		}

		//create an array to add non-seasonal adverts to so that if a seasonal advert can't be returned we can choose a random advert from this array.
		$available_notices = array();

		//If Advert wasn't passed then next we should check to see if a seasonal advert can be returned.
		foreach (self::$notices_content as $notice_id => $notice_data) {
			// Does the notice support the position specified? 
			if (isset(self::$notices_content[$notice_id]['supported_positions']) && !in_array($position, self::$notices_content[$notice_id]['supported_positions'])) continue;
			
			// If the advert has a validity function, then require the advert to be valid
			if (!empty($notice_data['validity_function']) && !call_user_func(array('UpdraftPlus_Notices', $notice_data['validity_function']))) continue;
		
			global $updraftplus;
		
			if (isset($notice_data['valid_from']) && isset($notice_data['valid_to'])) {
				// Do not show seasonal notices to people with an updraftplus.com version and no-addons yet
				if (!file_exists(UPDRAFTPLUS_DIR.'/udaddons') || $updraftplus->have_addons) {
					$valid_from = strtotime($notice_data['valid_from']);
					$valid_to = strtotime($notice_data['valid_to']);
					if (($time_now >= $valid_from && $time_now <= $valid_to) && !$seasonal_dismiss) {
						return $notice_data;
					}
				}
			} else {
			
				$dismiss = false;
				$dismiss_time = self::$notices_content[$notice_id]['dismiss_time'];
				if ('dismiss_notice' == $dismiss_time) $dismiss = $notice_dismiss;
				if ('dismiss_season' == $dismiss_time) $dismiss = $seasonal_dismiss;
				if ('dismissautobackup' == $dismiss_time) $dismiss = $autobackup_dismiss;
			
				if (!$dismiss) $available_notices[$notice_id] = $notice_data;
			}
		}
		
		if (empty($available_notices)) return false;

		//If a seasonal advert can't be returned then we will return a random advert

		/*
			Using shuffle here as something like rand which produces a random number and uses that as the array index fails, this is because in future an advert may not be numbered and could have a string as its key which will then cause errors.
		*/
		shuffle($available_notices);
		return $available_notices[0];

	}

	private static function render_specified_notice($advert_information, $return_instead_of_echo = false, $position = 'top') {
		
		if ('bottom' == $position) {
			$template_file = 'bottom-notice.php';
		} elseif ('report' == $position) {
			$template_file = 'report.php';
		} elseif ('report-plain' == $position) {
			$template_file = 'report-plain.php';
		} else {
			$template_file = 'horizontal-notice.php';
		}
		
		require_once(UPDRAFTPLUS_DIR.'/admin.php');
		global $updraftplus_admin;
		return $updraftplus_admin->include_template('wp-admin/notices/'.$template_file, $return_instead_of_echo, $advert_information);
	}
}
