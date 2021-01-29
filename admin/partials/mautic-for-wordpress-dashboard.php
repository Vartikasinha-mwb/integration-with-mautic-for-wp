<?php
$data = array( 'dateTo' => '2021-01-31' , 'dateFrom' => '2020-12-31', 'timeUnit' => 'd') ; 
$created_leads_in_time = MWB_M4WP_Mautic_Api::get_widget_data( 'created.leads.in.time' , $data );
$page_hits_in_time = MWB_M4WP_Mautic_Api::get_widget_data( 'page.hits.in.time' , $data );
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Dashboard', 'mautic-for-wordpress' ) ?></h3>
    </div>
    <div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-dashboard-panel">
        <?php if($created_leads_in_time) : ?>
        <div id="created-leads-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($created_leads_in_time['data']) )  ?>">
            <canvas id="created-leads-in-time-chart"></canvas>
        </div>
        <?php endif ;?>
        <?php if($page_hits_in_time) : ?>
        <div id="page-hits-in-time" class="mwb-m4wp-admin-widget-wrap" data="<?php echo htmlspecialchars( json_encode($page_hits_in_time['data']) )  ?>">
            <canvas id="page-hits-in-time-chart"></canvas>
        </div>
        <?php endif ;?>
    </div>
</div>