<script type="text/template" id="formPreview-template">
	<!-- Form Name -->
	<legend>
		
		<h3 class="form-title">
			<%= title %>
		</h3>

		<div class="form-description">
			<p>
				<%= description %>
			</p>
		</div>

	</legend>
</script>

<script type="text/template" id="nofieldSelected-template">
	<p class="alert alert-warning">
		<?php smuzform_translate_e( 'No field Selected' ) ?>
	</p>
</script>


<?php include smuzform_admin_view( 'backbone/form-settings.php' ) ?>

<?php include smuzform_admin_view( 'backbone/form-field-settings.php' ) ?>

<?php include smuzform_admin_view( 'backbone/form-fields-preview.php' ) ?>

<?php include smuzform_admin_view( 'backbone/form-navbar.php' ); ?>