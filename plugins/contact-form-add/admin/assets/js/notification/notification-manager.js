(function($) {

//SmuzForm

NotificationUI = {
  Models: {},
  Collections: {},
  Views: {},
  Dispatcher: _.clone(Backbone.Events),
};

NotificationUI.Models.NotificationCont = Backbone.Model.extend({

	defaults: function() {
		return {
			notifications: {}
		}
	},

	url: ajaxurl+'?action=smuzform_notification_api&nonce='+smuzform.notification_model_nonce+'&formID='+smuzform.form_id
});

NotificationUI.Models.Notification = Backbone.Model.extend({
	initialize: function(){},

	defaults: function() {

		return {

			type: 'email',

			label: 'Email Notification',

			uniqID: 'sfnotification',

			order: 10,

			status: 'disabled',

			collapsed: '',

			extraData: {
				subject: 'New Form Entry',
				emailAddress: '',
				replyToEmail: '',
				fromText: 'WordPress',
				template: '[smfield print="all"]',
				useHTML: true
			},

			/*rules: [
				{
					field: '',
					operator: 'is',
					cmpValue: '',
					action: 'send',
					forwardTo: ''
				}
			]*/

			rules: {
				field: '',
				operator: 'is',
				cmpValue: '',
				action: 'send'
			},

			ruleEnabled: false



		};

	}

});

NotificationUI.Collections.Notifications = Backbone.Collection.extend({
	model: NotificationUI.Models.Notification
});

NotificationUI.Views.AddNew = Backbone.View.extend({

	el: $('#addNewNotificationEL'),

	template: '',

	initialize: function() {

		this.template = _.template( $('#addNewNotification-template').html() );
	
	},

	events: {
		'click #addNewNotificationButton': 'addNew',
		'click #saveNotificationButton': 'saveChanges'
	},

	addNew: function(e) {
		
		var type = $('#notificationTypeSelect').val();

		type = _.escape( type );

		if ( type === 'email' ) {

			var notification = new NotificationUI.Models.Notification({
				type: 'email',
				uniqID: 'sfnotification'+Math.floor((Math.random() * 200000) + 100)
			});

			this.collection.add( notification );

		}

		if ( type === 'confirmationEmail' ) {

			var notification = new NotificationUI.Models.Notification({
				type: 'confirmationEmail',
				uniqID: 'sfnotification'+Math.floor((Math.random() * 200000) + 100),
				label: 'Confirmation Email',
				extraData: {
					subject: 'Thank You',
					emailAddress: '',
					replyToEmail: '',
					fromText: '',
					template: 'Thank you so much for your submission. We\'ll be in touch shortly.',
					useHTML: true
				},
			});

			this.collection.add( notification );

		}

	},

	saveChanges: function(e) {

		var old = $(e.currentTarget).text();

		var savingText = 'Saving changes...';

		$(e.currentTarget).text( savingText );

		this.model.set( 'notifications', this.collection.models );

		this.model.save().success( function(response) {

		}).always( function() {

			$(e.currentTarget).text(old);

		});

	},

	render: function() {
		
		this.$el.html( this.template( {} ) );

		return this;

	}

});

NotificationUI.Views.Notification = Backbone.View.extend({

	template: '',

	initialize: function() {

		this.template = _.template( $('#notificationCollectionView-template').html() );

	},

	events: {

		'change #emailTemplate': 'changeEmailTemplate',
		'change #emailSenderName': 'changeEmailFromText',
		'change #emailReplyToEmail': 'changeReplyToEmail',
		'change #emailSenderAddress': 'changeEmailAddress',
		'change #emailSubject': 'changeEmailSubject',
		'click .removeNotification': 'removeNotification',
		'change #notificationLabel': 'changeLabel',
		'change #emailUseHTML': 'changeUseHTML',
		'change #enableConditionalLogic': 'changeEnableRule',
		'change #ruleFields': 'changeRuleField',
		'change #ruleFieldAction': 'changeRuleFieldAction',
		'change #ruleFieldLogicValue': 'changeRuleFieldLogicValue',
		'change #ruleFieldOperator': 'changeRuleFieldOperator',

	},

	changeEmailAddress: function(e) {

		var value = _.escape( $(e.currentTarget).val() );

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.emailAddress = value;

		this.model.set( 'extraData', temp  );

		console.log( this.model.get( extraData ) );

	},
	changeEmailFromText: function(e) {

		var value = $(e.currentTarget).val();

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.fromText = value;

		this.model.set( 'extraData', temp  );

	},
	changeReplyToEmail: function(e) {
		
		var value = $(e.currentTarget).val();

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.replyToEmail = value;

		this.model.set( 'extraData', temp  );

	},
	changeEmailTemplate: function(e) {

		var value = $(e.currentTarget).val();

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.template = value;

		this.model.set( 'extraData', temp  );

	},
	changeEmailSubject: function(e) {

		var value = $(e.currentTarget).val();

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.subject = value;

		this.model.set( 'extraData', temp  );

	},


	changeLabel: function(e) {

		var label = _.escape( $(e.currentTarget).val() );

		this.model.set( 'label', label );

		this.render();

	},
	
	removeNotification: function(e) {

		if ( ! confirm( 'CONFIRM REMOVE ACTION' ) )
			return false;

		this.collection.remove( this.model );

	},

	changeUseHTML: function(e) {
		
		var value = false;

		if ( $(e.currentTarget).is(':checked') )
			value = true;

		var temp = _.clone( this.model.get( 'extraData' ) );

		temp.useHTML = value;

		this.model.set( 'extraData', temp  );
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

	render: function() {

		this.$el.html( this.template( this.model.attributes ) );

		return this;
	}

});

NotificationUI.Views.NotificationCol = Backbone.View.extend({

	initialize: function() {

		var parentThis = this;

		this.collection.on('add remove', function() {
			parentThis.render();
		});
	
	},

	render: function() {

		this.$el.html('');

		this.collection.each( function( model ) {

			var notification = new NotificationUI.Views.Notification({ 
				model: model,
				collection: this.collection
			});

			this.$el.append( notification.render().el );

		}.bind(this));
	
	}

});

NotificationUI.App = {
	init: function() {

		var NotificationServerModel = new NotificationUI.Models.NotificationCont();

		var NotificationsCol = new NotificationUI.Collections.Notifications();

		NotificationServerModel.fetch().success( function() {

			var notifications = NotificationServerModel.get('notifications');

			_.each( notifications, function( notification, index ) {
				NotificationsCol.add( notification );
			});

		});

		var AddNewButton = new NotificationUI.Views.AddNew( 
			{ el: $('#addNewNotificationEL'),
			  collection: NotificationsCol,
			  model: NotificationServerModel
			} );

		var NotificationColView = new NotificationUI.Views.NotificationCol( 
			{ el: $('#notificationCollectionCont'),
			  collection: NotificationsCol
			} );

		AddNewButton.render();

		NotificationColView.render();

	}
};

$(document).ready(function() {
	NotificationUI.App.init();
});

$(document).ready(function(){$('.toplevel_page_smuz-forms-main').addClass('current');})
})(jQuery);