<?php

class SmuzForm_Form {
	
	function __construct( $form_id ) {

		$this->id = $form_id;

		$object = $this->getFormMeta( 'model' );

		$this->model = $object;

		$this->setProperties();
		
	}

	function setProperties() {

		$model = $this->getModel();

		$this->title = $model['title'];

		$this->description = $model['description'];

		$this->fields = $model['fields'];

		$this->captcha = $model['captcha'];

		$this->lang = $model['lang'];

		$this->labelPlacement = $model['labelPlacement'];

		$this->confirmationOption = $model['confirmationOption'];

		$this->textMessage = $model['textMessage'];

		$this->redirectUrl = $model['redirectUrl'];

		$this->noOfEntries = $model['noOfEntries'];

		$this->onlySingleIP = $model['onlySingleIP'];

		$this->formTimer = $model['formTimer'];

		$this->formStartTime = $model['formStartTime'];

		$this->formEndTime = $model['formEndTime'];

		$this->theme = $model['theme'];

		$this->extraData = $model['extraData'];

		$this->confirmationEmailToUser = $model['confirmationEmailToUser'];

		$this->emailAddressField = $model['emailAddressField'];

		$this->replyToEmail = $model['replyToEmail'];

		$this->submitBtn = $model['submitBtn'];

		$this->submitBtnText = $model['submitBtnText'];

		$this->errorMessages = $model['errorMessages'];

		$this->style = $model['style'];

	}

	public function getModel() { return apply_filters( 'smform_model_form', $this->model ); }
	public function getId() { return $this->id; }
	public function getTitle() { return $this->title; }
	public function getDescription() { return $this->description; }
	public function getFields() { return $this->fields; }
	public function isCaptcha() { return $this->captcha; }
	public function getLanguage() { return $this->lang; }
	public function getLabelPlacement() { return $this->labelPlacement; }
	public function getConfirmationOption() { return $this->confirmationOption; }
	public function getTextMessage() { return $this->textMessage; }
	public function getRedirectUrl() { return $this->redirectUrl; }
	public function getNoOfEntries() { return $this->noOfEntries; }
	public function isOnlySingleIP() { return $this->onlySingleIP; }
	public function isFormTimer() { return $this->formTimer; }
	public function getFormStartTime() { return $this->formStartTime; }
	public function getFormEndTime() { return $this->formEndTime; }
	public function getTheme() { return $this->theme; }
	public function getExtraData() { return $this->extraData; }
	public function getConfirmationEmailToUser() { return $this->confirmationEmailToUser; }
	public function getEmailAddressField() { return $this->emailAddressField; }
	public function getReplyToEmail() { return $this->replyToEmail; }
	public function getSubmitBtn() { return $this->submitBtn; }
	public function getSubmitBtnText() { return $this->submitBtnText; }
	public function getErrorMessages() { return $this->errorMessages; }

	public function totalFields() { return count( $this->getFields() ); }

	public function getStyle() { return $this->style; }

	public function getFormMeta( $key, $single = true ) {

		return get_post_meta( $this->id, $key, $single );

	}

	public function getModelKey( $key ) {

		$model = $this->getModel();

		if ( ! isset( $model[$key] ) )
			return false;

		return $model[$key];

	}

	public function getFieldByCssId( $id ) {

		$fields = $this->getFields();

		foreach( $fields as $field ) {

			if ( $field['cssID'] === $id )
				return $field;

		}

		return false;

	}

	public function getFieldByLabel( $label ) {

		if ( empty( $label ) )
			return false;

		$fields = $this->getFields();

		foreach( $fields as $field ) {

			if ( $field['label'] === $label )
				return $field;

		}

		return false;

	}

	public function isFieldRequired( $id ) {

		if ( is_array( $id ) )
			return $id['required'];

		$field = $this->getFieldByCssId( $id );

		if ( $field )
			return $field['required'];

		return null;

	}

