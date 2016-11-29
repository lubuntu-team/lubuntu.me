<?php foreach( $entry_data as $key => $data ): ?>

<?php
	
	$label = esc_attr( $data['label'] );

	$value = $data['value'];

	if ( ! is_array( $value ) ) {

		$value = esc_attr( $data['value'] );

		echo "$label: $value" . "\r\n";
	
	} else if ( key( $value ) === 'name' ) {

		$firstName = esc_attr( $value['name']['firstName'] );

		$lastName = esc_attr( $value['name']['lastName'] );

		echo "First Name: $firstName" . "\r\n" . "\r\n";

		echo "Last Name: $lastName" . "\r\n";
			
	} else if ( key( $value ) === 'address' ) {

		echo esc_attr( $label  ) . ": \r\n";
		echo esc_attr( $value['address']['streetAddress'] ) . "\r\n";
		echo esc_attr( $value['address']['streetAddress2'] ) . "\r\n";
		echo esc_attr( $value['address']['city'] ) . "\r\n";
		echo esc_attr( $value['address']['state'] ) . "\r\n";
		echo esc_attr( $value['address']['zip'] ) . "\r\n";
		echo esc_attr( $value['address']['country'] ) . "\r\n";

	} else if ( key( $value ) === 'checkbox' ) {

		echo esc_attr( $label  ) . ": \r\n";

		foreach ( $value['checkbox'] as $check ) {
			echo esc_attr( $check ) . "\r\n"; 
		}


	}

		

?>

<?php endforeach; ?>