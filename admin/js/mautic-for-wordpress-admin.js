var ajaxUrl = ajax_data.ajax_url ; 
jQuery(document).ready(function ($) {

	$('.mwb-m4wp-datepicker').datepicker({
		dateFormat: "yy-mm-dd"
	});
	
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

	if($('#created-leads-in-time').length > 0  ){
		var chartdata = $('#created-leads-in-time').attr('data') ; 
		chartdata = JSON.parse(chartdata)
		var chartData = chartdata.chartData ; 
		var ctx = document.getElementById('created-leads-in-time-chart');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: chartData,
			options: {
				lineTension : 0.2,
				borderWidth: 1,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	}
	if($('#page-hits-in-time').length > 0  ){
		var chartdata = $('#page-hits-in-time').attr('data') ; 
		chartdata = JSON.parse(chartdata)
		var chartData = chartdata.chartData ; 
		var ctx = document.getElementById('page-hits-in-time-chart');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: chartData,
			options: {
				lineTension : 0.2,
				borderWidth: 1,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	}
	if($('#submissions-in-time').length > 0  ){
		var chartdata = $('#submissions-in-time').attr('data') ; 
		chartdata = JSON.parse(chartdata)
		var chartData = chartdata.chartData ; 
		var ctx = document.getElementById('submissions-in-time-chart');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: chartData,
			options: {
				lineTension : 0.2,
				borderWidth: 1,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	}
});