	public function isFieldValueDuplicate( $id, $value ) {

		/**
		Return FALSE if value is empty. Duplicate Rule will not work for empty
		Values.
		**/
		
		if ( empty( $value ) )
			return FALSE;

		$tmpv = preg_replace('/\s+/', '', $value);

		if ( empty( $tmpv ) )
			return FALSE;
		
		if ( ! is_array( $id ) )
			$field = $this->getFieldByCssId( $id );
		else
			$field = $id;

		if ( ! is_array( $value ) )
			$value = esc_sql( $value );

		$value = maybe_serialize( $value );

		$form_id = $this->getId();

		global $wpdb;

		$table = $wpdb->prefix . SMUZFORM_ENTRY_DATA_DATABASE_TABLE_NAME;

		$query = $wpdb->prepare( "SELECT id FROM $table where form_id=%d AND field_id=%s AND value=%s LIMIT 1;", $form_id, $field['cssID'], $value );
		
		$query = $wpdb->get_var( $query );

		if ( $wpdb->num_rows > 0 )
			return true;

		return false;

	}

	public function isFieldValueValidMaxLength( $id, $value ) {

		if ( ! is_array( $id ) )
			$field = $this->getFieldByCssId( $id );

		$field = $id;

		$maxLength = str_replace(' ', '', $field['rangeMax']);

		if ( empty( $maxLength ) )
			return true;

		if ( $field['type'] == 'textarea' ) {

			if ( str_word_count( $value ) > $maxLength )
				return false;

			return true;
			
		}

		if ( ! empty( $maxLength ) &&  ( mb_strlen( $value ) > intval($maxLength) ) )
			return null;

		return true;

	}

	public function isFieldValueValidMinLength( $id, $value ) {

		if ( ! is_array( $id ) )
			$field = $this->getFieldByCssId( $id );

		$field = $id;

		$minLength = str_replace(' ', '', $field['rangeMin']);

		if ( empty( $minLength ) )
			return true;

		if ( $field['type'] == 'textarea' ) {

			if ( str_word_count( $value ) < $minLength )
				return false;

			return true;

		}

		if ( ! empty( $minLength ) &&  ( mb_strlen( $value ) < intval($minLength) ) )
			return false;

		return true;

	}

	public function verifyFieldEntryValue( $id, $value ) {

		if ( is_array( $id ) )
			$field = $id;
		else
			$field = $this->getFieldByCssId( $id );

		$errorMessages = $this->getErrorMessages();

		$result = array();

		do_action( 'smuzform_verify_field_value', $field, $value, $this->getId() );
		
		if ( $field['type'] === 'singletext' )
			return $this->verifyFieldValueSingleText( $field, $value );

		if ( $field['type'] === 'number' )
			return $this->verifyFieldValueNumber( $field, $value );

		if ( $field['type'] === 'textarea' )
			return $this->verifyFieldValueTextarea( $field, $value );

		if ( $field['type'] === 'dropdown' )
			return $this->verifyFieldValueDropdown( $field, $value );

		if ( $field['type'] === 'radio' )
			return $this->verifyFieldValueRadio( $field, $value );

		if ( $field['type'] === 'checkbox' )
			return $this->verifyFieldValueCheckbox( $field, $value );

		if ( $field['type'] === 'fileupload' )
			return $this->verifyFieldValueFileUpload( $field, $value );

		if ( $field['type'] === 'name' )
			return $this->verifyFieldValueName( $field, $value );

		if ( $field['type'] === 'email' )
			return $this->verifyFieldValueEmail( $field, $value );

		if ( $field['type'] === 'date' )
			return $this->verifyFieldValueDate( $field, $value );

		if ( $field['type'] === 'address' )
			return $this->verifyFieldValueAddress( $field, $value );

		if ( $field['type'] === 'phone' )
			return $this->verifyFieldValuePhone( $field, $value );

		if ( $field['type'] === 'website' )
			return $this->verifyFieldValueWebsite( $field, $value );		

		return $this->fieldVerifyReturn( true, 'success', '', array(), $field );

	}

	function verifyFieldValueSingleText( $field, $value ){

		$dt = array();

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);
			
			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( ! $this->isFieldValueValidMinLength( $field, $value ) ) {

			$dt['errorBy'] = 'minLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_MINIMUM_LENGTH, $this->getId() ) . $field['rangeMin'];

			return $this->fieldVerifyReturn( false, '110', smuzform_translate( $errStr ), $dt, $field );

		}

		if ( ! $this->isFieldValueValidMaxLength( $field, $value ) ) {

			$dt['errorBy'] = 'maxLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_MAXIMUM_LENGTH, $this->getId() ) . $field['rangeMax'];

			return $this->fieldVerifyReturn( false, '120', smuzform_translate( $errStr ), $dt, $field );

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();

	}

