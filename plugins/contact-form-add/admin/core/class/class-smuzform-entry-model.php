<?php

class SmuzForm_Entry_Model {

	private $post_type = 'smuzform';

	/**
	Create the ajax action url and hook it to WP Ajax API
	**/
	public function init() {

		add_action( 'wp_ajax_smuzform_entry_api', array( $this, 'api' ) );

	}

	/**
	Forward Ajax REST actions to create/save/get/delete
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


		exit;
	}

	function get() {

		$form_id = intval($_GET['formID']);

		if ( get_post_type( $form_id ) !== $this->post_type )
			exit();

		$entryManager = new SmuzForm_Entry( $form_id );

		$totalEntries = $entryManager->getCount();

		$totalRecords = $totalEntries;

		$totalDisplayRecords = $totalEntries;

		if ( $totalEntries < SMUZFORM_ENTRIES_PER_PAGE )
			$displayRecords = $totalEntries;
		else
			$displayRecords = SMUZFORM_ENTRIES_PER_PAGE;

		if ( isset( $_GET['length'] ) )
			$displayRecords = intval( $_GET['length'] );

		if ( $displayRecords > $totalEntries )
			$displayRecords = $totalEntries;

		$searchValue = $_GET['search']['value'];

		if ( ! empty( $searchValue ) ) {
			$entries = $entryManager->searchEntries( $searchValue );
		} else {

			if ( isset( $_GET['start'] ) && ! empty( $_GET['start'] ) )
				$start = intval( $_GET['start'] );
			else
				$start = 0;

			$entries = $entryManager->getEntries( $start, $displayRecords, 'DESC' );
		}


		
		

		foreach( $entries as $key => $entry ) {
			
			$entry["DT_RowId"] = (string)intval( $key );
			$entry["DT_RowData"] = (string)intval( $key );
			$jsonentries[] = $entry;
		}

		$k = array_keys($jsonentries);
		$v = array_values($jsonentries);

		$rv = array_reverse($jsonentries);

		$jsonentries = array_combine($k, $rv);

		if ( empty( $jsonentries ) )
			$jsonentries = array();

		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalDisplayRecords,
			"aaData" => $jsonentries
		);

		echo json_encode( $output );

	}

	function create() {}
	function save(){}

	/**
	Delete the entry and it's associated fields from the database.
	**/
	function delete(){

		$entry_id = intval($_GET['entryID']);
		$form_id = intval($_GET['formID']);

		global $wpdb;
		
		$entryTableName = $wpdb->prefix . SMUZFORM_ENTRY_DATABASE_TABLE_NAME;
		
		$entryTableDataName = $wpdb->prefix . SMUZFORM_ENTRY_DATA_DATABASE_TABLE_NAME;

		$entryDelQuery = $wpdb->prepare( "DELETE FROM $entryTableName WHERE id=%d AND form_id=%d", $entry_id, $form_id );

		$entryDataDelQuery = $wpdb->prepare( "DELETE FROM $entryTableDataName WHERE entry_id=%d AND form_id=%d", $entry_id, $form_id );


		$wpdb->query( $entryDataDelQuery );

		$wpdb->query( $entryDelQuery );

		echo json_encode( array(
				'message' => 'Entry Deleted Successfully.',
				'signal' => true
			) );

	}

	/**
	Verify user permissions if failed PHP execution is stopped with no message.
	**/
	function verifyUser( $nonce = null ) {

		if ( ! current_user_can( 'manage_options' ) )
			exit;

		if ( is_null( $nonce ) && isset( $_GET['nonce'] ) )
			$nonce = $_GET['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'smuzform_entry_model' ) )
			exit();

	}


}