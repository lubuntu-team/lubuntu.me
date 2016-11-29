<?php

class SmuzForm_Public {

	function loadClasses() {

		if ( ! class_exists( 'SmuzForm_Error_Msg' ) )
			smuzform_admin( 'core/class/class-smuzform-error-message.php' );

		smuzform_public( 'core/class/class-smuzform-form.php' );
		
		smuzform_public( 'core/class/class-smuzform-public-render.php' );

	}

	/**
	Register shortcode and add assets
	**/
	public function createUI() {

		add_action( 'wp_enqueue_scripts', array( $this, 'addAssets' )  );

		add_shortcode( SMUZFORM_SHORTCODE, array( $this, 'doShortcode' ) );

	}

	function addAssets() {

		$style_url = smuzform_public_asset( 'css/form-style.css' );

		wp_enqueue_style( 'smuzform-public-form-render', $style_url );

		$script_url = smuzform_public_asset( 'js/smuzforms.js' );

		$jquery_validate_url = smuzform_public_asset( 'js/jquery-validate.js' );

		wp_enqueue_script( 'jquery-validate', $jquery_validate_url, array('jquery'), SMUZFORM_PLUGIN_VERSION, true );

		wp_enqueue_script( 'smuzformsjs', $script_url, array('jquery'), SMUZFORM_PLUGIN_VERSION, true );

		$js = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'errorMessages' => array(
					
					'required' => SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_REQUIRED ),

					'email' => SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_EMAIL ),

					'number' => SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_NUMERIC ),

					'url' => SmuzForm_Error_Msg::get( SMUZFORM_ERROR_FIELD_NOT_URL )

				)
		);

		wp_localize_script( 'smuzformsjs', 'smuzform', $js );


	}

	function doShortcode( $atts ) {

		$form_id = $atts['id'];

		ob_start();

		try {

			$form = new SmuzForm_Public_Render( $form_id, $atts );

			$form->render();
		
		} catch (Exception $e) {
    	
    		echo $e->getMessage() . "<br />";
		
		}

		$content = ob_get_contents();
		ob_end_clean();

		$content = apply_filters( 'smuzform_doShortcode_output', $content, $form_id );

		return $content;

		

	}

}