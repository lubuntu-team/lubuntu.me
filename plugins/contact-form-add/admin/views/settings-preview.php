<div id="inline-preview-cont">

<form id="formCont" role="form">

	<fieldset>
	
	<div id="formPreviewCont"></div>
	
	

	<ul id="formFieldsCont"></ul>

	</fieldset>

</form>

<div class="center-block">
	
	<p class="alert alert-info hidden"> 
		<?php smuzform_translate_e( '+ No Fields - Add new Fields From Fields List' ) ?>
	</p>

</div>

<?php if ( isset( $_GET['form_id'] ) ): ?>

<div class="saveFormBtn">
	<a class="button btn-primary btn-large" id="saveForm">Save Form</a>	
</div>

<?php else: ?>

<div class="createFormBtn">
	<a class="button btn-primary btn-large" id="saveForm">Create Form</a>
</div>

<?php endif; ?>

<?php include smuzform_admin_view( 'backbone-views.php' ) ?>

</div>