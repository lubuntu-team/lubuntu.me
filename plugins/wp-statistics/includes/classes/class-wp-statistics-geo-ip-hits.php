<?php
/*
	This is the class for recording GeoIP information for hits on the WordPress site.  It extends the Hits class.
*/

// Load the classes.
use GeoIp2\Database\Reader;

class WP_Statistics_GEO_IP_Hits extends WP_Statistics_Hits {
	public function __construct() {
		global $WP_Statistics;
		// Call the parent constructor (WP_Statistics::__constructor).
		parent::__construct();

		// We may have set the location based on a private IP address in the hits class, if so, don't bother looking it up again.
		if ( $this->location == '000' ) {

			// Now get the location information from the MaxMind database.
			try {
				// Get the WordPress upload directory information, which is where we have stored the MaxMind database.
				$upload_dir = wp_upload_dir();

				// Create a new Reader and point it to the database.
				$reader = new Reader( $upload_dir['basedir'] . '/wp-statistics/GeoLite2-Country.mmdb' );

				// Look up the IP address
				$record = $reader->country( $WP_Statistics->ip );

				// Get the location.
				$location = $record->country->isoCode;

				// MaxMind returns a blank for location if it can't find it, but we want to use 000 so replace it.
				if ( $location == "" ) {
					$location = "000";
				}
			} catch ( Exception $e ) {
				$location = "000";
			}

			// Store the location in the protected $location variable from the parent class.
			$this->location = $location;
		}

		// Check to see if we are excluded by the GeoIP rules.
		if ( ! $this->exclusion_match ) {
			// Grab the excluded/included countries lists, force the country codes to be in upper case to match what the GeoIP code uses.
			$excluded_countries        = explode(
				"\n",
				strtoupper( str_replace( "\r\n", "\n", $WP_Statistics->get_option( 'excluded_countries' ) ) )
			);
			$included_countries_string = trim(
				strtoupper( str_replace( "\r\n", "\n", $WP_Statistics->get_option( 'included_countries' ) ) )
			);

			// We need to be really sure this isn't an empty string or explode will return an array with one entry instead of none.
			if ( $included_countries_string == '' ) {
				$included_countries = array();
			} else {
				$included_countries = explode( "\n", $included_countries_string );
			}

			// Check to see if the current location is in the excluded countries list.
			if ( in_array( $this->location, $excluded_countries ) ) {
				$this->exclusion_match  = true;
				$this->exclusion_reason = "geoip";
			} // Check to see if the current location is not the included countries list.
			else if ( ! in_array( $this->location, $included_countries ) && count( $included_countries ) > 0 ) {
				$this->exclusion_match  = true;
				$this->exclusion_reason = "geoip";
			}
		}
	}
}