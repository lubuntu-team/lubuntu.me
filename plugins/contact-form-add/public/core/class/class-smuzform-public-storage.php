<?php

class SmuzForm_Public_Storage {
	
	function __construct() {

		if ( ! class_exists( 'SmuzForm_Form' ) )
			smuzform_public( 'core/class/class-smuzform-form.php' );

	}

	public function init() {

		add_action( 'wp_ajax_smuzform-storage', array( $this, 'api' ) );
		add_action( 'wp_ajax_nopriv_smuzform-storage', array( $this, 'api' ) );
		
	}

	

	function api() {

		$form_id = intval( $_GET['formId'] );

		$this->preCheckup( $form_id );

		$method = $_GET['method'];

		if ( isset( $_GET['use'] ) || $_GET['use'] == 'ajax' )
			$useAjax = true;
		else
			$useAjax = false;

		if ( $method === 'submit' && ! $useAjax )
			$this->htmlSubmit( $form_id );

		if ( $method === 'ajax' || $useAjax )
			$this->ajaxSubmit( $form_id );

		exit;

	}

	function filterFormFields( $untrustedData, $form ) {
		
		$trustedData = array();

		foreach( $untrustedData as $key => $utd ) {

			$field = $form->getFieldByCssId( $key );
			
			/*
			If the field does not exist or field key is invalid or field is removed from form. Skip the loop.
			*/
			if ( ! $field ) continue;

			if ( ! is_array( $utd ) ) {

				$utd = esc_html( $utd );
			
			} else {

				if ( key( $utd ) === 'name' && $field['type'] === 'name' ) {

					$utd['name']['firstName'] = esc_html( $utd['name']['firstName'] );

					$utd['name']['lastName'] = esc_html( $utd['name']['lastName'] );

				} elseif ( key( $utd ) === 'date' && $field['type'] === 'date' ) {

					$dd = intval( $utd['date']['dd'] );
					$mm = intval( $utd['date']['mm'] );
					$yyyy = intval( $utd['date']['yyyy'] );

					if ( $dd <= 0 || $mm <= 0 || $yyyy <= 0 )
						$utd = 'invalid';
					else if ( $dd > 31 || $mm > 12 || $yyyy > 9999 )
						$utd = 'invalid';
					else if ( empty( $utd['date']['dd'] ) || empty( $utd['date']['mm'] ) || empty( $utd['date']['yyyy'] ) )
						$utd = 'invalid';
					else
						$utd = sprintf( "%d/%d/%d", $dd, $mm, $yyyy );

				} elseif ( key( $utd ) === 'address' && $field['type'] === 'address' ) {

					
					$utd['address']['streetAddress'] = esc_html( $utd['address']['streetAddress'] );
					$utd['address']['streetAddress2'] = esc_html( $utd['address']['streetAddress2'] );
					$utd['address']['city'] = esc_html( $utd['address']['city'] );
					$utd['address']['state'] = esc_html( $utd['address']['state'] );
					$utd['address']['zip'] = esc_html( $utd['address']['zip'] );
					$utd['address']['country'] = esc_html( $utd['address']['country'] );

					
				} elseif ( key( $utd ) === 'checkbox' && $field['type'] === 'checkbox' ) {

					$index = 0;
					foreach ($utd['checkbox'] as $tmpvalue) {
						$utd['checkbox'][$index++] = esc_html( $tmpvalue );
					}

				}
				
			}


			$utd = apply_filters( 'smuzform_storage_field_value', $utd, $form );
			
			$trustedData[ esc_sql( $key ) ] = $utd;

		}

		foreach( $form->getFilterFields() as $val) {
			
			if ( ! isset( $trustedData[$val['cssID']] ) )
				$trustedData[$val['cssID']] = '';

		}

		
		return $trustedData;

	}

	function filterFormFileFields( $form, $data ) {

		$data = $this->normalize_files_array( $data );

		$untrustedData = $data;

		$trustedData = array();

		$index = 0;

		$field_id = null;

		foreach ($untrustedData as $key => $file) {
			
			$field_id = $key;

			$field = $form->getFieldByCssId( $field_id );
			
			if ( is_array( $field ) ) {

				if ( $field['type'] === 'fileupload' ) {
					$trustedData[$key] = array(
						'name' => $file[$index]['name'],
						'type' => $file[$index]['type'],
						'tmp_name' => $file[$index]['tmp_name'],
						'error' => $file[$index]['error'],
						'size' => $file[$index]['size']
					);
				}

			}

		}
		
		return $trustedData;

	}

