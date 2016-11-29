<?php
/**
Return error and form validation messages.

Apply filters for modular code.
**/

class SmuzForm_Error_Msg {

	function __construct(){}


	public static function get( $code, $form_id = null, $lang = 'en' ) {

		if ( ! is_int( $code ) )
			return null;

		$errors = self::getAll( $form_id, $lang );

		return apply_filters( 'smuzform_get_error_msg', $errors[$code], $code, $form_id, $lang );

	}

	public static function getAll( $form_id = null, $lang = 'en' ) {

		$errors = array( 
			
			SMUZFORM_ERROR_FIELD_REQUIRED => smuzform_translate( 'This field is required.'),

			SMUZFORM_ERROR_FIELD_MINIMUM_LENGTH => smuzform_translate( 'The value you have entered is too short. Minimum Length is ' ),

			SMUZFORM_ERROR_FIELD_MAXIMUM_LENGTH => smuzform_translate( 'The value you have entered is too large. Maximum Length is ' ),

			SMUZFORM_ERROR_FIELD_NO_DUPLICATE => smuzform_translate( 'You have entered a value that already exists.' ),

			SMUZFORM_ERROR_FIELD_NUMBER_MINIMUM_LENGTH => smuzform_translate( 'The digits you have entered should be more than '),

			SMUZFORM_ERROR_FIELD_NUMBER_MAXIMUM_LENGTH => smuzform_translate( 'The digits you have entered should be less than '),

			SMUZFORM_ERROR_FIELD_TEXTAREA_MINIMUM_LENGTH => smuzform_translate( 'The words you have entered should be more than '),

			SMUZFORM_ERROR_FIELD_TEXTAREA_MAXIMUM_LENGTH => smuzform_translate( 'The words you have entered should be less than '),

			SMUZFORM_ERROR_FIELD_NOT_NUMERIC => smuzform_translate( 'The value you have entered is not a numeric value' ),

			SMUZFORM_ERROR_FIELD_NOT_EMAIL => smuzform_translate( 'Please enter a valid email address.' ),

			SMUZFORM_ERROR_FIELD_INVALID_DATE => smuzform_translate( 'Date is not valid.' ),

			SMUZFORM_ERROR_FIELD_NOT_URL => smuzform_translate( 'URL is not valid.' )

		 );
		
		return apply_filters( 'smuzform_get_errors', $errors, $form_id, $lang );

	}


}