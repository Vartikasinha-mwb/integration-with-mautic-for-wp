<?php
$helper = new Mautic_For_Wordpress_Settings_Helper();
$date_range = get_option( 'mwb_m4wp_date_range' , array() ) ; 
if(empty($date_range)){
    $date_range = Mautic_For_Wordpress_Admin::get_default_date_range() ; 
}
$time_unit = Mautic_For_Wordpress_Admin::get_time_unit( $date_range );
$data = array( 'dateTo' => $date_range['date_to'] , 'dateFrom' => $date_range['date_from'], 'timeUnit' => $time_unit) ; 
$created_leads_in_time = $helper->get_widget_data('created.leads.in.time' , $data  ) ;
$page_hits_in_time = $helper->get_widget_data( 'page.hits.in.time' , $data );
$submissions_in_time =  $helper->get_widget_data( 'submissions.in.time' , $data  );
$top_lists =  $helper->get_widget_data( 'top.lists' , $data );
$most_sent_emails = $helper->get_widget_data( 'most.sent.emails', $data ); 
$base_url = get_option( 'mwb_m4wp_base_url' , '' ) ; 
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Dashboard', 'mautic-for-wordpress' ) ?></h3>
    </div>
    <div class="mwb-m4wp-admin-panel-date">
        <form method="post">
            <span>
                <label for="mwb_m4wp_from_date"><?php esc_html_e('From' , 'mautic-for-wordpress') ?></label>
                <input type="text" name="mwb_m4wp_from_date" class="mwb-m4wp-datepicker" value="<?php echo $date_range['date_from'] ?>" />
            </span>
            <span>
                <label for="mwb_m4wp_from_date"><?php esc_html_e('To' , 'mautic-for-wordpress') ?></label>
                <input type="text" name="mwb_m4wp_to_date" class="mwb-m4wp-datepicker" value="<?php echo $date_range['date_to'] ?>" />
            </span>
            <input type="hidden" name="action" value="mwb_m4wp_date_range" />
            <button type="submit" class="button"><?php esc_html_e( 'Apply', 'mautic-for-wordpress' ) ?></button>
            <?php echo $helper->get_refresh_button_html( "dashboard" ) ?>
        </form>
    </div>
    <div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-dashboard-panel">
        <?php if($created_leads_in_time) : ?>
        <div class="mwb-m4wp-admin-widget-row">
            <div class="mwb-m4wp-admin-widget-head">
                <?php esc_html_e( 'Created Leads In Time', 'mautic-for-wordpress' ) ;?>
            </div>
            <?php if( isset($created_leads_in_time['data']) && !empty($created_leads_in_time['data']) ) : ?>
            <div id="created-leads-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($created_leads_in_time['data']) )  ?>">
                <canvas id="created-leads-in-time-chart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <?php else : ?>
            <div class="widget-now-data-wrap">
                <p><?php esc_html_e('No data available', 'mautic-for-wordpress') ?></p>
            </div>
            <?php endif ;?>
        </div>
        <?php endif ;?>
        <?php if($page_hits_in_time) : ?>
        <div class="mwb-m4wp-admin-widget-row">
            <div class="mwb-m4wp-admin-widget-head">
            <?php esc_html_e( 'Page Hits In Time', 'mautic-for-wordpress' ) ;?>
            </div>
            <?php if( isset($page_hits_in_time['data']) && !empty($page_hits_in_time['data']) ) : ?>
            <div id="page-hits-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($page_hits_in_time['data']) )  ?>">
                <canvas id="page-hits-in-time-chart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <?php else : ?>
            <div class="widget-now-data-wrap">
                <p><?php esc_html_e('No data available', 'mautic-for-wordpress') ?></p>
            </div>
            <?php endif ;?>
        </div>
        <?php endif ;?>
        <?php if($submissions_in_time) : ?>
        <div class="mwb-m4wp-admin-widget-row">
            <div class="mwb-m4wp-admin-widget-head">
                <?php esc_html_e( 'Form Submissions In Time', 'mautic-for-wordpress' ) ;?>
            </div>
            <?php if( isset($submissions_in_time['data']) && !empty($submissions_in_time['data']) ) : ?>
            <div id="submissions-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($submissions_in_time['data']) )  ?>">
                <canvas id="submissions-in-time-chart" style="width: 100%; height: 300px;"></canvas>
            </div>
            <?php else : ?>
            <div class="widget-now-data-wrap">
                <p><?php esc_html_e('No data available', 'mautic-for-wordpress') ?></p>
            </div>
            <?php endif ;?>
        </div>
        <?php endif ;?>
        
        <div class="mwb-m4wp-admin-widget-row column-2">
        <?php if($top_lists) : ?>
            <div class="mwb-m4wp-admin-widget-column">
                <div class="mwb-m4wp-admin-widget-head">
                    <?php esc_html_e( 'Top Segments', 'mautic-for-wordpress' ) ;?>
                </div>
                <div id="top-lists" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($top_lists['data']) )  ?>">
                    <?php if(count($top_lists['data']['bodyItems'])>0) : ?>
                    <table id="top-lists-table" class="widget-table">
                        <tr><th><?php esc_html_e('Segments') ?></th><th><?php esc_html_e('Contacts') ?></th></tr>
                        <?php foreach ($top_lists['data']['bodyItems'] as $key => $list) : ?>
                            <tr>
                                <td><a target="_blank" href="<?php echo $base_url.$list[0]['link'] ?>"><?php echo $list[0]['value'] ?></a></td>
                                <td><a target="_blank" href="<?php echo $base_url.$list[1]['link'] ?>"><?php echo $list[1]['value'] ?></a></td>
                            </tr>
                        <?php endforeach ; ?>
                    </table>
                    <?php else : ?>
                    <div class="widget-now-data-wrap">
                        <p><?php esc_html_e('No data available', 'mautic-for-wordpress') ?></p>
                    </div>
                    <?php endif ;?>
                </div>
            </div>
        <?php endif ;?>
        <?php if($most_sent_emails) : ?>
            <div class="mwb-m4wp-admin-widget-column">
                <div class="mwb-m4wp-admin-widget-head">
                    <?php esc_html_e( 'Most Sent Emails', 'mautic-for-wordpress' ) ;?>
                </div>
                <div id="top-lists" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($top_lists['data']) )  ?>">
                    <?php if (count($most_sent_emails['data']['bodyItems']) > 0 ) : ?>
                    <table id="top-lists-table" class="widget-table">
                        <tr><th><?php esc_html_e('Segments') ?></th><th><?php esc_html_e('Contacts') ?></th></tr>
                        <?php foreach ($most_sent_emails['data']['bodyItems'] as $key => $list) : ?>
                            <tr>
                                <td><a target="_blank" href="<?php echo $base_url.$list[0]['link'] ?>"><?php echo $list[0]['value'] ?></a></td>
                                <td><a target="_blank" href="<?php echo $base_url.$list[1]['link'] ?>"><?php echo $list[1]['value'] ?></a></td>
                            </tr>
                        <?php endforeach ; ?>
                    </table>
                    <?php else : ?>
                    <div class="widget-now-data-wrap">
                        <p><?php esc_html_e('No data available', 'mautic-for-wordpress') ?></p>
                    </div>
                    <?php endif ;?>
                </div>
            </div>
        </div>
        <?php endif ;?>                    
        
    </div>
</div>