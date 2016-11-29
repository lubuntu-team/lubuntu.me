<div id="smuzform-cont">

<nav class="navbar navbar-inverse mainnavbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo admin_url( 'admin.php?page=smuz-forms-main' ) ?>">
				<?php smuzform_translate_e( 'WP Forms' ) ?>
			</a>
			
			<ul class="nav navbar-nav navbar-right" id="navBarActionsCont"></ul>
			
		
		</div>


	</div>
</nav>

<div class="container">
	<div class="row">

		<!-- Form Elements and Settings -->
		
		<div class="col-lg-4 smuzform-settings">

			<ul class="nav nav-tabs">

				<li class="active">
					<a data-toggle="tab" href="#tab-fields">
						<?php smuzform_translate_e( 'Add Field' ) ?>
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tab-field-settings">
						<?php smuzform_translate_e( 'Field Settings' ) ?>
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#tab-form-settings">
						<?php smuzform_translate_e( 'Form Settings' ) ?>
					</a>
				</li>

			</ul>

			<?php include smuzform_admin_view( 'settings-tabs.php' ) ?>
		
		</div>

		<!-- end -->

		<!-- Form Preview and View !-->

		<div class="col-md-6 smuzform-preview">

			<?php include smuzform_admin_view( 'settings-preview.php' ) ?>
		
		</div>

		<!-- end !-->

	</div>
</div>


</div>
<div id="serverModalCont">
	<p id="serverModalMessage"></p>	
</div>

<?php if ( isset( $_GET['form_id'] ) ):  $form_id = intval( $_GET['form_id'] ) ?>

	<?php if ( get_post_status( $form_id  ) === 'publish' ): ?>
		<script> window.smuz_formid = <?php echo $form_id ?>; </script>
	<?php endif; ?>

<?php endif; ?>