jQuery(document).ready(function($) {
  jQuery( '#select404page' ).change(function() {
    jQuery( '#edit_404_page, #test_404_page' ).prop( 'disabled', !( jQuery( '#select404page' ).val() == jQuery( '#404page_current_value').text() != 0 ) );
  });
  jQuery( '#select404page' ).trigger( 'change' );
  jQuery( '#edit_404_page' ).click(function() {
    window.location.href = jQuery( '#404page_edit_link' ).text();
  });
  jQuery( '#test_404_page' ).click(function() {
    window.location.href = jQuery( '#404page_test_link' ).text();
  });
  jQuery( '#404page_admin_notice .notice-dismiss' ).click( function() {
    jQuery.ajax({
      url: ajaxurl,
      data: {
        action: '404page_dismiss_admin_notice'
      }
    });
  });
});