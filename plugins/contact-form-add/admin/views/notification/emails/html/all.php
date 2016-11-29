    <?php foreach( $entry_data as $key => $data ): ?>
                <table cellspacing="0" cellpadding="0" width="100%">
                  <tr>
                    <td>
                      <b style="font-size: 18px"><?php echo esc_html( $data['label'] ) ?></b>
                    </td>
                   
                  </tr>
                  <tr>
                    <td class="border-bottom" height="5" style="border-bottom: 1px solid #ddd;"></td>
             
                  </tr>
                  <tr>
                    <td style="padding-top:5px;">
                      <p><?php
                      	$value = $data['value'];

                      	if ( ! is_array( $value ) ) {

                      		echo esc_html( $value );

                      	} else if ( key( $value ) === 'name' ) {

							$firstName = esc_attr( $value['name']['firstName'] );

							$lastName = esc_attr( $value['name']['lastName'] );

							echo "<b>First Name: </b> $firstName" . '<br />';

							echo "<b>Last Name: </b> $lastName" . '<br />';
								
						} else if ( key( $value ) === 'address' ) {

							
							echo esc_attr( $value['address']['streetAddress'] ) . '<br />';
							echo esc_attr( $value['address']['streetAddress2'] ) . '<br />';
							echo esc_attr( $value['address']['city'] ) . '<br />';
							echo esc_attr( $value['address']['state'] ) . '<br />';
							echo esc_attr( $value['address']['zip'] ) . '<br />';
							echo esc_attr( $value['address']['country'] ) . '<br />';

						} else if ( key( $value ) === 'checkbox' ) {

							include smuzform_admin_view( 'notification/emails/single-checkbox.php' );


						}

                      ?></p>
                    </td>
                  </tr>
                </table>
            <?php endforeach; ?>