var ajaxUrl = ajax_data.ajax_url ; 
jQuery(document).ready(function ($) {
	$('#mwb-fwpro-test-connection').on('click' , function(e){
		e.preventDefault();
		var requestData = {'action' : 'mwb_m4wp_test_api_connection'}
		$.post( ajaxUrl, requestData ).done(function(){
			
		});
	})
});