	function uploadFiles( $form, $errors = array() ) {

		$files = $this->filterFormFileFields( $form, $_FILES );

		$trustedData = array();

		$dt = array();

		$is_error = false;

		foreach( $files as $key => $file ) {

			$field = $form->getFieldByCssId( $key );

			$maxSize = $field['file']['maxSize'];

			$allowedExt = $field['file']['allowed'];

			$isReq = $field['required'];

			//$allowedExt = implode( $allowedExt , '|');

			$dt['fieldId'] = $field['cssID'];
			$dt['fieldLabel'] = $field['label'];
			$dt['errorTrigger'] = $field['type'];
			$dt['form_id'] = $form->getId();

			if ( $file['error'] === UPLOAD_ERR_OK ) {

				$tmp_name = $file['tmp_name'];

				$file_name = sanitize_file_name( $file['name'] );

				$file_size = $file['size'];

				$file_size = ( $file_size / 1024 ) / 1024;

				$ext = pathinfo($file_name, PATHINFO_EXTENSION);

				if ( ! in_array( $ext , $allowedExt) ) {

					$errors[] = array(
							'signal' => false,
							'code' => 2100,
							'message' => ecs_attr($ext).' file extension is not allowed.',
							'additionalData' => $dt
						);

					$is_error = true;

				} else if ( $file_size >= intval($maxSize) ) {

					$errors[] = array(
							'signal' => false,
							'code' => 2000,
							'message' => 'The uploaded file exceeds the ' . $maxSize . 'MB Limit',
							'additionalData' => $dt
						);

					$is_error = true;

				}


				

				if ( $is_error === false ) {

					$uploadDir = get_option( 'smuzform_upload_dir' );

					$hash = wp_generate_password(10, false, false);

					$moveFileName = $hash . '-' . $file_name;
					
					$moveFileName = $uploadDir . DIRECTORY_SEPARATOR . $form->getId() . DIRECTORY_SEPARATOR . $moveFileName;

					if ( ! file_exists($uploadDir . DIRECTORY_SEPARATOR . $form->getId() . DIRECTORY_SEPARATOR ) )
						@mkdir($uploadDir . DIRECTORY_SEPARATOR . $form->getId() . DIRECTORY_SEPARATOR);
 
					$result = move_uploaded_file( $tmp_name, $moveFileName );

					if ( $result ) {

						$trustedData[$key] = $moveFileName;

					}

				}

			}

		}

		return $trustedData;


	}

	function htmlSubmit( $form_id ) {

		$form = new SmuzForm_Form( $form_id );

		$errors = array();
		
		$returnLink = $_POST['_returnLink'];

		$untrustedData = $_POST['smFieldData'];
		
		$trustedData = $this->filterFormFields( $untrustedData, $form );

		
		if ( ! $form->getFields() ) {

			$errors[] = array( 
				'signal' => false, 
				'code' => 9999, 
				'message' => 'No Fields.'
			);

		}

		$trustedFile = $this->uploadFiles( $form, $errors );
		
		if ( ! $this->isError( $trustedFile ) ) {
			
			foreach( $trustedFile as $cssID => $fd ) {

				$trustedData[$cssID] = $fd;

			}

		} else {

			$errors = $trustedFile;

		}

		foreach ( $trustedData as $key => $td  ) {
			
			$tmp = $form->verifyFieldEntryValue( $key, $td  );

			if ( ! $tmp['signal'] )
				$errors[] = $tmp;

		}
		
		$errors = apply_filters( 'smuzform_field_errors', $errors, $form_id, $form );

		$processEntryAndNotifications = apply_filters( 'smuzform_do_entry_and_notifications', true );

		if ( empty( $errors ) ) {

			if ( $processEntryAndNotifications === true ) {
				$entryId = $this->saveEntry( $form_id, $trustedData );
			}

		}
		
		do_action( 'smuzform_form_submit', $form, $errors, $trustedData, $returnLink, 'html', $processEntryAndNotifications );

		if ( $form->getConfirmationOption() === 'text' || ! empty( $errors ) )
			/* When Javascript is disabled */ 
			$this->htmlSubmitView( $errors, $form_id, $returnLink );
		else
			wp_redirect( $form->getRedirectUrl() );

	}

