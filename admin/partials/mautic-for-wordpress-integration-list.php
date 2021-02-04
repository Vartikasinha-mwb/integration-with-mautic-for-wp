<?php
$integrations = Mautic_For_Wordpress_Admin::get_integrations() ; 
$settings = get_option('mwb_m4wp_integration_settings' , array()); 
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Integrations', 'mautic-for-wordpress' ) ?></h3>
    </div>
    <div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-integration-panel">
        <table class="form-table mwb-m4wp-admin-table">
            <thead>
                <tr>
                    <th class="name"><?php esc_html_e( 'Name', 'mautic-for-wordpress' ) ?></th>
                    <th class="des"><?php esc_html_e( 'Description', 'mautic-for-wordpress' ) ?></th>
                    <th class="status"><?php esc_html_e( 'Status', 'mautic-for-wordpress' ) ?></th>
                    <th class="setting"><?php esc_html_e( 'Settings', 'mautic-for-wordpress' ) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($integrations as $key => $integration) : 
                    
                    $setting = isset($settings[$key]) ? $settings[$key] : array() ;
                    $enable = isset($setting['enable']) ? $setting['enable'] : 'no' ; 
                    ?>
                <tr integration="<?php echo $key ?>">
                    <td class="name"><?php echo $integration['name'] ; ?></td>
                    <td class="des"><?php echo $integration['des'] ; ?></td>
                    <td class="status">
                        <label class="switch">
                            <input type="checkbox" <?php checked( $enable , 'yes' ) ?> class="mwb-m4wp-enable-cb">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td class="setting">
                        <a href="?page=integrations&id=<?php echo $integration['id'] ?>">
                            <span class="dashicons dashicons-admin-generic"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach ; ?>
            </tbody>
        </table>
    </div>
</div>