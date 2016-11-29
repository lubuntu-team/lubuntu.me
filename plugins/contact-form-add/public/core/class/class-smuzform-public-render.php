<?php

class SmuzForm_Public_Render {

	private $form_id = null;

	private $atts = array();

	private $cache = false;

	function __construct( $form_id, $atts = null ) {

		$this->form_id = $form_id;

		$this->atts = $atts;

		$form = get_post( $form_id );

		if ( $form->post_type !== 'smuzform' || $form->post_status !== 'publish' )
			throw new Exception( smuzform_translate_e( 'Form Not Exist.' ) );

		if ( ! get_post_meta( $form_id, 'model', true ) )
			throw new Exception( smuzform_translate_e( 'Form Model Not Exist.' ));

	}

	public function render() {

		$form = new SmuzForm_Form( $this->form_id );

		$multipage_path = apply_filters( 'smuzform_render_form_multipage_path', smuzform_public_view( 'form/multipage/render.php' ) );

		$single_path = apply_filters( 'smuzform_render_form_path', smuzform_public_view( 'form/render.php' ) );

		if ( $form->isMultiPage() )
			include ( $multipage_path );
		else
			include ( $single_path );

	}


	function isCached() {}

	function getFromCache() {}

	function updateCache() {}

}