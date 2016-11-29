<?php

class SmuzForm_Notification_Manager extends SmuzForm_Form {

	private $entry_data;

	private $entry_id = null;

	private $currNotification = null;

	function __construct( $form_id ) {

		add_shortcode( 'smfield', array( $this, 'replaceVarsInTemplate' ) );

		parent::__construct( $form_id );

	}

	function getNotifications() {

		$data = get_post_meta( $this->getId(), 'notifications', true );

		return apply_filters( 'smuzform_get_notifications', $data );

	}

	function sendNotifications( $entry_id, $entry_data ) {

		$this->entry_id = $entry_id;

		$this->entry_data = $entry_data; 

		$notifications = $this->getNotifications();

		foreach ( $notifications['notifications'] as $notification ) {
			
			if ( ! $this->checkRules( $notification, $entry_id, $entry_data ) )
				continue;
				
			if ( $notification['type'] === 'email' ) {

				$this->sendEmail( $notification, $entry_id, $entry_data );

			}

			if ( $notification['type'] === 'confirmationEmail' ) {

				$this->sendConfirmationEmail( $notification, $entry_id, $entry_data );

			}

		}


	}

	function sendEmail( $notification, $entry_id, $entry_data ) {

		$this->currNotification = $notification;

		$emailAddr = $notification['extraData']['emailAddress'];

		$replyTo = $notification['extraData']['replyToEmail'];

		$subject = $notification['extraData']['subject'];

		$fromText = $notification['extraData']['fromText'];

		$template = $notification['extraData']['template'];

		$emailAddr = sanitize_email( $emailAddr );

		$replyTo = $this->processTemplate( $replyTo, $entry_id, $entry_data );

		$subject = $this->processTemplate( $subject, $entry_id, $entry_data );

		$fromText =  $fromText;

		$message = $this->processTemplate( $template, $entry_id, $entry_data );

		if ( $notification['extraData']['useHTML'] ) {
			ob_start();

			include smuzform_admin_view( 'notification/confirmation-email/html-message.php' );

			$message = ob_get_contents();
			ob_end_clean();
		}
		
		if ( $notification['extraData']['useHTML'] )
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
		else {
			
		}

		if ( preg_replace('/\s+/', '', $replyTo) !== '' )
			$headers[] = "Reply-To: $replyTo" . "\r\n";
		
		if (!empty($fromText)) {
			$adm_email = get_option('admin_email');
			$headers[] = "From: $fromText <$adm_email>" . "\r\n";
		} else {
			$adm_email = get_option('admin_email');
			$headers[] = "From: WordPress <$adm_email>" . "\r\n";
		}

		$resp = wp_mail( $emailAddr, $subject, $message, $headers );

		$this->currNotification = null;

	}

	function sendConfirmationEmail( $notification, $entry_id, $entry_data ){

		$this->currNotification = $notification;

		$emailAddr = $notification['extraData']['emailAddress'];

		$replyTo = $notification['extraData']['replyToEmail'];

		$subject = $notification['extraData']['subject'];

		$fromText = $notification['extraData']['fromText'];

		$template = $notification['extraData']['template'];

		$replyTo = $this->processTemplate( $replyTo, $entry_id, $entry_data );

		$subject = $this->processTemplate( $subject, $entry_id, $entry_data );

		$fromText = sanitize_text_field( $fromText );

		$message = $this->processTemplate( $template, $entry_id, $entry_data );

		$fields = $this->getFilterFields();

		if ( empty( $emailAddr ) ) {

			foreach( $fields as $field ) {

				if ( $field['type'] === 'email' ) {
					$emailAddr = $field['cssID'];
					break;
				}

			}
		}

		foreach ( $entry_data as $key => $data ) {

			if ( $key === $emailAddr ) {
				$emailAddr = $data;
			}

		}

		if ( ! is_email( $emailAddr ) )
			return false;

		if ( $notification['extraData']['useHTML'] ) {
			ob_start();

			include smuzform_admin_view( 'notification/confirmation-email/html-message.php' );

			$message = ob_get_contents();
			ob_end_clean();
		}
		
		if ( $notification['extraData']['useHTML'] )
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
		else {
			
		}

		if ( preg_replace('/\s+/', '', $replyTo) !== '' )
			$headers[] = "Reply-To: $replyTo" . "\r\n";

		if ( preg_replace('/\s+/', '', $fromText) !== '' )
			$headers[] = "Reply-To: $fromText <$replyTo>" . "\r\n";
		

		$resp = wp_mail( $emailAddr, $subject, $message, $headers );

		$this->currNotification = null;
			
	}

