<div id="smformcont-<?php echo $form->getId() ?>">
<form class="yui3-cssreset smform smform-multipage smform-labelpos<?php echo ( $form->getLabelPlacement() === 'left' ) ? 'left' : 'top' ?>" id="smform-<?php echo $form->getId() ?>" method="POST" action="<?php echo $form->getFormActionUrl() ?>" role="form" enctype="multipart/form-data" data-formid="<?php echo $form->getId() ?>">
    
    <div class="smform-errors-cont"></div>
    
    <?php do_action( 'smuzform_display_start', $form->getId(), $form ) ?>

    <legend class="smform-header">
        
        <h3 class="smform-title"><?php echo $form->getTitle() ?></h3>

        <div class="smform-description">
            <p><?php echo $form->getDescription() ?></p>
        </div>

    </legend>

    <div class="smform-multipage-steps-cont">
    <ul class="smform-multipage-steps">
    	<?php foreach ( $form->getMultiPageFields() as $key => $page ): ?>

    	<li class="smform-multipage-step <?php echo ( $key === 0 ) ? 'active': '' ?>" data-page="<?php echo $key ?>">
    		
    		<strong><?php echo esc_html( $form->getPageTitle( $key ) ) ?></strong>
    		
    	</li>

    	<?php endforeach; ?>
    </ul>
    </div>

    <div class="smform-grid" id="smform<?php echo $form->getId() ?>"> 

    	
    	<?php foreach ( $form->getMultiPageFields() as $page_id => $page ): ?>
    	
    	<div class="smformpage smformpage<?php echo $page_id ?>">
    		
            <?php foreach ( $page['fields'] as $key => $field ): extract( $field, EXTR_OVERWRITE ); $key = $key + $page_id ?>
    			<?php include smuzform_public_view( 'form/multipage/render-fields.php' ) ?>
    		<?php endforeach; ?>

            <?php do_action( 'smuzform_display_at_end_of_page', $form->getId(), $form, $page_id ) ?>
    	</div>

    	<?php endforeach; ?>



    </div>

   <div class="smform-multipage-btncont">
        <button type="button" class="action smform-multipage-back">Back</button>
        <button type="button" class="action smform-multipage-next">Next</button>
        <button type="button" class="action smform-multipage-submit smform-submit">Submit</button>
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

    <div id="smuzform-robot" style="display: none">
        <label>If you're not a fish leave this field blank:</label>
        <input name="smuztrapfish" type="text" style="display: none" />
    </div>

    <input type="hidden" name="_formId" value="<?php echo $form->getId() ?>" />
    <input type="hidden" name="_returnLink" value="<?php the_permalink() ?>" />
    
    <input type="hidden" data-formid="<?php echo $form->getId() ?>" value='<?php echo json_encode( $form->getStyle() ) ?>' id="smformstyle-<?php echo $form->getId() ?>" class="smformstylehidden" />

    <?php do_action( 'smuzform_display_end', $form->getId(), $form ) ?>

</form>

</div> <!-- cont close div -->

