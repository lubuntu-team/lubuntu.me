<?php

class SmuzForm_Entry extends SmuzForm_Form {

	private $entryTableName = 'smuzform_entry';
	private $fieldEntryTableName = 'smuzform_entry_data';
	private $entry_id;
	private $noOfEntries = null;

	function __construct( $form_id ) {

		global $wpdb;

		$this->entryTableName = $wpdb->prefix . SMUZFORM_ENTRY_DATABASE_TABLE_NAME;

		$this->fieldEntryTableName = $wpdb->prefix . SMUZFORM_ENTRY_DATA_DATABASE_TABLE_NAME;

		parent::__construct( $form_id );
	}

	public function getCount() {

		if ( is_int( $this->noOfEntries ) )
			return $this->noOfEntries;

		global $wpdb;

		$table = $this->getEntryTableName();

		$query = $wpdb->prepare( "SELECT COUNT(id) FROM $table WHERE form_id=%d;", $this->getId() );

		$count = $wpdb->get_var( $query );

		$this->noOfEntries = $count;

		return intval( $count );

	}

	public function getEntries( $start = 0, $number = 10, $orderBy = 'ASC' ) {

		global $wpdb;

		$totalEntries = $this->getCount();

		$entryTable = $this->getEntryTableName();
		$fieldEntryTable = $this->getEntryFieldTableName();

		if ( $number > $totalEntries )
			$number = $totalEntries;

		$orderBy = esc_sql( $orderBy );

		$queryForEntries = $wpdb->prepare( "SELECT id FROM $entryTable WHERE form_id=%d ORDER BY created_at $orderBy LIMIT %d, %d;", $this->getId(), $start, $number );

		$entryIds = $wpdb->get_results( $queryForEntries );

		
		$inString = '';
		foreach( $entryIds as $entry ) {
			$inString = $inString . intval( $entry->id ) . ',';
		}
		$inString = trim( $inString, ',' );

		if ( empty( $inString ) )
			return array();

		$queryForFields = $wpdb->prepare( "SELECT id,entry_id,field_id,value FROM $fieldEntryTable WHERE entry_id IN($inString) AND form_id=%d;", $this->getId());

		$entryFieldData = $wpdb->get_results( $queryForFields );

		$entries = $this->filterEntries( $entryFieldData );

		return $entries;

	}

	function filterEntries( $rows ) {

		$_filter = array();

		$totalFields = count( $this->getFilterFields() );

		foreach ( $rows as $key => $entryField ) {
			
			$fieldModel = $this->getFieldByCssId( $entryField->field_id );

			if ( ! $fieldModel  )
				continue;

			$value = maybe_unserialize( $entryField->value );

			if ( $fieldModel['type'] === 'fileupload' )
				$value = array( 'fileupload' => array( 'path' => $value ) );
			
			if( is_array( $value ) ) {

				if ( key( $value ) === 'name' ) {
					$value = $value['name']['firstName'] . ' ' . $value['name']['lastName'];
				} else if ( key( $value ) === 'checkbox' ) {
					//$value = 
				} else if ( key( $value ) === 'address' ) {
					//$value = 
				} else if ( key( $value ) === 'fileupload' ) {
					//$value = 
				}

				$value = apply_filters( 'smuzforms_entry_filter_isarrayvalue', $value );
			} else {

				$value = esc_html( $value );

			}

			if ( mb_strlen( $value ) > 210 && $fieldModel['type'] !== 'fileupload' )
				$value = mb_substr( $value, 0, 210) . ' ...';
			
			$value = apply_filters( 'smuzforms_entry_filter_value', $value );
			
			$prepare[$entryField->entry_id][] = $value;

			$_filter = $prepare;

		}

		/**
		Add null values for new created fields. Prevent JS errors on datatable.
		Check for null attribute instead of blank value.
		**/
		foreach ( $_filter as $key => $arr ) {

			$arrCount = count( $arr );

			$diff = $totalFields - $arrCount;

			for ( $i = 0; $i < $diff; $i++ ){ $_filter[$key][] = null; }
		}

		
		return $_filter;
	}

	/**
	Return the entries matching search query.
	**/
	function searchEntries( $keyword, $orderBy = 'DESC' ) {

		global $wpdb;
		
		$orderBy = esc_sql( $orderBy  );

		$like = '%'.$wpdb->esc_like( $keyword ) . '%';

		$fieldEntryTable = $this->getEntryFieldTableName();

		$query = $wpdb->prepare( "SELECT entry_id FROM $fieldEntryTable WHERE value LIKE \"%s\" AND form_id=%d ORDER BY entry_id $orderBy;", $like, $this->getId());

		$queryForSearch = apply_filters( 'smuzform_entry_search_query', $query  );

		$entryIds = $wpdb->get_results( $queryForSearch );

		$inString = '';
		foreach( $entryIds as $field ) {
			$inString = $inString . intval( $field->entry_id ) . ',';
		}
		$inString = trim( $inString, ',' );

		if ( empty( $inString ) )
			return array();
		
		$queryForFields = $wpdb->prepare( "SELECT id,entry_id,field_id,value FROM $fieldEntryTable WHERE entry_id IN($inString) AND form_id=%d ORDER BY entry_id $orderBy;", $this->getId());

		$entryFieldData = $wpdb->get_results( $queryForFields );

		$entries = $this->filterEntries( $entryFieldData );

		return $entries;
	}

	function getEntry( $entry_id ) {

		$fieldEntryTable = $this->getEntryFieldTableName();

		global $wpdb;

		$query = $wpdb->prepare( "SELECT * FROM $fieldEntryTable WHERE entry_id=%d AND form_id=%d;", $entry_id, $this->getId() );

		$fields = $wpdb->get_results( $query );

		$_filter = array();

		foreach ( $fields as $key => $entryField ) {
			
			$fieldModel = $this->getFieldByCssId( $entryField->field_id );

			if ( ! $fieldModel  )
				continue;

			$value = maybe_unserialize( $entryField->value );

			if ( $fieldModel['type'] === 'fileupload' )
				$value = array( 'fileupload' => array( 'path' => $value, 'pathinfo' => pathinfo( $value ) ) );

			$_filter[] = array( 
				'id' => $entryField->id,
				'cssID' => $entryField->field_id,
				'value' => $value,
				'type' => $fieldModel['type'],
				'label' => $fieldModel['label']
			);

		}

		return apply_filters( 'smuzform_entry_manager_get_entry', $_filter, $entry_id, $this->getId() );

	}

	function getEntryUserInfo( $entry_id ) {

		$entryTable = $this->getEntryTableName();

		global $wpdb;

		$query = $wpdb->prepare( "SELECT * FROM $entryTable WHERE id=%d AND form_id=%d;", $entry_id, $this->getId() );

		$entry = $wpdb->get_row( $query );

		return $entry;

	}

	function getEntryTableName() {

		return $this->entryTableName;

	}

	function getEntryFieldTableName() {

		return $this->fieldEntryTableName;

	}

	
}