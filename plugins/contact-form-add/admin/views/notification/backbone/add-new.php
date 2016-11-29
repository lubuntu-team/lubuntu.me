<script type="text/template" id="addNewNotification-template">

<div class="row">
	
	<div class="col-lg-6 col-lg-offset-2">
			
		<div id="notificationTypeSelectCont" class="form-group">
			<label for="notificationTypeSelect"><?php smuzform_translate_e( 'Set Type ' ) ?></label>
			<select id="notificationTypeSelect">
				<option value="email"><?php smuzform_translate_e( 'Email' ) ?></option>
				<option value="confirmationEmail"><?php smuzform_translate_e( 'Confirmation Email / AutoResponder' ) ?></option>
			</select>
		</div>

		<div id="addNewNotificationButtonCont">
			<a id="addNewNotificationButton" class="button button-primaryNot" role="button">Add New</a>
			<a id="saveNotificationButton" class="button button-primaryNot" role="button">Save Changes</a>
		</div>

	</div>



</div>

</script>