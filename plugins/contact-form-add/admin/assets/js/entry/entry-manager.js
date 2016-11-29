(function($){


$(document).ready(function() {

	var table = $('#entryDataTable').DataTable( {
    orderMulti: false,
		processing: true,
		serverSide: true,
		ajax: ajaxurl+'?action=smuzform_entry_api&nonce='+smuzform.entry_model_nonce+'&formID='+smuzform.form_id,
		scrollX: true,
		select: true,
		bSort: false,
		dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +'Bfrtip',
		searchDelay: 500,
		paging: true,
		sPaginationType: "full_numbers",
		buttons: [
	        'print',
	        'csv',
	         {
                text: 'View Entry',
                action: function ( e, dt, node, config ) {
               		var selected = table.row( { selected: true } );
               		
               		if ( selected.count() === 0 ) {
               			alert( 'No Entry Selected' );
               			return;
               		}

               		var entryId = selected.id();

               		window.location.href = window.location.href+"&entry_id="+entryId

                },

            },
            {
                text: 'Delete Entry',
                action: function ( e, dt, node, config ) {
               		var selected = table.row( { selected: true } );
               		
               		if ( selected.count() === 0 ) {
               			alert( 'No Entry Selected' );
               			return false;
               		}

               		if ( ! confirm( 'CONFIRM delete action' ) )
               			return false;

               		var entryId = selected.id();

               		var apiUrl = ajaxurl+'?action=smuzform_entry_api&nonce='+smuzform.entry_model_nonce+'&formID='+smuzform.form_id+"&entryID="+entryId;

               		
               		$.ajax({

               			url: apiUrl,

               			type: 'delete',

               			success: function(response) {

               				response = $.parseJSON( response );

               				if ( response.signal )
               					table.row( { selected: true } ).remove().draw();

               			}

               		});

                }
                
            }

	    ],
	    "columnDefs": [
		    { "width": "20%" }
		  ],

	} );
	
	$('.dt-buttons span:contains(Delete Entry)').parent().removeClass('btn-default');
	$('.dt-buttons span:contains(Delete Entry)').parent().addClass('btn-danger');
  $('.dt-buttons span:contains(View Entry)').parent().removeClass('btn-default');
  $('.dt-buttons span:contains(View Entry)').parent().addClass('btn-info');

	var user_ip = $('#hiddenUserIp').val();

	if ( user_ip ) {
		$.getJSON('http://ipinfo.io/'+ user_ip +'/json', function(data){
		 
		}).success(function(data){
			
			if ( data === undefined || ! data )
				return false;

			var locationString = data.city + ', ' + data.region + ', ' + data.country;

			data.loc = smuz_escapeHtml( data.loc );

			var map = '<div style="text-decoration:none; overflow:hidden; height:150px; width:300px; max-width:100%;"><div id="my-map-display" style="height:100%; width:100%;max-width:100%;"><iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q='+ data.loc +'&key=AIzaSyAN0om9mFmy1QN6Wf54tXAowK4eT0ZUPrU"></iframe></div>';

			$('#userIpLocation').text( locationString );

			$('#userIpLocationMap').html( map );

		});
	}
	

});


var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
  };

function smuz_escapeHtml(string) {
   return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
   });
}

$(document).ready(function(){$('.toplevel_page_smuz-forms-main').addClass('current');})
}(jQuery));