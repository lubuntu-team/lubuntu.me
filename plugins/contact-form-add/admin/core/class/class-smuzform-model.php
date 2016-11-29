<?php

class SmuzForm_Model {

	private $post_type = 'smuzform';

	/**
	Create the ajax action url and hook it to WP Ajax API
	**/
	public function init() {

		add_action( 'wp_ajax_smuzform_api', array( $this, 'api' ) );

	}


	/**
	Forward Backbone REST actions to  create/save/get/delete
	**/
	function api() {

		$this->verifyUser();

		if ( $_SERVER['REQUEST_METHOD'] == 'GET' )
			$this->get();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
			$this->create();

		if ( $_SERVER['REQUEST_METHOD'] == 'PUT' )
			$this->save();

		if ( $_SERVER['REQUEST_METHOD'] == 'DELETE' )
			$this->delete();


	}

	/**
	Create a new form in the database with data from backbone model.
	**/
	function create() {

		$data = json_decode( file_get_contents( 'php://input' ), true );

		$form_id = $this->createForm( $data );

		$response = array(
				'method' => 'post',
				'action' => 'create',
				'formID' => $form_id,
				'error' => false,
				'errorMsg' => ''
			);

		echo json_encode( $response );
		
		exit(  );

	}

	/**
	Update form in the database with data from backbone model.
	**/
	function save() {
		
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$form_id = intval($data['id']);
		
		update_post_meta( $form_id, 'model', $data );

		$response = array(
				'method' => 'put',
				'action' => 'save',
				'formID' => $form_id,
				'error' => false,
				'errorMsg' => ''
			);

		wp_update_post( array( 
			'ID' => $form_id,
			'post_title' => sanitize_text_field( $data['title'] ) 
		) );

		echo json_encode( $response );

		exit();

	}

	/**
	Echo data of the form in json format. 
	**/
	function get() {

		$form_id = intval($_GET['formID']);

		$data = get_post_meta( $form_id, 'model', true );

		echo json_encode( $data );

		exit();

	}

	/**
	Delete the form from the database.
	**/
	function delete() {

		$form_id = intval($_GET['formID']);

		if ( get_post_type( $form_id ) !== $this->post_type )
			exit();

		wp_delete_post( $form_id, true );

		exit();

	}

	function createForm( $data ) {

		if ( empty( $data['title'] ) )
			$data['title'] = 'Title Not Set';

		$postarr = array(
				'post_title' => sanitize_text_field( $data['title'] ),
				'post_content' => '',
				'post_type' => 'smuzform',
				'post_status' => 'publish'
			);
		
		$form_id = wp_insert_post( $postarr );
		
		$data['id'] = $form_id;

		update_post_meta( $form_id, 'model', $data );

		return $form_id;

	}

	/**
	Verify user permissions if failed PHP execution is stopped with no message.
	**/
	function verifyUser( $nonce = null ) {

		if ( ! current_user_can( 'manage_options' ) )
			exit;

		if ( is_null( $nonce ) && isset( $_GET['nonce'] ) )
			$nonce = $_GET['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'smuzform_form_model' ) )
			exit;

	}


}