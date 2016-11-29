(function($) {

	$.extend( $.validator.messages, smuzform.errorMessages );

	$('.smform').validate();

	/**
	Enable ajax form.
	**/

	$('form.smform').submit(function() {

		if ( $( ':file', $(this) ).length >= 1 )
			return true;

		if ( ! $('.smform').valid() )
			return false;

		var form = $(this);
		
		var spinner  = $( '.smform-ajax-spinner .sk-circle', form );

		var submitBtn = $( '.smform-submit', form );

		var successMsg = $( '.smform-ajax-msg', form );

		var errorCont = $( '.smform-errors-cont', form );

		var action = $(this).prop( 'action' );

		action = action + '&use=ajax';

		spinner.show();
		submitBtn.prop( 'disabled', true );

		$.post( action, form.serialize(), function( resp ) {

			var response = JSON.parse( resp );

			if ( response.signal ) {

				if ( response.isMsg ) {
					successMsg.text( response.thanksMsg );
					successMsg.fadeIn();
					errorCont.fadeOut();
				} else {
					window.location = response.redirectUrl;
				}

				form[0].reset();

				$( document ).trigger( 'smuzFormSubmitSuccess', [ form, response ] );

			} else {

				var errors = response.errors;
				
				var errorHtml = '';
				
				for (  var i = 0; i < errors.length; i++ ) {
					
					var item = '<li class="smform-error-field"><strong class="smform-error-field-label">'+ errors[i].additionalData.fieldLabel +'! </strong> '+ errors[i].message +'</li>';
					errorHtml = errorHtml + item;

				}

				errorHtml = '<ul>' + errorHtml + '</ul>';

				errorCont.html( errorHtml );

				errorCont.fadeIn();

				$('html, body').animate({
				     scrollTop: errorCont.offset().top - 200   
				   }, 200);

				successMsg.fadeOut();

				$( document ).trigger( 'smuzFormSubmitError', [ form, response ] );

			}


			

		}).always( function() {

			spinner.hide();

			submitBtn.prop( 'disabled', false );

		});

		return false;
	});

	/**
	Bind Conditonal Logic for form fields.
	**/
	$('.smform-fieldcont').each( function( index, elem) {

		var enabled = $(this).data('rule'),
			action = $(this).data('ruleaction'),
			operator = $(this).data('ruleoperator'),
			cmpValue = $(this).data('rulecmpvalue'),
			field = $(this).data('rulefield'),
			parentElementRef = $(this);

		var fieldBind = 'smFieldData[' + field + ']';

		if ( field === '' || action === '' || operator === '' ) return;

		if ( enabled === 'enabled' ) {

			if ( action === 'hide' )
				parentElementRef.show();

			var bindTo = 'input[name="'+fieldBind+'"]';
			
			$(bindTo).on( 'change', function(e) {
				
				var applyRules = false,
					bindToValue = $(this).val();
					revertAction = false;

				if ( operator === 'is' ) {

					if ( ! isNaN( cmpValue ) ) {
						
						if ( parseInt( cmpValue ) === parseInt( bindToValue ) )
							applyRules = true;

					} else {

						if ( cmpValue.toLowerCase() === bindToValue.toLowerCase() )
							applyRules = true;

					}
					

				} else if ( operator === 'isNot' ) {

					if ( ! isNaN( cmpValue ) ) {

						if ( parseInt( cmpValue ) !== parseInt( bindToValue ) )
							applyRules = true;

					} else {

						if ( cmpValue.toLowerCase() !== bindToValue.toLowerCase() )
						applyRules = true;

					}

				} else { applyRules = false; }

				if ( applyRules ) {

					if ( action === 'show' ) {

						parentElementRef.show();

					}

					if ( action === 'hide' ) {

						parentElementRef.hide();
						
					}

				} else {

					if ( action === 'show' )
						parentElementRef.hide();
					else if ( action === 'hide' )
						parentElementRef.show();

				}

			});

		}

	}); //End Logic;

if ( $('.smform-multipage').length ) {
	$(document).ready(function(){
		var current = 1;
		
		widget      = $(".smformpage");
		btnnext     = $(".smform-multipage-next");
		btnback     = $(".smform-multipage-back"); 
		btnsubmit   = $(".smform-multipage-submit");

		// Init buttons and UI
		widget.not(':eq(0)').hide();
		hideButtons(current);
		setProgress(current);

		// Next button click action
		btnnext.click(function(){
			if(current < widget.length){
				// Check validation
				if($(".smform-multipage").valid()){
					widget.show();
					widget.not(':eq('+(current++)+')').hide();
					//setProgress(current);
					manageActiveTab(current);
				}
			}
			hideButtons(current);
		})

		// Submit button click
		btnsubmit.click(function(){
			$('form.smform-multipage').submit();
		});


		// Back button click action
		btnback.click(function(){
			if(current > 1){
				current = current - 2;
				if(current < widget.length){
					widget.show();
					widget.not(':eq('+(current++)+')').hide();
					//setProgress(current);
					manageActiveTab(current);
				}
			}
			hideButtons(current);
		})

	    $('.smform-multipage').validate({ // initialize plugin
			ignore:":not(:visible)"
	    });

	});

	// Change progress bar action
	setProgress = function(currstep){
		var percent = parseFloat(100 / widget.length) * currstep;
		percent = percent.toFixed();
		$(".progress-bar").css("width",percent+"%").html(percent+"%");		
	}

	// Hide buttons according to the current step
	hideButtons = function(current){
		var limit = parseInt(widget.length); 

		$(".action").hide();

		if(current < limit) btnnext.show();
		if(current > 1) btnback.show();
		if (current == limit) { 
			// Show entered values
			$(".display label.lbl").each(function(){
				$(this).html($("#"+$(this).data("id")).val());	
			});
			btnnext.hide(); 
			btnsubmit.show();
		}
	}

	manageActiveTab = function(currstep) {

		if ( currstep < 1 )
			return false;

		 $('.smform-multipage-step').each( function( index, elem ) {

		 	$(this).removeClass('active');

		 	var pageid = parseInt( $(this).data('page') ) + 1;

		 	if ( pageid === currstep  )
		 		$(this).addClass('active');

		 });

	}
}

$('.smformstylehidden').each( function( index, elem ) {

	if ( $(this).val() === '' || $(this).val() === '[]' )
		return false;

	var style = JSON.parse( $(this).val() );

	if ( style.enabled !== true )
		return false;

	var form = $( '#smform-' + $(this).data('formid') );

	var cont = $( '#smformcont-' + $(this).data('formid') );

	var heading = $( '.smform-title', form );

	var description = $( '.smform-description', form );

	var fieldLabel = $( '.smform-field-label', form );

	var fieldInput = $( '.smform-control:not(.radio, .checkbox)', form );

	var submitBtn = $( '.smform-submit', form );

	$( '.smform-error-field', form ).css({
		'color': style.formCont.errorMsgColor + ' !important'
	});


	$( '.smform-labelpostop .smform-field-label', cont ).css ( 'width', '100%' );

	$( '.smInlineForm span label', form ).css( 'color', style.fieldLabel.advancedColor );

	if ( style.formCont.hideHeadingSection === true )
		$( '.smform-header', form ).hide();

	if ( style.submitBtn.alignCenter === true )
		$( '.smform-submitbtn-cont', form ).css( 'text-align', 'center' );

	$( '.smform-controllabelpos-radio', form ).css( 'background-color', 'transparent' );


	$( '.smform-controllabelpos-radio', form ).css( 'color', style.formCont.color );


	cont.css({
		'font-size': style.formCont.fontSize + 'px',
		'color': style.formCont.color,
		'background-color': style.formCont.bgColor,
		'margin': style.formCont.margin,
		'padding': style.formCont.padding
	});

	if ( style.formCont.bgImageUrl !== '' ) {

		cont.css({
			'background-image': "url('"+ style.formCont.bgImageUrl +"')",
			'background-repeat': 'no-repeat',
			'background-size': 'cover',
			'background-position': 'center'
		});

	}

	$('form', cont).css({
		'font-size': style.formCont.fontSize + 'px',
		'color': style.formCont.color
	})

	heading.css({
		'font-size': style.formHeading.fontSize + 'px',
		'color': style.formHeading.color,
		'background-color': style.formHeading.bgColor,
		'margin': style.formHeading.margin,
		'padding': style.formHeading.padding
	});

	description.css({
		'font-size': style.formDescription.fontSize + 'px',
		'color': style.formDescription.color,
		'background-color': style.formDescription.bgColor,
		'margin': style.formDescription.margin,
		'padding': style.formDescription.padding
	});

	$('p', description).css({
		'font-size': style.fontSize + 'px',
		'color': style.formDescription.color
	});

	fieldLabel.css({
		'font-size': style.fieldLabel.fontSize + 'px',
		'color': style.fieldLabel.color,
		'background-color': style.fieldLabel.bgColor,
		'margin': style.fieldLabel.margin,
		'padding': style.fieldLabel.padding,
		'font-weight': style.fieldLabel.fontWeight,
		'margin-left': style.fieldLabel.marginLeft + 'px',
		'margin-right': style.fieldLabel.marginRight + 'px',
		'margin-top': style.fieldLabel.marginTop + 'px',
		'margin-bottom': style.fieldLabel.marginBottom + 'px',
		'padding-left': style.fieldLabel.paddingLeft + 'px',
		'padding-right': style.fieldLabel.paddingRight + 'px',
		'padding-top': style.fieldLabel.paddingTop + 'px',
		'padding-bottom': style.fieldLabel.paddingBottom + 'px',
		'border-color': style.fieldLabel.borderColor,
		'border-width': style.fieldLabel.borderSize + 'px',
		'border-radius': style.fieldLabel.borderRadius + 'px',
		'border-style': style.fieldLabel.borderStyle
	});

	fieldInput.css({

		'font-size': style.fieldInput.fontSize + 'px',
		'font-weight': style.fieldInput.fontWeight,
		'color': style.fieldInput.color,
		'background-color': style.fieldInput.bgColor,
		'margin-left': style.fieldInput.marginLeft + 'px',
		'margin-right': style.fieldInput.marginRight + 'px',
		'margin-top': style.fieldInput.marginTop + 'px',
		'margin-bottom': style.fieldInput.marginBottom + 'px',
		'padding-left': style.fieldInput.paddingLeft + 'px',
		'padding-right': style.fieldInput.paddingRight + 'px',
		'padding-top': style.fieldInput.paddingTop + 'px',
		'padding-bottom': style.fieldInput.paddingBottom + 'px',
		'border-color': style.fieldInput.borderColor,
		'border-width': style.fieldInput.borderSize + 'px',
		'border-radius': style.fieldInput.borderRadius + 'px',
		'border-style': style.fieldInput.borderStyle

	});

	submitBtn.css({

		'font-size': style.submitBtn.fontSize + 'px',
		'font-weight': style.submitBtn.fontWeight,
		'color': style.submitBtn.color,
		'background-color': style.submitBtn.bgColor,
		'margin-left': style.submitBtn.marginLeft + 'px',
		'margin-right': style.submitBtn.marginRight + 'px',
		'margin-top': style.submitBtn.marginTop + 'px',
		'margin-bottom': style.submitBtn.marginBottom + 'px',
		'padding-left': style.submitBtn.paddingLeft + 'px',
		'padding-right': style.submitBtn.paddingRight + 'px',
		'padding-top': style.submitBtn.paddingTop + 'px',
		'padding-bottom': style.submitBtn.paddingBottom + 'px',
		'border-color': style.submitBtn.borderColor,
		'border-width': style.submitBtn.borderSize + 'px',
		'border-radius': style.submitBtn.borderRadius + 'px',
		'border-style': style.submitBtn.borderStyle

	});


	var selectOfSubmitBtn = '#smformcont-' + $(this).data('formid') + ' .smform-submit';

	var selectOfErrorMsg = '#smformcont-' + $(this).data('formid') + ' .smform-error-field';

	var selectOfInlineErrorMsg = '#smformcont-' + $(this).data('formid') + ' .error';

	var selectOfSuccessMsg = '#smformcont-' + $(this).data('formid') + ' .smform-ajax-msg';

	style.submitBtn.hoverBgColor = $('<div />').css('display','none').text(style.submitBtn.hoverBgColor).text();

	style.formCont.errorMsgColor = $('<div />').css('display','none').text(style.formCont.errorMsgColor).text();
	
	style.formCont.successMsgColor = $('<div />').css('display','none').text(style.formCont.successMsgColor).text();

	if ( style.submitBtn.hoverBgColor !== '' )
		$('head').append('<style>'+selectOfSubmitBtn+':hover{background-color : ' + style.submitBtn.hoverBgColor  + ' !important;}</style>');

	if ( style.formCont.successMsgColor !== '' )
		$('head').append('<style>'+selectOfSuccessMsg+'{color : ' + style.formCont.successMsgColor  + ' !important;}</style>');

	if ( style.formCont.errorMsgColor !== '' ) {

		$('head').append('<style>'+selectOfErrorMsg+'{color : ' + style.formCont.errorMsgColor  + ' !important;}</style>');

		$('head').append('<style>'+selectOfInlineErrorMsg+'{color : ' + style.formCont.errorMsgColor  + ' !important;}</style>');

	}

	if ( style.formCont.successMsgFontSize !== '' )
		$('head').append('<style>'+selectOfSuccessMsg+'{font-size : ' + parseInt( style.formCont.successMsgFontSize )  + 'px !important;}</style>');


	if ( style.formCont.hideFormOnSuccessForSuccessMsg === true ) {

		$( document ).on( 'smuzFormSubmitSuccess', function( event, form, response ) {
			form.html( $( '<div />' ).addClass( 'smform-ajax-msg' ).html( $('.smform-ajax-msg', form).text() ).fadeIn() );
		});
	}

});

})(jQuery);