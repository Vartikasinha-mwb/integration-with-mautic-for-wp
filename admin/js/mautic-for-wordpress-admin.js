var ajaxUrl = ajax_data.ajax_url ; 
jQuery(document).ready(function ($) {
	$('#mwb-fwpro-test-connection').on('click' , function(e){
		e.preventDefault();
		var requestData = {'action' : 'mwb_m4wp_test_api_connection'}
		$.post( ajaxUrl, requestData ).done(function( response ){
			alert( response.msg )
		});
	})

	$('#mwb-m4wp-auth-type').on('change', function(e){
		$( '.mwb-m4wp-oauth-row' ).toggleClass( 'row-hide' );
		$( '.mwb-m4wp-basic-row' ).toggleClass( 'row-hide' );
	})

	$('.mwb-m4wp-form-view').on('click' , function(e){
		e.preventDefault();
		var html = $(this).attr('form-html');
		$('#mwb-m4wp-form-html').html(html);
	})
});