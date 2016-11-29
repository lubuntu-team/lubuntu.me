<div id="smformcont-<?php echo $form->getId() ?>">
<form class="yui3-cssreset smform smform-labelpos<?php echo ( $form->getLabelPlacement() === 'left' ) ? 'left' : 'top' ?>" id="smform-<?php echo $form->getId() ?>" method="POST" action="<?php echo $form->getFormActionUrl() ?>" role="form" enctype="multipart/form-data" data-formid="<?php echo $form->getId() ?>">
	
	<div class="smform-errors-cont"></div>
	
	<?php do_action( 'smuzform_display_start', $form->getId(), $form ) ?>

	<legend class="smform-header">
		
		<h3 class="smform-title"><?php echo $form->getTitle() ?></h3>

		<div class="smform-description">
			<p><?php echo $form->getDescription() ?></p>
		</div>

	</legend>

	<?php foreach( $form->getFields() as $key => $field ): extract( $field, EXTR_OVERWRITE ); ?>


	<div class="smform-fieldcont smform-fieldcont<?php echo $type ?> <?php esc_attr_e( $cssClasses ) ?>" id="smform-fieldcont<?php echo $form->getId() . $key ?>" 
		data-key="smform-field<?php echo $form->getId() . $key ?>" 
		data-rule="<?php echo ( $ruleEnabled ) ? 'enabled': 'disabled' ?>" 
		data-ruleoperator="<?php echo $rules['operator'] ?>"
		data-ruleaction="<?php echo $rules['action'] ?>"
		data-rulecmpvalue="<?php echo $rules['cmpValue'] ?>"
		data-rulefield="<?php echo $rules['field'] ?>" >

		<?php do_action( 'smuzform_showfield_start', $field, $form->getId() ) ?>
	
		<?php if ( $type == 'singletext' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

		<input name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>"
		 	value="<?php esc_attr_e($preValue) ?>"
		 	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
		   	class="smform-control smform-controllabelpos <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
		   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> date-type="text" />

		<?php endif; ?>

		<?php if ( $type == 'number' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

		<input name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>"
		 	value="<?php esc_attr_e($preValue) ?>"
		 	placeholder="<?php esc_attr_e( $placeholderText ) ?>"
		   	class="smform-control smform-controllabelpos <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>"
		   	type="text" <?php echo ( $required ) ? 'required ': ''  ?> 
		   	data-type="number" />

		<?php endif; ?>

		<?php if ( $type == 'textarea' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

		<textarea name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>" 
			placeholder="<?php esc_attr_e( $placeholderText ) ?>" 
			class="smform-control smform-controllabelpos <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?> smform-control smform-controllabelpostextarea" 
			<?php echo ( $required ) ? 'required ': ''  ?> 
			data-type="textarea" 
			><?php echo esc_html($preValue) ?></textarea>

		<?php endif; ?>

		<?php if ( $type == 'dropdown' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>

		<select name="smFieldData[<?php echo $cssID ?>]" id="smform-field<?php echo $form->getId() . $key ?>" 
			class="smform-control smform-controllabelpos <?php echo ( $required ) ? 'required ': '' ?> smform-field-size<?php echo $size ?>" 
			<?php echo ( $required ) ? 'required ': ''  ?> 
			data-type="select" >
		
			<?php foreach ( $choices as $choicekey => $choice ): ?>

			<option <?php echo ($choicekey === $selectedChoice ) ? 'selected': '' ?> value="<?php esc_attr_e( $choice ) ?>"><?php echo esc_html( $choice ) ?></option>
		
			<?php endforeach; ?>
		
		</select>

		<?php endif; ?>

		<?php if ( $type == 'radio' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>
			
			<div class="smform-radio-cont">
			<?php foreach ( $choices as $choicekey => $choice ): ?>
			
			<div class="smform-control smform-controllabelpos-radio radio">
				<label><input name="smFieldData[<?php echo $cssID ?>]" type="radio" <?php echo ($choicekey === $selectedChoice ) ? 'checked': '' ?> value="<?php esc_attr_e( $choice ) ?>" <?php echo ( $required ) ? 'required ': ''  ?> data-type="radio" /> <?php echo esc_html( $choice ) ?></label>
			</div>
		
			<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<?php if ( $type == 'checkbox' ): ?>
		
		<label for="smform-field<?php echo $form->getId() . $key ?>" class="smform-field-label <?php echo ( $required ) ? 'required ': ''; echo ( ! $labelVisible ) ? 'smform-labelhidden': ''  ?>"><?php echo esc_html( $label ) ?></label>
			
			<div class="smform-checkbox-cont">
			<?php foreach ( $choices as $choicekey => $choice ): ?>
			
			<div class="smform-control smform-controllabelpos-radio checkbox">
				<label><input name="smFieldData[<?php echo $cssID ?>][checkbox][]" type="checkbox" <?php echo ($choicekey === $selectedChoice ) ? 'checked': '' ?> value="<?php esc_attr_e( $choice ) ?>" <?php echo ( $required ) ? 'required ': ''  ?> data-type="checkbox" /> <?php echo esc_html( $choice ) ?></label>
			</div>
		
			<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<?php if ( $type == 'linebreak' ): ?>
		<hr />
		<?php endif; ?>

		<?php if ( $type == 'sectionbreak' ): ?>
		<legend>
		
			<h3 class="smsection-title"><?php echo esc_html( $sectionTitle ) ?></h3>

			<div class="smsection-description">
				<p><?php echo esc_html( $sectionDescription ) ?></p>
			</div>

		</legend>
		<?php endif; ?>

		<?php include smuzform_public_view( 'form/render-advanced-fields.php' ) ?>

		<?php include smuzform_public_view( 'form/render-html-fields.php' ) ?>

		<?php do_action( 'smuzform_showfield_end', $field, $form->getId() ) ?>

	</div>

	<?php endforeach; ?>

	
	<div id="smuzform-robot" style="display: none">
		<label>If you're not a fish leave this field blank:</label>
		<input name="smuztrapfish" type="text" style="display: none" />
	</div>

	
	<?php do_action( 'smuzform_display_before_submit_btn', $form->getId(), $form ) ?>

	<div class="smform-submitbtn-cont">
		<input type="submit" value="<?php echo $form->getSubmitBtnText() ?>" name="submitForm" class="smform-submit" />
		<div class="smform-ajax-spinner">
			<div class="sk-circle">
		        <div class="sk-circle1 sk-child"></div>
		        <div class="sk-circle2 sk-child"></div>
		        <div class="sk-circle3 sk-child"></div>
		        <div class="sk-circle4 sk-child"></div>
		        <div class="sk-circle5 sk-child"></div>
		        <div class="sk-circle6 sk-child"></div>
		        <div class="sk-circle7 sk-child"></div>
		        <div class="sk-circle8 sk-child"></div>
		        <div class="sk-circle9 sk-child"></div>
		        <div class="sk-circle10 sk-child"></div>
		        <div class="sk-circle11 sk-child"></div>
		        <div class="sk-circle12 sk-child"></div>
		     </div>
		</div>
	</div>

	<div class="smform-ajax-msg"><p class="smform-ajax-msg-p"></p></div>

	<input type="hidden" name="_formId" value="<?php echo $form->getId() ?>" />
	<input type="hidden" name="_returnLink" value="<?php the_permalink() ?>" />

	<input type="hidden" data-formid="<?php echo $form->getId() ?>" value='<?php echo json_encode( $form->getStyle() ) ?>' id="smformstyle-<?php echo $form->getId() ?>" class="smformstylehidden" />

	<?php do_action( 'smuzform_display_end', $form->getId(), $form ) ?>
	
</form>
</div>