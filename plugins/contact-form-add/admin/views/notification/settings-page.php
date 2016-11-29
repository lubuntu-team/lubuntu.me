<div id="smuzform-cont">

<nav class="navbar navbar-inverse mainnavbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand">
				<?php smuzform_translate_e( 'Notification Manager' ) ?>
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

	<div id="addNewNotificationEL"></div>

	<div id="notificationCollectionCont"></div>
	
	<?php do_action( 'smuzform_notification_manager_add' ) ?>
	
</div>


</div>

<?php include smuzform_admin_view( 'notification/backbone/add-new.php' ) ?>

<?php include smuzform_admin_view( 'notification/backbone/collection.php' ) ?>