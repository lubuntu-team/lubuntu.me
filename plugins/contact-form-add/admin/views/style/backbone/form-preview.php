<h2 style="color: #337ab7; display: none">Live Preview</h2>

<div id="inline-preview-cont">

<form id="formCont" role="form">

	<fieldset>

	<div id="formPreviewCont"></div>
	
	

	<ul id="formFieldsCont"></ul>

	</fieldset>

	<div id="submitBtnCont"></div>

</form>


</div>

<script type="text/template" id="formPreview-template">
	<!-- Form Name -->
	<legend class="smform-header">
		
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

<script type="text/template" id="formSubmitBtn-template">
	<input onclick="return false;" type="submit" id="formSubmitBtn" value="<%- submitBtnText %>" />
</script>

<?php include smuzform_admin_view( 'backbone/form-fields-preview.php' ) ?>