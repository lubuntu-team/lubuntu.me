<!DOCTYPE html>

<html lang="en">
<head>

	<meta name="robots" content="noindex">

	<title><?php smuzform_translate_e('Form') ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	
	<style>
		<?php include smuzform_public_view( 'css/skeleton.css' ) ?>
	</style>
</head>

<body>
	
	<div class="container">
		
		<section class="header">
			
			<?php if ( ! empty( $errors ) ): ?>
			<h2 class="title"><?php esc_html_e( $form->getTitle() ) ?> <?php smuzform_translate_e( 'Form Errors:' ) ?></h2>
			<?php else: ?>
				
			<h2 class="title"><?php esc_html_e( $form->getTitle() ) ?> <?php smuzform_translate_e( 'Success:' ) ?></h2>
			<p class="description"><?php esc_html_e( $form->getTextMessage() ) ?></p>
			<?php endif; ?>

		</section>
	
	<?php if ( ! empty( $errors ) ): ?>
		<div id="errors">
			<ul>
				<?php foreach( $errors as $err ): ?>
					
				<li> 
					<strong><?php echo $err['additionalData']['fieldLabel'] ?>: </strong>
					<span class="errorMsg"><?php echo $err['message'] ?></span>
				</li>

				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

		<footer>
			
			<div id="backBtnCont">
				<a class="button button-primary" href="<?php echo $returnLink ?>"><?php smuzform_translate_e( 'Go Back' ) ?></a>
				<small>
					<?php smuzform_translate_e( '' ) ?>
				</small>
			</div>

		</footer>

	</div>

</body>


</html>