	function verifyFieldValueNumber( $field, $value ){

		$dt = array( 'errorBy' => 'isNumeric' );

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( ! is_numeric( $value ) && str_replace( ' ', '', $value) !== '' ) {

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_NUMERIC, $this->getId() );

			return $this->fieldVerifyReturn( false, '200',  $errStr , $dt, $field );

		}

		if ( ! $this->isFieldValueValidMinLength( $field, $value ) ) {

			$dt['errorBy'] = 'minLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NUMBER_MINIMUM_LENGTH, $this->getId() ) . $field['rangeMin'];

			return $this->fieldVerifyReturn( false, '310', $errStr, $dt, $field );

		}

		if ( ! $this->isFieldValueValidMaxLength( $field, $value ) ) {

			$dt['errorBy'] = 'maxLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NUMBER_MAXIMUM_LENGTH, $this->getId() ) . $field['rangeMax'];

			return $this->fieldVerifyReturn( false, '320', $errStr, $dt, $field );

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();

	}

	function verifyFieldValueTextarea( $field, $value ){

		$dt = array();

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( ! $this->isFieldValueValidMinLength( $field, $value ) ) {

			$dt['errorBy'] = 'minLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_TEXTAREA_MINIMUM_LENGTH, $this->getId() ) . $field['rangeMin'];

			return $this->fieldVerifyReturn( false, '310', $errStr, $dt, $field );

		}

		if ( ! $this->isFieldValueValidMaxLength( $field, $value ) ) {

			$dt['errorBy'] = 'maxLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_TEXTAREA_MAXIMUM_LENGTH, $this->getId() ) . $field['rangeMax'];

			return $this->fieldVerifyReturn( false, '320', $errStr, $dt, $field );

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueDropdown( $field, $value ){

		$dt = array( 'errorBy' => 'dropdown' );

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueRadio( $field, $value ){

		$dt = array( 'errorBy' => 'radio' );

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueCheckbox( $field, $value ){

		$dt = array( 'errorBy' => 'checkbox' );

		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueFileUpload( $field, $value ){

		$dt = array( 'errorBy' => 'fileupload' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueName( $field, $value ){

		$dt = array( 'errorBy' => 'name' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$firstName = str_replace( ' ', '', $value['name']['firstName'] ); 

			$lastName = str_replace( ' ', '', $value['name']['lastName'] );
			
			if ( empty( $firstName ) || empty( $lastName ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueEmail( $field, $value ){

		$dt = array( 'errorBy' => 'email' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( ! is_email( $value ) && ! empty( $tempStr ) ) {

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_EMAIL, $this->getId() );

			$dt['errorBy'] = 'is_email';

			return $this->fieldVerifyReturn( false, '400', $errStr, $dt, $field );

		}

		if ( ! $this->isFieldValueValidMinLength( $field, $value ) ) {

			$dt['errorBy'] = 'minLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_MINIMUM_LENGTH, $this->getId() ) . $field['rangeMin'];

			return $this->fieldVerifyReturn( false, '410', smuzform_translate( $errStr ), $dt, $field );

		}

		if ( ! $this->isFieldValueValidMaxLength( $field, $value ) ) {

			$dt['errorBy'] = 'maxLength';

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_MAXIMUM_LENGTH, $this->getId() ) . $field['rangeMax'];

			return $this->fieldVerifyReturn( false, '420', smuzform_translate( $errStr ), $dt, $field );

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();
	}

	function verifyFieldValueDate( $field, $value ){

		$dt = array( 'errorBy' => 'date' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( $value === 'invalid' ) {

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_INVALID_DATE, $this->getId() );

			$dt['errorBy'] = 'invalidInput';

			return $this->fieldVerifyReturn( false, '500', $errStr, $dt, $field );
		}

		return $this->fieldVerifyReturn();

	}

	function verifyFieldValueAddress( $field, $value ){

		$dt = array( 'errorBy' => 'date' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		return $this->fieldVerifyReturn();

	}

	function verifyFieldValueWebsite( $field, $value ){

		$dt = array( 'errorBy' => 'date' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( filter_var( $value, FILTER_SANITIZE_URL ) === false && ! empty( $value ) ) {

			$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_URL, $this->getId() );

			$dt['errorBy'] = 'invalidwebsite';

			return $this->fieldVerifyReturn( false, '700', $errStr, $dt, $field );

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();

	}

	function verifyFieldValuePhone( $field, $value ){

		$dt = array( 'errorBy' => 'phone' );
		
		if ( $this->isFieldRequired( $field )  ) { 

			$tempStr = str_replace( ' ', '', $value);

			if ( empty( $tempStr ) ) {

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED, $this->getId() );

				$dt['errorBy'] = 'required';

				return $this->fieldVerifyReturn( false, '600', $errStr, $dt, $field );

			}

		}

		if ( $field['noDuplicates'] ) {

			if ( $this->isFieldValueDuplicate( $field, $value ) ) {
				
				$dt['errorBy'] = 'duplicateValue';

				$errStr = SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NO_DUPLICATE, $this->getId() );

				return $this->fieldVerifyReturn( false, '2000', smuzform_translate( $errStr ), $dt, $field );

			}
		}

		return $this->fieldVerifyReturn();

	}


	function fieldVerifyReturn( $sig=true, $code='success', $message='', $dt = array(), $field = null ) {

		if ( $field ) {
			$dt['fieldId'] = $field['cssID'];
			$dt['fieldLabel'] = $field['label'];
			$dt['errorTrigger'] = $field['type'];
		}

		if ( ! isset( $dt['formId'] ) )
			$dt['formId'] = $this->getId();

		$returnValue = array( 
			'signal' => $sig, 
			'code' => $code, 
			'message' => $message,
			'additionalData' => $dt
		);

		$returnValue = apply_filters( 'smuzform_verify_return_value', $returnValue );
		
		return $returnValue;

	}

	public function getFormActionUrl( $action = 'html' ) {

		$nonce = wp_create_nonce( 'smuzform-storage-ajax-referer' );

		if ( $action == 'html' )
			$url = 'admin-ajax.php?action=smuzform-storage&method=submit&formId='. $this->getId() . '&nonce=' . $nonce;
		else
			$url = 'admin-ajax.php?action=smuzform-storage&method=ajax&formId='. $this->getId() . '&nonce=' . $nonce;

		$url = admin_url( $url  );


		return $url;

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

    /*Removes Layout and Html element fields */
    function getFilterFields( $allowed = null ) {

    	$fields = $this->getFields();
    	$_filter = array();

    	if ( ! $allowed ) {
    		$allowed = array( 'singletext', 'number', 'textarea', 'dropdown', 'radio', 'checkbox', 'fileupload', 'name', 'email', 'date', 'address', 'phone', 'website', 'likert' );
    	}

    	$allowed = apply_filters( 'smuzform_filter_fields', $allowed  );

    	foreach ( $fields as $key => $field ) {
    		
    		if ( in_array( $field['type'], $allowed  ) )
    			$_filter[$key] = $field;
    	}

    	return $_filter;

    }

    function isMultiPage() {

    	foreach ( $this->getFields() as $field ) {

    		if ( $field['type'] === 'pagebreak' )
    			return true;

    	}

    	return false;

    }

    function getMultiPageFields() {

    	if ( ! $this->isMultiPage() )
    		return array();

    	$fields = $this->getFields();

    	$_filter = array();

    	$currPage = 0;

    	foreach ( $fields as $key => $field ) {

    		if ( $field['type'] !== 'pagebreak' ) {

    			$field['pageid'] = $currPage;

    			$_filter[] = $field;

    		} else {

    			$currPage = $currPage + 1;

    		}

    	}

    	$_temp = array();

    	foreach ( $_filter as $key => $field ) {

    		$_temp[$field['pageid']]['fields'][] = $field;

    	}

    	$_filter = $_temp;

    	return $_filter;

    }

    function getPageTitle( $id ) {

    	$fields = $this->getFields();

    	$pages = array();

    	foreach( $fields as $field ) {
    		
    		if ( $field['type'] === 'pagebreak' )
    			$pages[] = $field;

    	}

    	if ( ! isset( $pages[$id]['label'] ) )
    		return 'Page';

    	return $pages[$id]['label'];

    }


	private $id;

	private $model;

	private $title;

	private $description;

	private $fields;

	private $captcha;

	private $lang;

	private $labelPlacement;

	private $confirmationOption;

	private $textMessage;

	private $redirectUrl;

	private $noOfEntries;

	private $onlySingleIP;

	private $formTimer;

	private $formStartTime;

	private $formEndTime;

	private $theme;

	private $extraData;

	private $confirmationEmailToUser;

	private $emailAddressField;

	private $replyToEmail;

	private $submitBtn;

	private $submitBtnText;

	private $errorMessages;

}