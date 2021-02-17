<?php 
$helper = new Mautic_For_Wordpress_Settings_Helper();
$location = get_option( 'mwb_m4wp_script_location' , 'footer' ) ; 
$enable =  get_option( 'mwb_m4wp_tracking_enable' , 'yes' ) ;
?>
<div class="wrap mwb-m4wp-admin-wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Mautic WordPress Integration', 'mautic-for-wordpress' ) ?></h3>
    </div>
    <div class="mwb-m4wp-admin-panel-main">
        <div class="setting-table-wrap mwb-m4wp-admin-table">
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Enable Mautic Tracking' , 'mautic-for-wordpress') ?></th>
                        <td class="tracking">
                            <label class="switch">
                                <input type="checkbox" name="mwb_m4wp_tracking_enable" value="yes" <?php echo checked('yes' , $enable) ?>>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Script Location' , 'mautic-for-wordpress') ?></th>
                        <td>
                            <input type="radio" name="mwb_m4wp_script_location" value="head" <?php echo checked('head' , $location) ?> />
                            <label
                                for="mwb_m4wp_script_location"><?php esc_html_e('Head' , 'mautic-for-wordpress') ?></label>
                            <input type="radio" name="mwb_m4wp_script_location" value="footer" <?php echo checked('footer' , $location) ?> />
                            <label
                                for="mwb_m4wp_script_location"><?php esc_html_e('Footer' , 'mautic-for-wordpress') ?></label>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="action" value="mwb_m4wp_setting_save" />
                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('_nonce') ?>" />
                <button id="mwb-m4wp-save-btn" type="submit" class="button"><?php esc_html_e('Save' , 'mautic-for-wordpress') ?></button>
            </form>
        </div>
    </div>
</div>