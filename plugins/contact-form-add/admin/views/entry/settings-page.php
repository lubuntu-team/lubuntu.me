<?php if ( ! isset( $_GET['entry_id'] ) ): ?>
<div id="smuzform-cont">

<nav class="navbar navbar-inverse mainnavbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand">
				<?php smuzform_translate_e( 'Entry Manager' ) ?>
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
	<div class="row">

		<div class="col-xs-3 contInfoCard formInfoCard">

			<div class="contInfoInlineCont">
				<h3><?php esc_html_e( $entryManager->getTitle() ) ?></h3>

				<p><?php esc_html_e( $entryManager->getDescription() ) ?></p>
			</div>

		</div>

		<?php do_action( 'smuzform_admin_entry_card_display', $entryManager->getId(), $entryManager ) ?>

		<div class="col-xs-3 contInfoCard entryCountCard">
			<div class="contInfoInlineCont">
				<h3>No of Entries</h3>

				<h4 id="entriesCountHeading"><?php echo $entryManager->getCount() ?></h4>
			</div>
		</div>

	</div>

	<div class="row">
		
		<div id="entryDataGridCont">
			<?php include smuzform_admin_view( 'entry/datatable.php' ) ?>	
		</div>

	</div>
</div>


</div>

<?php else: ?>
<?php include smuzform_admin_view( 'entry/edit-entry.php' ) ?>
<?php endif; ?>