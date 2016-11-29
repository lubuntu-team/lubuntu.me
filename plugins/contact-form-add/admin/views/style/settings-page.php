<div id="smuzform-cont">

<nav class="navbar navbar-inverse mainnavbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand">
				<?php smuzform_translate_e( 'Form Style Manager' ) ?>
			</a>
			
			<ul class="nav navbar-nav navbar-right" id="navBarActionsCont">
				<li class="navBarActionsLi">
					<a id="formEntriesAction" href="<?php echo admin_url( 'admin.php?page=smuz-forms&form_id='.intval($_GET['form_id']) ); ?>"><?php smuzform_translate_e( 'Go Back to Form Builder <' ) ?></a>
				</li>
			</ul>
			
		
		</div>


	</div>
</nav>

<div class="container">

	<!-- Style Elements and Settings -->
		
		<div class="col-lg-4 smuzform-settings smuzform-style-settings">

			<?php include smuzform_admin_view( 'style/backbone/form-style.php' ) ?>
		
		</div>

		<!-- end -->

		<!-- Form Preview and View !-->

		<div class="col-md-6 smuzform-preview">

			<?php include smuzform_admin_view( 'style/backbone/form-preview.php' ) ?>
		
		</div>

		<!-- end !-->
	
	<?php do_action( 'smuzform_style_manager_add' ) ?>
	
</div>


</div>

<div id="serverModalContHtml">

	<div id="serverModalCont">
		<p id="serverModalMessage">
			Loading
		</p>	
		<div class="loadingSpinner">
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

</div>