(function($) {

//SmuzForm

StyleManager = {
  Models: {},
  Collections: {},
  Views: {},
  Dispatcher: _.clone(Backbone.Events),
};


StyleManager.Models.StyleSettings = Backbone.Model.extend({
	
	initialize: function() { },

	defaults: function() {

		return {

			enabled: false,

			formCont: {
				font: '',
				fontSize: 14,
				color: '#000000',
				bgColor: '#ffffff',
				margin: '',
				padding: '',
				bgImg: '',
				hideHeadingSection: false,
				errorMsgColor: '#a94442',
				successMsgColor: '#3c763d',
				successMsgFontSize: 14,
				hideFormOnSuccessForSuccessMsg: false,
				bgImageUrl: ''
			},
			
			formHeading: {
				font: '',
				fontSize: '24',
				color: '',
				bgColor: '',
				padding: '',
				margin: '',
			},

			formDescription: {
				font: '',
				fontSize: '13',
				color: '',
				bgColor: '',
				padding: '',
				margin: '',
			},

			fieldLabel: {
				font: '',
				fontSize: '14',
				color: '',
				bgColor: '',
				paddingLeft: '',
				paddingRight: '',
				paddingTop: '',
				paddingBottom: '',
				marginLeft: '',
				marginRight: '',
				marginTop: '',
				marginBottom: '',
				fontWeight: 'bold',
				borderColor: '#ccc',
				borderSize: '0',
				borderStyle: 'solid',
				borderRadius: '0',
				advancedColor: ''
			},

			fieldInput: {
				font: '',
				fontSize: '14',
				color: '',
				bgColor: '',
				paddingLeft: '12',
				paddingRight: '12',
				paddingTop: '6',
				paddingBottom: '6',
				marginLeft: '0',
				marginRight: '0',
				marginTop: '0',
				marginBottom: '0',
				fontWeight: 'bold',
				borderColor: '#ccc',
				borderSize: '1',
				borderStyle: 'solid',
				borderRadius: '4'
			},

			submitBtn: {
				font: '',
				fontSize: '16',
				color: '#ffffff',
				bgColor: '#333',
				paddingLeft: 26,
				paddingRight: 26,
				paddingBottom: 13,
				paddingTop: 13,
				margin: '',
				fontWeight: 'bold',
				hoverBgColor: '#707070',
				borderColor: '#ccc',
				borderSize: '0',
				borderStyle: 'solid',
				borderRadius: '0',
				hoverBgColor: '',
				alignCenter: false
			},

			extraData: {}


		};
	}

});

StyleManager.Models.FormSettings = Backbone.Model.extend({

	initialize: function() { },

	defaults: function() {

		return {
			tmpid: '',
			fields: {},
			title: 'Form Name',
			description: 'Click me to change the description.',
			lang: 'en',
			labelPlacement: 'top',
			confirmationOption: 'text',
			textMessage: 'Thanks for submitting your information',
			redirectUrl: '',
			captcha: false,
			noOfEntries: '',
			onlySingleIP: false,
			formTimer: false,
			formStartTime: null,
			formEndTime: null,
			theme: {},
			extraData: {},
			confirmationEmailToUser: false,
			emailAddressField: '',
			replyToEmail: '',
			submitBtn: true,
			submitBtnText: 'Submit',
			submitBtnType: 'normal',
			errorMessages: {},
			style: {},
			mailchimpAddOnData: {},
			addOnData: {}

		};

	},

	url: ajaxurl+'?action=smuzform_api&nonce='+smuzform.form_model_nonce

});

StyleManager.Models.Field = Backbone.Model.extend({

	validate: function( attr ) {

		if ( attr.type === undefined  )
			return 'Set Field Type';
	},

	initialize: function() {

		this.on( 'invalid', function( model, error ) {

			console.log( error );

		} );

		this.on( 'change:label', function( model ) {

		} );

	},

	defaults: function() { 
		return {

			label: 'Untitled Label',
			
			type: undefined,
			
			size: 'medium',
			
			required: false,
			
			noDuplicates: false,
			
			visibility: true,

			labelVisible: true,
			
			rangeMin: '',
			
			rangeMax: '',
			
			rangeFormat: 'char',

			preValue: '',

			helpText: '',

			cssClasses: '',

			placeholderText: '',

			radios: false,

			checkboxes: false,

			textAreaRows: 10,

			textAreaCols: 60,

			cssID: 'wpfieldid',

			choices: ['First Choice','Second Choice', 'Third Choice'],

			selectedChoice: null,

			checkboxSelectedChoice: [],

			sectionTitle: 'Section Title',

			sectionDescription: 'Click me to change the description',

			extraData: null,

			ruleEnabled: false,

			rules: {
				field: '',
				operator: 'is',
				cmpValue: '',
				action: 'show'
			},

			statements: {},

			columns: {},

			file: { 
				
				allowed: [ 'jpg','jpeg','png','gif','pdf','doc','docx','key','ppt','pptx','pps','ppsx','odt','xls','xlsx','zip','mp3','m4a','ogg','wav','mp4','m4v','wmv','avi','mpg','ogv','3gp','3g2' ],
				maxSize: 2

			},

			login: {}

		};
	}

});

StyleManager.Collections.FieldsCol = Backbone.Collection.extend({
	model: StyleManager.Models.Field
});


StyleManager.Views.FieldsCollectionView = Backbone.View.extend({

		

		tagName: 'li',

		settingsView: null,

		initialize: function() {

			this.model.on( 'change', this.render, this );

			this.template = _.template( $('#fieldsView-template').html() );

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;

		}

});

StyleManager.Views.FormPreview = Backbone.View.extend({

		initialize: function() { 

			this.template = _.template( $('#formPreview-template').html() );

			this.model.on( 'change', this.render, this );

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;

		}

});


StyleManager.Views.StyleSettings = Backbone.View.extend({

	initialize: function() {

		this.template = _.template( $('#formStyle-template').html() );

		//this.model.on( 'change', this.render, this );

	},

	render: function() {

		this.$el.html( this.template( this.model.attributes ) );

		$('.colorpickerfield').wpColorPicker({

			change: _.throttle( function () { $(this).trigger('change'); }, 300 ),
			palettes: true,

		});

		this.applyStylesToCont();
		this.applyStylesToHeading();
		this.applyStylesToDescription();
		this.applyStylesToFieldLabel();
		this.applyStylesToFieldInput();
		this.applyStylesToSubmitBtn();
		
		return this;
	},

	events: {

		'click #styleEnabled': 'changeStyleEnabled',

		//Form Cont Events
		'change #styleContFontSize': 'changeContFontSize',
		'change #styleContColor': 'changeContColor',
		'change #styleContBgColor': 'changeContBgColor',
		'change #styleContMargin': 'changeContMargin',
		'change #styleContPadding': 'changeContPadding',
		'click #hideHeadingSection': 'changeHideHeadingSection',
		'change #styleContBgImageUrl': 'changeContBgImageUrl',
		//End of Form Cont Events

		//Form Heading Events
		'change #styleHeadingFontSize': 'changeHeadingFontSize',
		'change #styleHeadingColor': 'changeHeadingColor',
		'change #styleHeadingBgColor': 'changeHeadingBgColor',
		'change #styleHeadingMargin': 'changeHeadingMargin',
		'change #styleHeadingPadding': 'changeHeadingPadding',
		//End of Form Heading Events

		//Form Description Events
		'change #styleDescriptionFontSize': 'changeDescriptionFontSize',
		'change #styleDescriptionColor': 'changeDescriptionColor',
		'change #styleDescriptionBgColor': 'changeDescriptionBgColor',
		'change #styleDescriptionMargin': 'changeDescriptionMargin',
		'change #styleDescriptionPadding': 'changeDescriptionPadding',
		//End of Form Description Events

		//Form FieldLabel Events
		'change #styleFieldLabelFontSize': 'changeFieldLabelFontSize',
		'change #styleFieldLabelColor': 'changeFieldLabelColor',
		'change #styleFieldLabelBgColor': 'changeFieldLabelBgColor',
		'change #styleFieldLabelMarginRight': 'changeFieldLabelMarginRight',
	    'change #styleFieldLabelMarginTop': 'changeFieldLabelMarginTop',
	    'change #styleFieldLabelMarginBottom': 'changeFieldLabelMarginBottom',
	    'change #styleFieldLabelPaddingLeft': 'changeFieldLabelPaddingLeft',
	    'change #styleFieldLabelPaddingRight': 'changeFieldLabelPaddingRight',
	    'change #styleFieldLabelPaddingTop': 'changeFieldLabelPaddingTop',
	    'change #styleFieldLabelPaddingBottom': 'changeFieldLabelPaddingBottom',
	    'change #styleFieldLabelBorderColor': 'changeFieldLabelBorderColor',
	    'change #styleFieldLabelBorderSize': 'changeFieldLabelBorderSize',
	    'change #styleFieldLabelBorderRadius': 'changeFieldLabelBorderRadius',
	    'change #styleFieldLabelBorderStyle': 'changeFieldLabelBorderStyle',
		'click #styleFieldLabelFontWeight': 'changeFieldLabelFontWeight',
		'change #styleFieldAdvancedColor': 'changeFieldLabelAdvancedColor',
		//End of Form FieldLabel Events

		//Form FieldInput Events
		'change #styleFieldInputFontSize': 'changeFieldInputFontSize',
		'change #styleFieldInputColor': 'changeFieldInputColor',
		'change #styleFieldInputBgColor': 'changeFieldInputBgColor',
		'change #styleFieldInputMarginLeft': 'changeFieldInputMarginLeft',
		'change #styleFieldInputMarginRight': 'changeFieldInputMarginRight',
		'change #styleFieldInputMarginTop': 'changeFieldInputMarginTop',
		'change #styleFieldInputMarginBottom': 'changeFieldInputMarginBottom',
		'change #styleFieldInputPaddingLeft': 'changeFieldInputPaddingLeft',
		'change #styleFieldInputPaddingRight': 'changeFieldInputPaddingRight',
		'change #styleFieldInputPaddingTop': 'changeFieldInputPaddingTop',
		'change #styleFieldInputPaddingBottom': 'changeFieldInputPaddingBottom',
		'click #styleFieldInputFontWeight': 'changeFieldInputFontWeight',
		'change #styleFieldInputBorderColor': 'changeFieldInputBorderColor',
		'change #styleFieldInputBorderSize': 'changeFieldInputBorderSize',
		'change #styleFieldInputBorderRadius': 'changeFieldInputBorderRadius',
		'change #styleFieldInputBorderStyle': 'changeFieldInputBorderStyle',
		//End of Form FieldInput Events

		//Form SubmitBtn Events
	    'change #styleSubmitBtnFontSize': 'changeSubmitBtnFontSize',
	    'change #styleSubmitBtnColor': 'changeSubmitBtnColor',
	    'change #styleSubmitBtnBgColor': 'changeSubmitBtnBgColor',
	    'change #styleSubmitBtnMarginLeft': 'changeSubmitBtnMarginLeft',
	    'change #styleSubmitBtnMarginRight': 'changeSubmitBtnMarginRight',
	    'change #styleSubmitBtnMarginTop': 'changeSubmitBtnMarginTop',
	    'change #styleSubmitBtnMarginBottom': 'changeSubmitBtnMarginBottom',
	    'change #styleSubmitBtnPaddingLeft': 'changeSubmitBtnPaddingLeft',
	    'change #styleSubmitBtnPaddingRight': 'changeSubmitBtnPaddingRight',
	    'change #styleSubmitBtnPaddingTop': 'changeSubmitBtnPaddingTop',
	    'change #styleSubmitBtnPaddingBottom': 'changeSubmitBtnPaddingBottom',
	    'click #styleSubmitBtnFontWeight': 'changeSubmitBtnFontWeight',
	    'change #styleSubmitBtnBorderColor': 'changeSubmitBtnBorderColor',
	    'change #styleSubmitBtnBorderSize': 'changeSubmitBtnBorderSize',
	    'change #styleSubmitBtnBorderRadius': 'changeSubmitBtnBorderRadius',
	    'change #styleSubmitBtnBorderStyle': 'changeSubmitBtnBorderStyle',
	    'change #styleSubmitBtnHoverBgColor': 'changeSubmitBtnHoverBgColor',
	    'click #styleSubmitBtnAlignCenter': 'changeSubmitBtnAlignCenter',
	    //End of Form SubmitBtn Events

	    //Form Messages Events
	    'change #styleMsgErrorColor': 'changeErrorMsgColor',
	    'change #styleMsgSuccessColor': 'changeSuccessMsgColor',
	    'change #styleSuccessMsgFontSize': 'changeSuccessMsgFontSize',
	    'click #styleHideFormOnSuccessForSuccessMsg': 'changeHideFormOnSuccessForSuccessMsg'
	    //End of Form Messages Events.
	},

	changeStyleEnabled: function(e) {

		if ( $(e.currentTarget).is(':checked') )
			this.model.set( 'enabled', true );
		else
			this.model.set( 'enabled', false );

	},


	applyStylesToCont: function(){

		var style = _.clone( this.model.get( 'formCont' ) );


		$('#inline-preview-cont').css({

			'font-size': style.fontSize + 'px',
			'color': style.color,
			'background-color': style.bgColor,
			'margin': style.margin,
			'padding': style.padding

		});

		if ( style.hideHeadingSection )
			$('.smform-header').hide();
		else
			$('.smform-header').show();

		if ( style.bgImageUrl !== '' ) {

			$('#inline-preview-cont').css({
				'background-image': "url('"+ style.bgImageUrl +"')",
				'background-repeat': 'no-repeat',
				'background-size': 'cover',
				'background-position': 'center'
			});
		}

		
	},

	changeErrorMsgColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.errorMsgColor = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	changeSuccessMsgColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.successMsgColor = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	changeHideFormOnSuccessForSuccessMsg: function(e) {

		var temp  = _.clone( this.model.get( 'formCont' ) );

		if ( $(e.currentTarget).is( ':checked' ) )
			temp.hideFormOnSuccessForSuccessMsg = true;
		else
			temp.hideFormOnSuccessForSuccessMsg = false;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();
		
		e.stopImmediatePropagation();

	},

	changeHideHeadingSection: function(e){

		var temp  = _.clone( this.model.get( 'formCont' ) );

		if ( $(e.currentTarget).is(':checked') )
			temp.hideHeadingSection = true;
		else
			temp.hideHeadingSection = false;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	changeSuccessMsgFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.successMsgFontSize = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	changeContFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.fontSize = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},
	changeContColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.color = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},
	changeContBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.bgColor = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},
	changeContMargin: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.margin = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},
	changeContPadding: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.padding = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	changeContBgImageUrl: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formCont' ) );

		temp.bgImageUrl = value;

		this.model.set( 'formCont', temp );

		this.applyStylesToCont();

	},

	//form heading

	applyStylesToHeading: function(){

		var style = _.clone( this.model.get( 'formHeading' ) );


		$('.form-title').css({

			'font-size': style.fontSize + 'px',
			'color': style.color,
			'background-color': style.bgColor,
			'margin': style.margin,
			'padding': style.padding

		});

		
	},

	changeHeadingFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formHeading' ) );

		temp.fontSize = value;

		this.model.set( 'formHeading', temp );

		this.applyStylesToHeading();

	},
	changeHeadingColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formHeading' ) );

		temp.color = value;

		this.model.set( 'formHeading', temp );

		this.applyStylesToHeading();

	},
	changeHeadingBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formHeading' ) );

		temp.bgColor = value;

		this.model.set( 'formHeading', temp );

		this.applyStylesToHeading();

	},
	changeHeadingMargin: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formHeading' ) );

		temp.margin = value;

		this.model.set( 'formHeading', temp );

		this.applyStylesToHeading();

	},
	changeHeadingPadding: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formHeading' ) );

		temp.padding = value;

		this.model.set( 'formHeading', temp );

		this.applyStylesToHeading();

	},

	//form description

	applyStylesToDescription: function(){

		var style = _.clone( this.model.get( 'formDescription' ) );

		$('.form-description').css({

			'font-size': style.fontSize + 'px',
			'color': style.color,
			'background-color': style.bgColor,
			'margin': style.margin,
			'padding': style.padding

		});

		$('.form-description p').css({

			'font-size': style.fontSize + 'px'

		});

		
	},

	changeDescriptionFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );
		
		var temp  = _.clone( this.model.get( 'formDescription' ) );

		temp.fontSize = value;

		this.model.set( 'formDescription', temp );

		this.applyStylesToDescription();

	},
	changeDescriptionColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formDescription' ) );

		temp.color = value;

		this.model.set( 'formDescription', temp );

		this.applyStylesToDescription();

	},
	changeDescriptionBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formDescription' ) );

		temp.bgColor = value;

		this.model.set( 'formDescription', temp );

		this.applyStylesToDescription();

	},
	changeDescriptionMargin: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formDescription' ) );

		temp.margin = value;

		this.model.set( 'formDescription', temp );

		this.applyStylesToDescription();

	},
	changeDescriptionPadding: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'formDescription' ) );

		temp.padding = value;

		this.model.set( 'formDescription', temp );

		this.applyStylesToDescription();

	},

	//field label

	applyStylesToFieldLabel: function(){

		var style = _.clone( this.model.get( 'fieldLabel' ) );

		$('#formFieldsCont .form-group label').css({
			'color': style.advancedColor
		});

		$('#formFieldsCont .form-group .smfieldlabel').css({
			'font-weight': style.fontWeight,
			'background-color': style.bgColor,
			'font-size': style.fontSize + 'px',
			'width': '100%',
			'color': style.color,
			'margin-left': style.marginLeft + 'px',
			'margin-right': style.marginRight + 'px',
			'margin-top': style.marginTop + 'px',
			'margin-bottom': style.marginBottom + 'px',
			'padding-left': style.paddingLeft + 'px',
			'padding-right': style.paddingRight + 'px',
			'padding-top': style.paddingTop + 'px',
			'padding-bottom': style.paddingBottom + 'px',
			'border-color': style.borderColor,
			'border-width': style.borderSize + 'px',
			'border-radius': style.borderRadius + 'px',
			'border-style': style.borderStyle
		});
		
	},

	changeFieldLabelFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldLabel' ) );

		temp.fontSize = value;

		this.model.set( 'fieldLabel', temp );

		this.applyStylesToFieldLabel();

	},
	changeFieldLabelColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldLabel' ) );

		temp.color = value;

		this.model.set( 'fieldLabel', temp );

		this.applyStylesToFieldLabel();

	},
	changeFieldLabelBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldLabel' ) );

		temp.bgColor = value;

		this.model.set( 'fieldLabel', temp );

		this.applyStylesToFieldLabel();

	},
	changeFieldLabelMarginLeft: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.marginLeft = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},
	changeFieldLabelMarginRight: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.marginRight = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},
	changeFieldLabelMarginTop: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.marginTop = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},
	changeFieldLabelMarginBottom: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.marginBottom= value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},
	changeFieldLabelPaddingLeft: function(e){

	      var value = parseInt( $(e.currentTarget).val() );

	      var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	      temp.paddingLeft = value;

	      this.model.set( 'fieldLabel', temp );

	      this.applyStylesToFieldLabel();

	},
	changeFieldLabelPaddingRight: function(e){

	      var value = parseInt( $(e.currentTarget).val() );

	      var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	      temp.paddingRight = value;

	      this.model.set( 'fieldLabel', temp );

	      this.applyStylesToFieldLabel();

	},
	changeFieldLabelPaddingTop: function(e){

	      var value = parseInt( $(e.currentTarget).val() );

	      var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	      temp.paddingTop = value;

	      this.model.set( 'fieldLabel', temp );

	      this.applyStylesToFieldLabel();

	},
	changeFieldLabelPaddingBottom: function(e){

	      var value = parseInt( $(e.currentTarget).val() );

	      var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	      temp.paddingBottom = value;

	      this.model.set( 'fieldLabel', temp );

	      this.applyStylesToFieldLabel();

	},
	changeFieldLabelBorderColor: function(e){

	    var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.borderColor = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},

	changeFieldLabelBorderSize: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.borderSize = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();


	},
	changeFieldLabelBorderRadius: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.borderRadius = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},
	changeFieldLabelBorderStyle: function(e){

	    var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.borderStyle = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},

	changeFieldLabelAdvancedColor: function(e){

	    var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldLabel' ) );

	    temp.advancedColor = value;

	    this.model.set( 'fieldLabel', temp );

	    this.applyStylesToFieldLabel();

	},

	changeFieldLabelFontWeight: function(e){

		var value = 'bold';

		if ( $(e.currentTarget).is(':checked') )
			value = 'bold';
		else
			value = 'normal';

		var temp  = _.clone( this.model.get( 'fieldLabel' ) );

		temp.fontWeight = value;

		this.model.set( 'fieldLabel', temp );

		this.applyStylesToFieldLabel();

	},

	//field input

	applyStylesToFieldInput: function(){

		var style = _.clone( this.model.get( 'fieldInput' ) );

		$('.smformfield').css({

			'font-size': style.fontSize + 'px',
			'font-weight': style.fontWeight,
			'color': style.color,
			'background-color': style.bgColor,
			'margin-left': style.marginLeft + 'px',
			'margin-right': style.marginRight + 'px',
			'margin-top': style.marginTop + 'px',
			'margin-bottom': style.marginBottom + 'px',
			'padding-left': style.paddingLeft + 'px',
			'padding-right': style.paddingRight + 'px',
			'padding-top': style.paddingTop + 'px',
			'padding-bottom': style.paddingBottom + 'px',
			'border-color': style.borderColor,
			'border-width': style.borderSize + 'px',
			'border-radius': style.borderRadius + 'px',
			'border-style': style.borderStyle

		});

		
	},

	changeFieldInputFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.fontSize = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.color = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.bgColor = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputMarginLeft: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.marginLeft = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputMarginRight: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.marginRight = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputMarginTop: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.marginTop = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputMarginBottom: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.marginBottom= value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},
	changeFieldInputPaddingLeft: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.paddingLeft = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputPaddingRight: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.paddingRight = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputPaddingTop: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.paddingTop = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputPaddingBottom: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.paddingBottom = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputBorderColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.borderColor = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},

	changeFieldInputBorderSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.borderSize = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();


	},
	changeFieldInputBorderRadius: function(e){

		var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.borderRadius = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputBorderStyle: function(e){

		var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'fieldInput' ) );

	    temp.borderStyle = value;

	    this.model.set( 'fieldInput', temp );

	    this.applyStylesToFieldInput();

	},
	changeFieldInputFontWeight: function(e){

		var value = 'bold';

		if ( $(e.currentTarget).is(':checked') )
			value = 'bold';
		else
			value = 'normal';

		var temp  = _.clone( this.model.get( 'fieldInput' ) );

		temp.fontWeight = value;

		this.model.set( 'fieldInput', temp );

		this.applyStylesToFieldInput();

	},

	applyStylesToSubmitBtn: function(){

		var style = _.clone( this.model.get( 'submitBtn' ) );


		if ( style.alignCenter )
			$('#submitBtnCont').css( 'text-align', 'center' );
		else
			$('#submitBtnCont').css( 'text-align', '' );

		$('#formSubmitBtn').css({

			'font-size': style.fontSize + 'px',
			'font-weight': style.fontWeight,
			'color': style.color,
			'background-color': style.bgColor,
			'margin-left': style.marginLeft + 'px',
			'margin-right': style.marginRight + 'px',
			'margin-top': style.marginTop + 'px',
			'margin-bottom': style.marginBottom + 'px',
			'padding-left': style.paddingLeft + 'px',
			'padding-right': style.paddingRight + 'px',
			'padding-top': style.paddingTop + 'px',
			'padding-bottom': style.paddingBottom + 'px',
			'border-color': style.borderColor,
			'border-width': style.borderSize + 'px',
			'border-radius': style.borderRadius + 'px',
			'border-style': style.borderStyle

		});

		if ( style.hoverBgColor !== '' )
			$('head').append('<style>#formSubmitBtn:hover{background-color : ' + style.hoverBgColor  + ' !important;}</style>');

	},

	changeSubmitBtnFontSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.fontSize = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.color = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnBgColor: function(e){
		
		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.bgColor = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnMarginLeft: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.marginLeft = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnMarginRight: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.marginRight = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnMarginTop: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.marginTop = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnMarginBottom: function(e){

		var value = parseInt( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.marginBottom= value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnPaddingLeft: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.paddingLeft = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnPaddingRight: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.paddingRight = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnPaddingTop: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.paddingTop = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnPaddingBottom: function(e){

	    var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.paddingBottom = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnBorderColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.borderColor = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},

	changeSubmitBtnBorderSize: function(e){

		var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.borderSize = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();


	},
	changeSubmitBtnBorderRadius: function(e){

		var value = parseInt( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.borderRadius = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnBorderStyle: function(e){

		var value = _.escape( $(e.currentTarget).val() );

	    var temp  = _.clone( this.model.get( 'submitBtn' ) );

	    temp.borderStyle = value;

	    this.model.set( 'submitBtn', temp );

	    this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnFontWeight: function(e){

		var value = 'bold';

		if ( $(e.currentTarget).is(':checked') )
			value = 'bold';
		else
			value = 'normal';

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.fontWeight = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnHoverBgColor: function(e){

		var value = _.escape( $(e.currentTarget).val() );

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		temp.hoverBgColor = value;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	},
	changeSubmitBtnAlignCenter: function(e){

		var temp  = _.clone( this.model.get( 'submitBtn' ) );

		if ( $(e.currentTarget).is(':checked') )
			temp.alignCenter = true;
		else
			temp.alignCenter = false;

		this.model.set( 'submitBtn', temp );

		this.applyStylesToSubmitBtn();

	}


});

StyleManager.Views.SubmitBtn = Backbone.View.extend({

	initialize: function() {

		this.template = _.template( $('#formSubmitBtn-template').html() );

	},

	render: function() {

		this.$el.html( this.template( this.model.attributes ) );

		return this;
	}

});

StyleManager.App = {
	
	init: function() {

		var FormModel = new StyleManager.Models.FormSettings();
		var FieldsCol = new StyleManager.Collections.FieldsCol();
		var FormPreviewView = new StyleManager.Views.FormPreview( { model: FormModel, el: $('#formPreviewCont') } );

		var FormStyleModel = new StyleManager.Models.StyleSettings();

		var FormSubmitBtn = new StyleManager.Views.SubmitBtn({
			model: FormModel,
			el: $('#submitBtnCont')
		});

		FormStyleModel.on( 'change', function( model ) {
			
			FormModel.set( 'style', FormStyleModel );

			FormModel.set( 'id', smuzform.form_id );
			
			smuzform.savingStyleModel = false;

			FormModel.save({
				data: { formID: smuzform.form_id }
			}).success( function( resp ) {
				
				smuzform.savingStyleModel = true;

			});

		});


		$('#serverModalCont').show();
		$('body').append($('#serverModalContHtml').html());

		$('#serverModalContHtml').html('');

		FormModel.fetch({
			data: { formID: smuzform.form_id }
		}).success( function( resp ) {

			var fields = FormModel.get('fields');

			_.each( fields, function( field, index ) {
				FieldsCol.add( field );
			});
	

			if ( ! $.isEmptyObject( FormModel.get('style') ) )
				FormStyleModel.attributes = FormModel.get('style');

			smuzform.isDataLoaded = true;

			$('#serverModalCont').hide();

		}).done(function() {
			
			StyleManager.Views.FieldsListView = new Backbone.CollectionView({

				el: $('#formFieldsCont'),

				sortable: false,

				modelView: StyleManager.Views.FieldsCollectionView,

				collection: FieldsCol,

				emptyListCaption: '<div id="noFieldsMessage" class="alert alert-warning"><p></p></div>',

				reuseModelViews: false,

				selectable: false

			});

			var FormStyleView = new StyleManager.Views.StyleSettings({ model: FormStyleModel, el: $('#formStyleCont') });

			FormPreviewView.render();

			setTimeout( function(){
				FormStyleView.render();
			}, 200 );
		
			StyleManager.Views.FieldsListView.render();

			FormSubmitBtn.render();

		});


		


	}

};

$(document).ready(function() {
	StyleManager.App.init();
});

$(document).ready(function(){$('.toplevel_page_smuz-forms-main').addClass('current');})

})(jQuery);