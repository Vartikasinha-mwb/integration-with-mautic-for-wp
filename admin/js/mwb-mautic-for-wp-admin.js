var ajaxUrl = ajax_data.ajax_url;
jQuery(document).ready(function($) {
    
    $('.mwb-dashicons-visibility').on('click', function(e){
        var icon = $(this) ; 
        var input = icon.closest('td').find('input') ; 
        if(input.attr('type') == 'password'){
            input.attr('type' , 'text') ;
        }else{
            input.attr('type' , 'password') ;
        }
    })

    $('.mwb-m4wp-form-refresh').on('click', function(e){
        e.preventDefault();
        var page = 'forms';
        var action = 'mwb_m4wp_refresh';
        $(this).html('<span class="dashicons dashicons-update-alt mwb-refresh-icon--rotation"></span>');
        $('.mwb-refresh-icon').addClass('mwb-refresh-icon--rotation');
        $.post(ajaxUrl, { page, action }).done(function(response) {
            location.reload()
        })        
    })
    
    //shivjs
    $('.mwb-switch-checkbox').on('click', function() {
        $(this).toggleClass('mwb-switch-checkbox--move');
    });
    $('#mwb-preview-close--icon').on('click', function() {
        $("#mwb-m4wp-form-html").removeClass('mwb-form-html--popup');
        $('.mwb-m4wp-form-head').css("opacity", "0");
        $('#mwb-m4wp-form-main').html('');

    });
    //shivjs
    $('#mwb-m4wp-save-btn').on('click', function(e) {

        if ($('#input-baseurl').val() == '') {
            alert('Please enter all details');
        }
        var type = $('#mwb-m4wp-auth-type').val();
        if (type == 'basic') {
            if ($('#input-password').val() == '' || $('#input-username').val() == '') {
                e.preventDefault();
                alert('Please enter all details');
            }
        } else {
            if ($('#input-id').val() == '' || $('#input-secret').val() == '') {
                e.preventDefault();
                alert('Please enter all details');
            }
        }
    })

    $('.mwb-m4wp-datepicker').datepicker({
        dateFormat: "yy-mm-dd"
    });

    $('#mwb-fwpro-test-connection').on('click', function(e) {
        e.preventDefault();
        var requestData = { 'action': 'mwb_m4wp_test_api_connection' }
        $.post(ajaxUrl, requestData).done(function(response) {
            alert(response.msg)
        });
    })

    $('#mwb-m4wp-auth-type').on('change', function(e) {
        $('.mwb-m4wp-oauth-row').toggleClass('row-hide');
        $('.mwb-m4wp-basic-row').toggleClass('row-hide');
    })

    $('.mwb-m4wp-form-view').on('click', function(e) {
        e.preventDefault();
        var html = $(this).attr('form-html');
        //shivjs
        $("#mwb-m4wp-form-html").addClass('mwb-form-html--popup');
        $('.mwb-m4wp-form-head').css("opacity", "1");
        //shivjs
        $('#mwb-m4wp-form-main').html(html);
    })

    $('.mwb-m4wp-implicit-cb').on('change', function(e) {
        $('.row-implicit').toggleClass('row-hide');
    })

    $('.mwb-m4wp-enable-cb').on('change', function(e) {
        var cb = $(this);
        var enable = cb.prop('checked') ? 'yes' : 'no';
        var integration = cb.closest('tr').attr('integration');
        var action = 'mwb_m4wp_enable_integration';
        var request = { enable, integration, action };
        $.post(ajaxUrl, request).done(function(response) {

        });
    })

    $('.mwb-m4wp-form-code').on('click', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        if (row.hasClass('row-active')) {
            var id = $(this).attr('form_id');
            var ele = document.getElementById("form-input-" + id);
            copy_text(ele);
        }
    });

    $('.mwb-m4wp-refresh-btn').on('click', function(e) {
        e.preventDefault();
        var page = $(this).attr('page');
        var action = 'mwb_m4wp_refresh';
        var select = $('#mwb-m4wp-segment-select');
        var selected = select.val();
        $('.mwb-refresh-icon').addClass('mwb-refresh-icon--rotation');
        $.post(ajaxUrl, { page, action }).done(function(response) {
            if (page != "segments") {
                location.reload();
            }
            var response_obj = JSON.parse(response);
            var segmentList = response_obj.segment_list;
            var options = getSegmentOptionHtml(segmentList, selected);
            select.html(options);
            $('.mwb-refresh-icon').removeClass('mwb-refresh-icon--rotation');
        })
    })

    function getSegmentOptionHtml(segmentList, selected) {
        var html = '';
        $.each(segmentList, function(index, value) {
            var check = (selected == value.id) ? 'selected' : '';
            console.log(typeof(value.id));
            console.log(value.id);
            if (value.id == -1) {
                html += '<option value="' + value.id + '" ' + check + '>' + value.name + '</option>';
                console.log(html);
            } else {
                html += '<option value="' + value.id + '">' + value.name + '</option>';
                console.log(html);
            }
        });
        return html;
    }

    if ($('#created-leads-in-time').length > 0) {
        var chartdata = $('#created-leads-in-time').attr('data');
        chartdata = JSON.parse(chartdata)
        var chartData = chartdata.chartData;
        var ctx = document.getElementById('created-leads-in-time-chart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                lineTension: 0.2,
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
    if ($('#page-hits-in-time').length > 0) {
        var chartdata = $('#page-hits-in-time').attr('data');
        chartdata = JSON.parse(chartdata)
        var chartData = chartdata.chartData;
        var ctx = document.getElementById('page-hits-in-time-chart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                lineTension: 0.2,
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
    if ($('#submissions-in-time').length > 0) {
        var chartdata = $('#submissions-in-time').attr('data');
        chartdata = JSON.parse(chartdata)
        var chartData = chartdata.chartData;
        var ctx = document.getElementById('submissions-in-time-chart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                lineTension: 0.2,
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

function copy_text(element) {
    element.select();
    element.setSelectionRange(0, 99999)
    document.execCommand("copy");
    console.log(element.value)
    alert("Copied");
}