	function htmlSubmitView( $errors, $form_id, $returnLink ) {

		$form = new SmuzForm_Form( $form_id );

		include smuzform_public_view( 'form/html-submit-view.php' );
	}

	function ajaxSubmit( $form_id ) {

		$form = new SmuzForm_Form( $form_id );

		$errors = array();
		
		$returnLink = $_POST['_returnLink'];

		$untrustedData = $_POST['smFieldData'];
		
		$trustedData = $this->filterFormFields( $untrustedData, $form );

		
		if ( ! $form->getFilterFields() ) {

			$errors[] = array( 
				'signal' => false, 
				'code' => 9999, 
				'message' => 'No Fields.'
			);

		}

		/*$trustedFile = $this->uploadFiles( $form, $errors );
		
		if ( ! $this->isError( $trustedFile ) ) {
			
			foreach( $trustedFile as $cssID => $fd ) {

				$trustedData[$cssID] = $fd;

			}

		} else {

			$errors = $trustedFile;

		}*/

		foreach ( $trustedData as $key => $td  ) {
			
			$tmp = $form->verifyFieldEntryValue( $key, $td  );

			if ( ! $tmp['signal'] )
				$errors[] = $tmp;

		}
		

		$errors = apply_filters( 'smuzform_field_errors', $errors, $form_id, $form );

		$processEntryAndNotifications = apply_filters( 'smuzform_do_entry_and_notifications', true );

		if ( empty( $errors ) ) {

			if ( $processEntryAndNotifications === true ) {
				$entryId = $this->saveEntry( $form_id, $trustedData );
			}

			if ( $form->getConfirmationOption() === 'text' )
				$isThanksMsg = true;
			else
				$isThanksMsg = false;

			$thanksMsg = esc_html( $form->getTextMessage() );

			$response = array(
					'signal' => true,
					'code' => 1,
					'thanksMsg' => $thanksMsg,
					'errors' => array(),
					'isMsg' => $isThanksMsg,
					'redirectUrl' => $form->getRedirectUrl()
				);

		} else {

			$response = array(
					'signal' => false,
					'code' => 0,
					'thanksMsg' => null,
					'errors' => $errors,
					'isRedirect' => null,
					'redirectUrl' => null
				);

		}

		$response = apply_filters( 'smuzform_public_ajax_resp', $response );
		
		do_action( 'smuzform_form_submit', $form, $errors, $trustedData, $returnLink, 'ajax', $processEntryAndNotifications );

		echo json_encode( $response );

	}

	function saveEntry( $form_id, $data ) {

		if ( ! is_array( $data ) && empty( $data ) )
			return false;

		$entry_id = $this->saveToDatabase( $form_id, $data );

		do_action( 'smuzform_after_save_entry_data', $form_id, $entry_id, $data  );

		$this->sendNotifications( $form_id, $entry_id, $data );

		return $entry_id;

	}

	function sendNotifications( $form_id, $entry_id, $data ) {

		$notificationManager = new SmuzForm_Notification_Manager( $form_id );

		$notificationManager->sendNotifications( $entry_id, $data );

		do_action( 'smuzform_after_notifications_send', $form_id, $entry_id, $data  );

	}


	/**
	Add entry and the fields data in WordPress connected database.

	@Return entry_id 
	**/
	function saveToDatabase( $form_id, $data ) {

		global $wpdb;

		$ip = $this->getUserIp();
		$userAgent = $this->getUserAgent();
		$createdAt =  date( 'Y-m-d H:i:s' );
		$updatedAt =  date( 'Y-m-d H:i:s' );

		$entry = array(
				'form_id' => $form_id,
				'user_ip' => $ip,
				'user_agent' => $userAgent,
				'created_at' => $createdAt,
				'updated_at' => $updatedAt
			);

		$entryTableName = $wpdb->prefix . SMUZFORM_ENTRY_DATABASE_TABLE_NAME;
		
		$entryTableDataName = $wpdb->prefix . SMUZFORM_ENTRY_DATA_DATABASE_TABLE_NAME;

		$wpdb->insert( $entryTableName, $entry );

		$entryId = $wpdb->insert_id;

		$entryData = array();

		foreach ( $data as $key => $value ) {

			$dtmp = array(
				'form_id' => $form_id,
				'field_id' => $key,
				'entry_id' => $entryId
			);

			$dtmp['value'] = maybe_serialize( $value );

			$entryData[] = $dtmp;

		}

		$this->wp_insert_rows( $entryData, $entryTableDataName );

		do_action( 'smuzform_storage_signal_database', $form_id, $data );

		return $entryId;
	
	}

