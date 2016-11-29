<?php if ( $type == 'fileupload' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

		<input name="<?php echo $cssID ?>" id="smform-field<?php echo $form->getId() . $key ?>"
		   	class="smform-controllabelpos smform-control <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
		   	type="file" <?php echo ( $required ) ? 'required ': ''  ?> date-type="fileupload" />

<?php endif; ?>

<?php if ( $type == 'name' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>
<div class="smFormInlineFormCont">		
		<div class="smInlineForm">
			<span>
			<label><input name="smFieldData[<?php echo $cssID ?>][name][firstName]" id="smform-fieldfname<?php echo $form->getId() . $key ?>"
			   	class="smFieldFirstName smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="name" /> First</label></span>

			<span><label><input name="smFieldData[<?php echo $cssID ?>][name][lastName]" id="smform-fieldlname<?php echo $form->getId() . $key ?>"
			   	class="smFieldLastName smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="name" /> Last</label></span>
		</div>
</div>
		<div style="clear:both"></div>

<?php endif; ?>

<?php if ( $type == 'email' ): ?>
		
<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<input name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>"
	value="<?php esc_attr_e($preValue) ?>"
	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
	class="smform-control smform-controllabelpos <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
	type="email" <?php echo ( $required ) ? 'required ': ''  ?> date-type="email" />

<?php endif; ?>

<?php if ( $type == 'date' ):

$_date = array( 'dd' => '', 'mm' => '', 'yyyy' => '' );
$_tmpdate = explode( '/' ,  $preValue );

if ( is_array( $_tmpdate ) && ! empty( $preValue ) )
	$_date = array( 'dd' => $_tmpdate[0], 'mm' => $_tmpdate[1], 'yyyy' => $_tmpdate[2] );

?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<div class="smFormInlineFormCont">
		<div class="smInlineForm smInlineFormDate">
			<span>
			<label><input name="smFieldData[<?php echo $cssID ?>][date][dd]" id="smform-field-yyyy<?php echo $form->getId() . $key ?>" 
				value="<?php esc_attr_e($_date['dd']) ?>"
			   	class="smFieldDD smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="number" <?php echo ( $required ) ? 'required ': ''  ?> date-type="date"  min="1" max="31" /> DD</label></span>

			<span><label><input name="smFieldData[<?php echo $cssID ?>][date][mm]" id="smform-field-mm<?php echo $form->getId() . $key ?>" 
				value="<?php esc_attr_e($_date['mm']) ?>"
			   	class="smFieldMM smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="number" <?php echo ( $required ) ? 'required ': ''  ?> date-type="date"  min="1" max="12" /> MM</label></span>

			<span><label><input name="smFieldData[<?php echo $cssID ?>][date][yyyy]" id="smform-field-yyyy<?php echo $form->getId() . $key ?>" 
				value="<?php esc_attr_e($_date['yyyy']) ?>"
			   	class="smFieldDD smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="number" <?php echo ( $required ) ? 'required ': ''  ?> date-type="date" min="1" max="9999" /> YYYY</label></span>
		</div>
</div>
		<div style="clear:both"></div>

<?php endif; ?>

<?php if ( $type == 'address' ): ?>
		
<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<div class="smFormInlineFormCont">

<div class="smFormAddressMargin"><label><input name="smFieldData[<?php echo $cssID ?>][address][streetAddress]" id="smform-fieldstreetaddress<?php echo $form->getId() . $key ?>"
	value=""
	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
	class="smform-control <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="email" />Street Address</label> </div>

<div class="smFormAddressMargin"><label><input name="smFieldData[<?php echo $cssID ?>][address][streetAddress2]" id="smform-fieldaddress2<?php echo $form->getId() . $key ?>"
	value=""
	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
	class="smform-control <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="email" />Address 2</label></div>

		<div class="smInlineForm smInlineFormAddress">
			<span>
			<label><input name="smFieldData[<?php echo $cssID ?>][address][city]" id="smform-fieldcity<?php echo $form->getId() . $key ?>"
			   	class="smFieldCity smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="name" /> City</label></span>

			<span><label><input name="smFieldData[<?php echo $cssID ?>][address][state]" id="smform-fieldstate<?php echo $form->getId() . $key ?>"
			   	class="smFieldState smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="name" /> State / Province</label></span>
		</div>

		<div style="clear:both;"></div>

		<div class="smInlineForm smInlineFormAddress">
			<span>
			<label><input name="smFieldData[<?php echo $cssID ?>][address][zip]" id="smform-fieldzip<?php echo $form->getId() . $key ?>"
			   	class="smFieldZip smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="name" /> Postal / Zip Code</label></span>

			<span><label><select name="smFieldData[<?php echo $cssID ?>][address][country]" id="smform-fieldcountry<?php echo $form->getId() . $key ?>"
			   	class="smFieldCountry smform-control smform-field-size<?php echo $size ?> <?php echo ( $required ) ? 'required ': '' ?>"
			    <?php echo ( $required ) ? 'required ': ''  ?> date-type="address" /><?php include smuzform_public_view( 'form/select-county-list.php' ) ?></select> Country</label></span>
		</div>
</div>
		<div style="clear:both"></div>

<?php endif; ?>

<?php if ( $type == 'phone' ): ?>
		
<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<input name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>"
	value="<?php esc_attr_e($preValue) ?>"
	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
	class="smform-controllabelpos smform-control <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="phone" />

<?php endif; ?>

<?php if ( $type == 'website' ): ?>
		
<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<input name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>"
	value="<?php esc_attr_e($preValue) ?>"
	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
	class="smform-controllabelpos smform-control <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
	type="url" <?php echo ( $required ) ? 'required ': ''  ?> date-type="phone" />

<?php endif; ?>

<?php if ( $type == 'pagebreak' ): ?>
		
<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

<div id="smform-field<?php echo $form->getId() . $key ?>"></div>

<?php endif; ?>