	function checkRules( $notification, $entry_id, $entry_data ) {

		if ( ! $notification['ruleEnabled'] )
			return true;



		$cmpValue = $notification['rules']['cmpValue'];
		$field_id = $notification['rules']['field'];
		$action = $notification['rules']['action'];
		$operator = $notification['rules']['operator'];

		if ( preg_replace('/\s+/', '', $cmpValue) == '' )
			return true;

		
		$field = $this->getFieldByCssId( $field_id );
		
		/*
		If field is deleted from the form builder.
		*/
		if ( ! is_array( $field ) )
			return true;

		if ( $action === 'send' ) {

			foreach ( $entry_data as $key => $data ) {
				
				if ( $key === $field_id ) {


					
					if ( $operator === 'is' ) {

						if ( mb_strtolower( $cmpValue ) === mb_strtolower( $data ) )
							return true;
						else
							return false;

						
					}

					if ( $operator === 'isNot' ) {

						if ( mb_strtolower( $cmpValue ) !== mb_strtolower( $data ) ) 
							return true;
						else
							return false;
					}
				}

			}

			return false;

		}

		if ( $action === 'stop' ) {

			foreach ( $entry_data as $key => $data ) {
				
				if ( $key === $field_id ) {
					
					if ( $operator === 'is' ) {

						if ( mb_strtolower( $cmpValue ) === mb_strtolower( $data ) ) 
							return false;
						else
							return true;
					}

					if ( $operator === 'isNot' ) {

						if ( mb_strtolower( $cmpValue ) !== mb_strtolower( $data ) ) 
							return false;
						else
							return true;

						
					}
				}

			}

		}

		return true;
 
	}

	function processTemplate( $template, $id, $data ) {

		$template = do_shortcode( $template );

		return $template;
	}

	function replaceVarsInTemplate( $atts ) {

		if ( $this->currNotification === null )
			return 'Use class';

		$entry_id = $this->entry_id;

		if ( $entry_id === null )
			return 'Use class';

		$entry_data = $this->entry_data;

		$notification = $this->currNotification;

		$atts = shortcode_atts( array(
			'print' => null,
			'html' => false,
			'adt' => false
		), $atts, 'smfield' );

		if ( ! $atts['print'] )
			return false;

		$entry_data = $this->readyEntryData( $entry_data );

		ob_start();

		if ( $atts['print'] === 'all' ) {
			
			if ( $notification['extraData']['useHTML'] ) {

				include smuzform_admin_view( 'notification/emails/html/all.php' );

			} else {

				include smuzform_admin_view( 'notification/emails/all-fields.php' );
			}
			
		}

		if ( $atts['print'] !== 'all' ) {

			$label = $atts['print'];

			$field = array();

			foreach ( $entry_data as $data ) {

				if ( mb_strtolower( $data['label'] ) === mb_strtolower( $label ) )
					$field = $data;

			}


			include smuzform_admin_view( 'notification/emails/single.php' );


		}
		
		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}

	function readyEntryData( $entry_data ) {
		
		$_filter = array();

		foreach( $entry_data as $key => $data ) {

			$tmp = array();

			$field_id = $key;

			$field = $this->getFieldByCssId( $field_id );

			$value = $data;

			if ( $field['type'] === 'fileupload' && ! empty( $value ) ) {

				$file_name = basename( basename($value) );

				$directory = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . basename( get_option( 'smuzform_upload_dir' ) ) . DIRECTORY_SEPARATOR . $this->getId() . DIRECTORY_SEPARATOR;

				$value = WP_CONTENT_URL . $directory . $file_name;

			}

			if ( is_array( $data ) && key( $data ) == 'name' ) {

			}else if ( is_array( $data ) && key( $data ) == 'address' ) {

			}

			$tmp['label'] = $field['label'];
			$tmp['value'] = $value;
			$tmp['adt'] = $field;

			$_filter[] = $tmp;


		}

		return $_filter;
	} 

}