	function sendToService( $form_id, $data, $service_name, $entry_id ) {

		do_action( 'smuzform_storage_signal_service', $form_id, $data, $service_name, $entry_id ); 
	
	}

	function formExist( $form_id ) {

		$form = get_post( $form_id );

		if ( $form->post_type !== 'smuzform' || $form->post_status !== 'publish' )
			return false;

		return true;

	}

	function preCheckup( $form_id ) {

		if ( ! $this->formExist( $form_id ) )
			die();

		$_REQUEST['_ajax_nonce'] = $_GET['nonce'];

		check_ajax_referer( 'smuzform-storage-ajax-referer' );

		if ( ! empty( $_REQUEST['smuztrapfish'] ) )
			die();

		$this->fraudCheck();

	}

	function fraudCheck() { do_action( 'smuzform_storage_fraud_check' ); }

	
	function getUserIp() {
	    
	    $ip = apply_filters( 'smuzform_get_user_ip', $_SERVER['REMOTE_ADDR'] );

	    return $ip;

	}

	function getUserAgent() {

		$ug = $_SERVER['HTTP_USER_AGENT'];
		
		return apply_filters( 'smuzform_get_user_agent', $ug ); 
	}

	/**
	 * A method for inserting multiple rows into the specified table
	 * 
	 *  Usage Example: 
	 *
	 *  $insert_arrays = array();
	 *  foreach($assets as $asset) {
	 *
	 *  $insert_arrays[] = array(
	 *  'type' => "multiple_row_insert",
	 *  'status' => 1,
	 *  'name'=>$asset,
	 *  'added_date' => current_time( 'mysql' ),
	 *  'last_update' => current_time( 'mysql' ));
	 *
	 *  }
	 *
	 *  wp_insert_rows($insert_arrays);
	 *
	 *
	 * @param array $row_arrays
	 * @param string $wp_table_name
	 * @return false|int
	 *
	 * @author  Ugur Mirza ZEYREK
	 * @source http://stackoverflow.com/a/12374838/1194797
	 */

	function wp_insert_rows($row_arrays = array(), $wp_table_name) {
	    global $wpdb;
	    $wp_table_name = esc_sql($wp_table_name);
	    // Setup arrays for Actual Values, and Placeholders
	    $values = array();
	    $place_holders = array();
	    $query = "";
	    $query_columns = "";

	    $query .= "INSERT INTO {$wp_table_name} (";

	            foreach($row_arrays as $count => $row_array)
	            {

	                foreach($row_array as $key => $value) {

	                    if($count == 0) {
	                        if($query_columns) {
	                        $query_columns .= ",".$key."";
	                        } else {
	                        $query_columns .= "".$key."";
	                        }
	                    }

	                    $values[] =  $value;

	                    if(is_numeric($value)) {
	                        if(isset($place_holders[$count])) {
	                        $place_holders[$count] .= ", '%d'";
	                        } else {
	                        $place_holders[$count] .= "( '%d'";
	                        }
	                    } else {
	                        if(isset($place_holders[$count])) {
	                        $place_holders[$count] .= ", '%s'";
	                        } else {
	                        $place_holders[$count] .= "( '%s'";
	                        }
	                    }
	                }
	                        // mind closing the GAP
	                        $place_holders[$count] .= ")";
	            }

	    $query .= " $query_columns ) VALUES ";

	    $query .= implode(', ', $place_holders);

	    if($wpdb->query($wpdb->prepare($query, $values))){
	        return true;
	    } else {
	        return false;
	    }

	}

	function normalize_files_array($files) {

        $normalized_array = array();

        foreach($files as $index => $file) {

            if (!is_array($file['name'])) {
                $normalized_array[$index][] = $file;
                continue;
            }

            foreach($file['name'] as $idx => $name) {
                $normalized_array[$index][$idx] = array(
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                );
            }

        }

        return $normalized_array;

    }

    function isError( $errors ) {

    	if ( empty($errors) )
    		return false;

    	foreach ($errors as $key => $error ) {
    		
    		if ( ! isset( $error['signal'] ) )
    			return false;
    		
    		if ( $error['signal'] === false )
    			return true;

    	}

    	return false;
    }



}