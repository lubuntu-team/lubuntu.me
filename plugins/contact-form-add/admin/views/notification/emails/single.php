<?php 

if ( ! is_array( $field['value'] ) ) {

	$value = esc_attr( $field['value'] );

	echo $value; 

}

if ( is_array( $field['value'] ) ) {

	$value = $field['value'];

	if ( key( $value ) === 'name' ) {
		
		if ( ! $atts['adt'] ) {

			echo esc_attr( $value['name']['firstName'] ) . ' ' . esc_attr( $value['name']['lastName'] );
		}

		if ( mb_strtolower( $atts['adt'] ) === 'first' || mb_strtolower( $atts['adt'] ) === 'firstname' ) {
			echo esc_attr( $value['name']['firstName'] );
		}

		if ( mb_strtolower( $atts['adt'] ) === 'last' || mb_strtolower( $atts['adt'] ) === 'lastname' ) {
			echo esc_attr( $value['name']['lastName'] );
		}


	} else if ( key( $value ) === 'address' ) {

		$atts['adt'] = mb_strtolower( $atts['adt'] );

		if ( $atts['adt'] === 'address1' || $atts['adt'] === 'streetaddress'  || $atts['adt'] === 'streetaddress1')
			echo esc_attr( $value['address']['streetAddress'] );

		if ( $atts['adt'] === 'address2' || $atts['adt'] === 'streetaddress2'  || $atts['adt'] === 'streetaddress2')
			echo esc_attr( $value['address']['streetAddress2'] );

		if ( $atts['adt'] === 'city' )
			echo esc_attr( $value['address']['city'] );

		if ( $atts['adt'] === 'state' )
			echo esc_attr( $value['address']['state'] );

		if ( $atts['adt'] === 'zip' )
			echo esc_attr( $value['address']['zip'] );

		if ( $atts['adt'] === 'country' )
			echo esc_attr( $value['address']['country'] );

		if ( ! $atts['adt'] && ! $atts['useHTML'] ) {
			echo esc_attr( $value['address']['streetAddress'] ) . "\r\n";
			echo esc_attr( $value['address']['streetAddress2'] ) . "\r\n";
			echo esc_attr( $value['address']['city'] ) . "\r\n";
			echo esc_attr( $value['address']['state'] ) . "\r\n";
			echo esc_attr( $value['address']['zip'] ) . "\r\n";
			echo esc_attr( $value['address']['country'] ) . "\r\n";
		}

	} else if ( key( $value ) === 'checkbox' ) {

		if ( $atts['useHTML'] ) {

			include smuzform_admin_view( 'notification/emails/single-checkbox.php' );

		} else {

			foreach ( $value['checkbox'] as $check ) {
				echo esc_attr( $check ) . "\r\n"; 
			}

		}

	}

}

?>