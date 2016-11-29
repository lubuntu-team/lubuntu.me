(function($) {

smuzform.App = {
  Models: {},
  Collections: {},
  Views: {},
  Dispatcher: _.clone(Backbone.Events),
};

smuzform.App.isNewForm = function() {

	return true;

};


function smuzform_deentitize_str( str ) {
    var ret = str.replace(/&gt;/g, '>');
    ret = ret.replace(/&lt;/g, '<');
    ret = ret.replace(/&quot;/g, '"');
    ret = ret.replace(/&apos;/g, "'");
    ret = ret.replace(/&amp;/g, '&');
    return ret;
 }

smuzform.App.Models.FormSettings = Backbone.Model.extend({

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
			addOnData: []

		};

	},

	url: ajaxurl+'?action=smuzform_api&nonce='+smuzform.form_model_nonce

});




var Field = Backbone.Model.extend({

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

var FieldsCollection = Backbone.Collection.extend({

	model: Field,
	
	initialize: function() {

		

	}
});



$(document).ready(function() {

	smuzform.App.Views.NavBar = Backbone.View.extend({

		template: _.template( $('#formNavBar-template').html() ),

		el: $('#navBarActionsCont'),

		events: {

			'click #formExportAction': 'onClickFormExportAction',
			'click #formEntriesAction': 'onClickFormEntriesAction',
			'click #formNotificationAction': 'onClickFormNotificationAction',
			'click #formStyleAction': 'onClickFormStyleAction'
		},

		initialize: function( model ) { 

			this.model = model;

			this.model.on( 'change', this.render, this );

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;
		},

		onClickFormExportAction: function(e) {

			if ( ! FormPreviewModel.get('id') ) {

				alert('Please create a form first. Click the create form button below');
				
				return false;

			}

			//this.render();

			FormPreviewModel.set( 'tmpid', FormPreviewModel.get('id') );

			$('#exportModal').modal('show');

		},

		onClickFormEntriesAction: function(e) {

			var redirectLink = smuzform.admin_url+'admin.php?page=smuz-forms-entry&form_id='+FormPreviewModel.get('id');

			if ( ! FormPreviewModel.get('id') )
				alert('Please create a form first. Click the create form button below');
			else
				window.location = redirectLink;

		},

		onClickFormNotificationAction: function(e) {

			var redirectLink = smuzform.admin_url+'admin.php?page=smuz-forms-notifications&form_id='+FormPreviewModel.get('id');

			if ( ! FormPreviewModel.get('id') )
				alert('Please create a form first. Click the create form button below');
			else
				window.location = redirectLink;

		},

		onClickFormStyleAction: function(e) {

			var redirectLink = smuzform.admin_url+'admin.php?page=smuz-forms-style&form_id='+FormPreviewModel.get('id');

			if ( ! FormPreviewModel.get('id') )
				alert('Please create a form first. Click the create form button below');
			else
				window.location = redirectLink;

		}

	});

	

	smuzform.App.Views.FormPreview = Backbone.View.extend({

		template: _.template( $('#formPreview-template').html() ),

		el: $('#formPreviewCont'),

		initialize: function( model ) { 

			this.model = model;

			this.model.on( 'change', this.render, this );

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;

		}

	});

	smuzform.App.Views.FormSettings = Backbone.View.extend({

		template: _.template( $('#formSettings-template').html() ),

		el: $('#formSettingsCont'),

		initialize: function() { 

			this.model.on( 'change', this.render, this ); 

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;

		},

		events: {

			'change #formTitle': 'changeTitle',
			'change #formDescription': 'changeDescription',
			'change #formLang': 'changeLanguage',
			'change #formLabelPlacement': 'changeLabelPlacement',
			'change input[name=optConfOption]': 'changeConfirmationOption',
			'change #formCaptcha': 'changeCaptcha',
			'change #formSubmitBtnText': 'changeSubmitBtnText',
			'change #formRedirectUrl': 'changeRedirectUrl',
			'change #formTextMessage': 'changeTextMessage'

		},

		changeTitle: function(e) {

			this.model.set( 'title', $(e.currentTarget).val() );
 
		},

		changeDescription: function(e) {

			this.model.set( 'description', $(e.currentTarget).val() );

		},

		changeLanguage: function(e) {

			this.model.set( 'lang', $(e.currentTarget).val() );

		},

		changeLabelPlacement: function(e) {
			
			this.model.set( 'labelPlacement', $(e.currentTarget).val() );

		},

		changeConfirmationOption: function(e) {

			if ( $(e.currentTarget).val() === 'text' )
				this.model.set( 'confirmationOption', 'text' );

			if ( $(e.currentTarget).val() === 'redirect' )
				this.model.set( 'confirmationOption', 'redirect' );

		},

		changeCaptcha: function(e) {

			
			if ( $(e.currentTarget).is(':checked') )
				this.model.set( 'captcha', true );
			else
				this.model.set( 'captcha', false );

			
 
		},

		changeSubmitBtnText: function(e) {

			this.model.set( 'submitBtnText', $(e.currentTarget).val() );

		},

		changeRedirectUrl: function(e) {

			this.model.set( 'redirectUrl', $(e.currentTarget).val() );

		},

		changeTextMessage: function(e) {

			this.model.set( 'textMessage', $(e.currentTarget).val() );
		},

		setModel: function(model) { this.model = model; }

	});

	
	
	var FieldsSettingsView = Backbone.View.extend({

		template: _.template( $('#fieldSettings-template').html() ),

		el: $('#fieldSettingsCont'),

		callCounter: 0,

		initialize: function() {

			

		},

		events: {

			'change #fieldLabel': 'changeLabel',
			'change #fieldPlaceholder': 'changePlaceholder',
			'change #fieldValue': 'changeValue',
			'change .fieldOptChoice': 'changeChoice',
			'click .optChoiceRemove': 'removeChoice',
			'click #addNewChoice': 'addChoice',
			'click .optChoiceSelect': 'toggleChoice',
			'change #optionRequired': 'changeRequired',
			'change input[name=fieldOptionRadio]': 'changeVisibility',
			'change #fieldSectionTitle': 'changeSectionTitle',
			'change #fieldSectionDescription': 'changeSectionDescription',
			'change #fieldSize': 'changeSize',
			'change #fieldFileMaxSize': 'changeFileMaxSize',
			'change #fieldFileAllowed': 'changeFileAllowed',
			'change #fieldMinLength': 'changeFieldMinLength',
			'change #fieldMaxLength': 'changeFieldMaxLength',
			'change #enableConditionalLogic': 'changeEnableRule',
			'change #ruleFields': 'changeRuleField',
			'change #ruleFieldAction': 'changeRuleFieldAction',
			'change #ruleFieldLogicValue': 'changeRuleFieldLogicValue',
			'change #ruleFieldOperator': 'changeRuleFieldOperator',
			'change #optionNoDuplicate': 'changeNoDuplicates',
			'change #fieldCssClasses': 'changeCssClasses',

			/** events for custom text **/

			'change #customTextTag': 'changeCustomTextTag',
			'change #customTextColor': 'changeCustomTextColor',
			'change #customTextFontSize': 'changeCustomTextFontSize',
			'click #customTextBold': 'changeCustomTextBold',
			'change #customTextValue': 'changeCustomTextValue',

			/** events for custom image **/

			'change #customImageUrl': 'changeCustomImageUrl',
			'change #customImageWidth': 'changeCustomImageWidth',
			'change #customImageHeight': 'changeCustomImageHeight',

			/** events for custom html **/

			'change #customHtmlSource': 'changeCustomHtmlSource'

		},

		render: function() {

			if ( this.model )
				this.$el.html( this.template( this.model.attributes ) );
			else
				this.$el.html( $('#nofieldSelected-template').html() );

			return this;

		},

		changeRuleField: function(e) {

			var value = $(e.currentTarget).val();

			if ( value === '' ) return;

			var temp = _.clone( this.model.get( 'rules' ) );

			temp.field = value;

			this.model.set( 'rules', temp  );

		},

		changeRuleFieldOperator: function(e) {

			var value = $(e.currentTarget).val();

			if ( value === '' ) return;

			var temp = _.clone( this.model.get( 'rules' ) );

			temp.operator = value;

			this.model.set( 'rules', temp  );

		},
		
		changeRuleFieldAction: function(e) {

			var value = $(e.currentTarget).val();

			if ( value === '' ) return;

			var temp = _.clone( this.model.get( 'rules' ) );

			temp.action = value;

			this.model.set( 'rules', temp  );

		},

		changeRuleFieldLogicValue: function(e) {

			var value = $(e.currentTarget).val();

			if ( value === '' ) return;

			var temp = _.clone( this.model.get( 'rules' ) );

			temp.cmpValue = value;

			this.model.set( 'rules', temp  );

		},

		changeEnableRule: function(e) {

			if ( $(e.currentTarget).is(':checked')  )
				this.model.set( 'ruleEnabled', true );
			else
				this.model.set( 'ruleEnabled', false );

		},

		changeSize: function(e) {

			this.model.set( 'size', $(e.currentTarget).val() );

		},

		changeVisibility: function(e) {


			if ( $(e.currentTarget).val() === 'visible' )
				this.model.set( 'labelVisible', true );
			else
				this.model.set( 'labelVisible', false );

		},

		changeRequired: function(e) {

			if ( $(e.currentTarget).is(':checked')  )
				this.model.set( 'required', true );
			else
				this.model.set( 'required', false );

		},

		changeNoDuplicates: function(e) {

			if ( $(e.currentTarget).is(':checked') )
				this.model.set( 'noDuplicates', true );
			else
				this.model.set('noDuplicates', false );
		},

		changeLabel: function(e) {
			
			this.model.set( 'label', $('#fieldLabel').val() );

			e.stopImmediatePropagation();

		},

		changePlaceholder: function(e) {

			this.model.set( 'placeholderText', $('#fieldPlaceholder').val() );

			e.stopImmediatePropagation();
		},

		changeValue: function(e) {

			var value = $(e.currentTarget).val();

			if ( this.model.get('type') === 'date' ) {

				if ( value !== '' ) {

					var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

					if ( pattern.test( value ) ) {

						var arr = value.split('/');

						var temp = { day: arr[0], month: arr[1], year: arr[2] }

						this.model.set( 'extraData', temp );


					}

				}

			}

			this.model.set( 'preValue', value );



			e.stopImmediatePropagation();

		},

		changeChoice: function(e) {

			var choices = this.model.get( 'choices' );

			choices = _.clone( choices );

			var choiceText = $(e.currentTarget).val();

			var index = $(e.currentTarget).data('index');

			index = parseInt( index );

			choices[index] = choiceText;

			this.model.set( 'choices', choices );

			e.stopImmediatePropagation();

		},

		removeChoice: function(e) {

			var choices = this.model.get( 'choices' );

			choices = _.clone( choices );

			var index = $(e.currentTarget).data('index');

			index = parseInt( index );

			choices.splice( index, 1 );

			this.model.set( 'choices', choices );

			smuzform.App.Views.SettingsView.render();

			e.stopImmediatePropagation();

		},

		addChoice: function(e) {

			var choices = this.model.get( 'choices' );

			choices = _.clone( choices );

			choices.push('');

			this.model.set( 'choices', choices );

			smuzform.App.Views.SettingsView.render();

			e.stopImmediatePropagation();

		},

		toggleChoice: function(e) {

			var index = $(e.currentTarget).data('index');

			var selectedIndex = this.model.get( 'selectedChoice' );

			selectedIndex = parseInt( selectedIndex );

			index = parseInt( index );

			if ( $(e.currentTarget).is(':checked') ) {

				if ( selectedIndex === index ) {

					$(e.currentTarget).prop( 'checked', false );

					this.model.set( 'selectedChoice', null );

					return;

				}

			}

			if ( $(e.currentTarget).is(':checked') )
				this.model.set( 'selectedChoice', index );
			else
				this.model.set( 'selectedChoice', null );
			
			e.stopImmediatePropagation();

		},

		changeSectionTitle: function(e) {

			this.model.set( 'sectionTitle', $(e.currentTarget).val() );

		},

		changeSectionDescription: function(e) {

			this.model.set( 'sectionDescription', $(e.currentTarget).val() );

		},

		changeFileAllowed: function(e) {


			var temp = this.model.get( 'file' );

			var types = $(e.currentTarget).val().split(',');

			temp.allowed = types;

			this.model.set( 'file', temp  );

		},

		changeFileMaxSize: function(e) {

			var temp = this.model.get( 'file' );

			var size = parseInt( $(e.currentTarget).val() );

			temp.maxSize = size;

			this.model.set( 'file', temp  );

		},

		changeFieldMinLength: function(e) {

			var minLength = $(e.currentTarget).val();
			
			if ( ! minLength === '' )
				minLength = parseInt(minLength);

			this.model.set( 'rangeMin', minLength );

		},

		changeFieldMaxLength: function(e) {

			var maxLength = $(e.currentTarget).val();
			
			if ( ! maxLength === '' )
				maxLength = parseInt(maxLength);

			this.model.set( 'rangeMax', maxLength );

		},

		processCustomTextHtml: function() {

			var text = _.clone( this.model.get( 'extraData' ) );

			var value = '';

			if ( text.bold )
				text.bold = 'bold';
			else
				text.bold = 'normal';

			value = $('<div>').html( $( '<'+text.tag+'>' ).text( text.html ).css({
				'font-size': text.size+'px',
				'font-weight': text.bold,
				'color': text.color 
			}) );

			value = value.html();

			var temp = _.clone( this.model.get( 'extraData' ) );

			temp.readyHtml = value;

			this.model.set( 'extraData', temp );

		},

		changeCustomTextValue: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.html = value;

			this.model.set( 'extraData', temp );

			this.processCustomTextHtml();

		},

		changeCustomTextColor: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.color = value;

			this.model.set( 'extraData', temp );

			this.processCustomTextHtml();

		},

		changeCustomTextTag: function(e) {

			var temp = this.model.get( 'extraData' );

			var value = _.escape( $(e.currentTarget).val() );

			temp.tag = value;

			this.model.set( 'extraData', temp );

			this.processCustomTextHtml();

		},

		changeCustomTextFontSize: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.size = parseInt( value );

			this.model.set( 'extraData', temp );

			this.processCustomTextHtml();

		},

		changeCustomTextBold: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = false;

			if ( $(e.currentTarget).is(':checked') )
				value = true;

			temp.bold = value;

			this.model.set( 'extraData', temp );

			this.processCustomTextHtml();

		},

		processCustomImageHtml: function() {

			var image = _.clone( this.model.get( 'extraData' ) );

			var value = '';

			value = $('<div>').html( $( '<img>' ).prop( 'src', image.imgUrl ).css({
				'width': image.width + 'px',
				'height': image.height + 'px'
			}) );

			value = value.html();

			var temp = _.clone( this.model.get( 'extraData' ) );

			temp.readyHtml = value;

			this.model.set( 'extraData', temp );

		},
		changeCustomImageUrl: function(e){

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.imgUrl = value;

			this.model.set( 'extraData', temp );

			this.processCustomImageHtml();

		},
		changeCustomImageWidth: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.width = parseInt( value );

			this.model.set( 'extraData', temp );

			this.processCustomImageHtml();

		},
		changeCustomImageHeight: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = _.escape( $(e.currentTarget).val() );

			temp.height = parseInt( value );

			this.model.set( 'extraData', temp );

			this.processCustomImageHtml();

		},

		changeCustomHtmlSource: function(e) {

			var temp = _.clone( this.model.get( 'extraData' ) );

			var value = $(e.currentTarget).val();

			temp.readyHtml = value;

			this.model.set( 'extraData', temp );

		},

		changeCssClasses: function(e) {

			this.model.set( 'cssClasses', _.escape( $( e.currentTarget ).val() ) );
		},

		destroy: function(){
		  this.remove();
		  this.unbind();
		},

		setModel: function(model) { this.model = model;  }

	});
	
	smuzform.App.Views.SettingsView =  new FieldsSettingsView();	

	smuzform.App.Collections.FieldsCollectionView = Backbone.View.extend({

		template: _.template( $('#fieldsView-template').html() ),

		tagName: 'li',

		settingsView: null,

		initialize: function() {

			this.model.on( 'change', this.render, this );

			
		},

		events: {

			'click': 'openSettingsPanel',
			'click .removeMeField': 'removeField'

		},

		render: function() {

			this.$el.html( this.template( this.model.attributes ) );

			return this;

		},

		openSettingsPanel: function(e) {

			var selectedModel = FieldsListView.getSelectedModel();
			
			smuzform.App.Views.SettingsView.setModel( this.model ) ;

			smuzform.App.Views.SettingsView.render();

			var tab = 'tab-field-settings';

			$('.nav-tabs a[href="#' + tab + '"]').delay(200).tab('show');

		},

		removeField: function(e) {

			if ( ! confirm( "Are you sure you want to remove this field? All data associated with it will be lost." ) )
				return;

			smuzform.App.Collections.FieldsCol.remove( this.model );

			smuzform.App.Views.SettingsView.setModel( null );

			smuzform.App.Views.SettingsView.render();

			e.stopImmediatePropagation();
			
		}

	});


	smuzform.App.Collections.FieldsCol = new FieldsCollection;

	$('.singleText button').click(function() {

		var TextFieldModel = new Field({

			type: 'singletext',
			label: 'Untitled',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( TextFieldModel );

		


	});

	$('.textArea button').click(function() {

		var TextAreaFieldModel = new Field({

			type: 'textarea',
			label: 'Untitled',
			size: 'large',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( TextAreaFieldModel );


	});

	$('.radioButtons button').click(function() {

		var RadioFieldModel = new Field({

			type: 'radio',
			label: 'Untitled',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( RadioFieldModel );


	});

	$('.checkboxes button').click(function() {

		var CheckboxFieldModel = new Field({

			type: 'checkbox',
			label: 'Untitled',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( CheckboxFieldModel );


	});

	$('.dropdown button').click(function() {

		var SelectFieldModel = new Field({

			type: 'dropdown',
			label: 'Untitled',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( SelectFieldModel );


	});

	$('.lineBreak button').click(function() {

		var LinebreakFieldModel = new Field({

			type: 'linebreak',

			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( LinebreakFieldModel );


	});

	$('.sectionBreak button').click(function() {

		var SectionbreakFieldModel = new Field({

			type: 'sectionbreak',

			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( SectionbreakFieldModel );


	});

	$('.singleNumber button').click(function() {

		var NumberFieldModel = new Field({

			type: 'number',
			label: 'Number',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( NumberFieldModel );


	});

	$('.fileUpload button').click(function() {

		var FileUploadFieldModel = new Field({

			type: 'fileupload',
			label: 'Upload File',


			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( FileUploadFieldModel );


	});

	$('.name button').click(function() {

		var NameFieldModel = new Field({

			type: 'name',
			label: 'Name',
			extraData: { firstName: '', LastName: '' },
			size: 'large',
 
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( NameFieldModel );


	});

	$('.email button').click(function() {

		var EmailFieldModel = new Field({

			type: 'email',
			label: 'Email',
			
 
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( EmailFieldModel );


	});

	$('.website button').click(function() {

		var WebsiteFieldModel = new Field({

			type: 'website',
			label: 'Link',
 
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100)

		});

		smuzform.App.Collections.FieldsCol.add( WebsiteFieldModel );


	});

	$('.date button').click(function() {

		var DateFieldModel = new Field({

			type: 'date',
			label: 'Date',
 			extraData: { day: 16, month: 05, year: 2016 },
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			size: 'large',
			preValue: '16/05/2016'

		});

		smuzform.App.Collections.FieldsCol.add( DateFieldModel );
		
	


	});

	$('.phone button').click(function() {

		var PhoneFieldModel = new Field({

			type: 'phone',
			label: 'Phone Number',
 			extraData: {  },
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			size: 'medium'

		});

		smuzform.App.Collections.FieldsCol.add( PhoneFieldModel );
		
		


	});

	$('.address button').click(function() {

		var addressData = {

			firstAddress: '',

			secondAddress: '',

			city: '',

			state: '',

			code: '',

			country: ''

		};

		var AddressFieldModel = new Field({

			type: 'address',
			label: 'Address',
 			extraData: addressData,
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			size: 'large'

		});

		smuzform.App.Collections.FieldsCol.add( AddressFieldModel );

	});

	$('.likert button').click(function() {

		var likertData = {};

		var LikertFieldModel = new Field({

			type: 'likert',
 			extraData: likertData,
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),

		});

		alert( 'The field is in development. Will be available in next version.' );

		//smuzform.App.Collections.FieldsCol.add( LikertFieldModel );

	});

	$('.customText button').click(function() {
		
		var textData = {
			html: 'Change me',
			readyHtml: '<p style="font-size: 14px;color: #000;font-weight: normal">Change Me</p>',
			tag: 'p',
			size: 14,
			color: '#000000',
			bold: false
		};

		var TextModel =  new Field( {

			type: 'customText',
			label: 'Custom Text',
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			extraData: textData
		});

		smuzform.App.Collections.FieldsCol.add( TextModel );

	});

	$('.customImage button').click(function() {
		
		var imageData = {
			imgUrl: '',
			readyHtml: '<img src="" width="300" height="150" />',
			width: 300,
			height: 150
		};

		var ImageModel =  new Field( {

			type: 'customImage',
			label: 'Custom Image',
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			extraData: imageData
		});

		smuzform.App.Collections.FieldsCol.add( ImageModel );

	});

	$('.customHtml button').click(function() {
		
		var htmlData = {
			readyHtml: 'Edit Me'
		};

		var HtmlModel =  new Field( {

			type: 'customHtml',
			label: 'Custom Html',
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			extraData: htmlData
		});

		smuzform.App.Collections.FieldsCol.add( HtmlModel );

	});

	$('.pageBreak button').click(function() {
		
		var pageBreakData = {
			navText: ''
		};

		var PageBreakModel =  new Field( {
			label: 'Page Break',
			type: 'pagebreak',
			label: 'Page Break',
			cssID: 'wpformfield'+Math.floor((Math.random() * 200000) + 100),
			extraData: pageBreakData
		});

		smuzform.App.Collections.FieldsCol.add( PageBreakModel );

	});

	var FieldsListView = new Backbone.CollectionView({

		el: $('#formFieldsCont'),

		sortable: true,

		modelView: smuzform.App.Collections.FieldsCollectionView,

		collection: smuzform.App.Collections.FieldsCol,

		emptyListCaption: '<div id="noFieldsMessage" class="alert alert-warning"><p></p></div>',

		reuseModelViews: false,

		selectable: true

	});

	

	var FormPreviewModel = new smuzform.App.Models.FormSettings();

	var FormPreviewView = new smuzform.App.Views.FormPreview( FormPreviewModel );

	var FormSettingsView = new smuzform.App.Views.FormSettings( {model: FormPreviewModel} );
	
	$('#fetchForm').click(function(e) {

		e.preventDefault();

		FormPreviewModel.fetch({
			data: { formID: '781' }
		}).success(function(){
			FormPreviewModel.set('id', '781');
		});
		

	});

	$('#saveForm').click(function(e) {

		e.preventDefault();

		FormPreviewModel.set( 'fields', smuzform.App.Collections.FieldsCol.models );

		$('#serverModalCont p').text('Saving Form...');
		
		$('#serverModalCont').show();

		FormPreviewModel.save().success( function( response ) {

			var id = response.formID;

			var relative_url = 'admin.php?page=smuz-forms&form_id='+id;

			window.history.replaceState( null, null, relative_url );

			FormPreviewModel.set('id', id);

			FormPreviewModel.set('tmpid', id);

			if ( response.action === 'create' )
				$('#saveForm').text('Save Form');

		}).always( function() {

			$('#serverModalCont p').text('');
		
			$('#serverModalCont').hide();

		});
		

	});

	if (  window.smuz_formid !== undefined ) {

		FormPreviewModel.set( 'tmpid', window.smuz_formid );

		$('#serverModalCont p').text('Loading Form...');

		$('#serverModalCont').show();

		FormPreviewModel.fetch({
			
			data: { formID: window.smuz_formid }
		
		}).success(function(){

			FormPreviewModel.set('id', window.smuz_formid );

			var fields = FormPreviewModel.get('fields');

			_.each( fields, function( field, index ) {
				smuzform.App.Collections.FieldsCol.add(field);
			});
			
			smuzform.App.Collections.FieldsCol.on( 'add', function( model ) {
				$('html, body').animate({
				     scrollTop: $('#saveForm').offset().top - 200   
				   }, 200);
			} );

		}).always(function() {
			
			$('#serverModalCont').delay(100).hide();

			$('#serverModalCont p').delay(100).text('');

		});

	}

	

	FormPreviewView.render();

	FormSettingsView.render( FormPreviewModel );

	FieldsListView.render();

	var FormNavBarView = new smuzform.App.Views.NavBar( FormPreviewModel );
	
	FormNavBarView.render();

});

$(document).on( 'submit', '#fieldSettingsForm', function() {
		return false;
	} );
$(document).on( 'submit', '#formSettingsForm', function() {
		return false;
	} );

$(document).ready(function(){$('.toplevel_page_smuz-forms-main').addClass('current');})

})(jQuery);