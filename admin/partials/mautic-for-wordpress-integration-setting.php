<?php
$segment_list = Mautic_For_Wordpress_Admin::get_segment_options();
$integation = Mautic_For_Wordpress_Admin::get_integrations( $_GET['id'] ) ;

$settings = get_option('mwb_m4wp_integration_settings' , array()); 
$setting = isset($settings[$_GET['id']]) ? $settings[$_GET['id']] : array();
$enable = isset($setting['enable']) ? $setting['enable'] : 'no' ; 
$implicit = isset($setting['implicit']) ? $setting['implicit'] : 'no' ; 
$checkbox_txt = isset($setting['checkbox_txt']) ? $setting['checkbox_txt'] : '' ; 
$precheck = isset($setting['precheck']) ? $setting['precheck'] : 'no' ; 
$add_segment = isset($setting['add_segment']) ? $setting['add_segment'] : '-1' ; 
$add_tag = isset($setting['add_tag']) ? $setting['add_tag'] : '' ; 
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Integrations', 'mautic-for-wordpress' ) ?></h3>
    </div>
    <div class="mwb-m4wp-admin-panel-main mwb-m4wp-admin-integration-panel">
    <div class="mwb-m4wp-admin-panel-back-wrap">
        <a href="?page=integrations"><?php esc_html_e('Back' , 'mautic-for-wordpress') ?></a>
    </div>
    <div class="mwb-m4wp-admin-form-wrap-title">
        <?php echo $integation['name'] ?>
    </div>
    <div class="mwb-m4wp-admin-form-wrap mwb-m4wp-admin-integration-form-wrap">
        <form action="" method="post">
            <table class="form-table mwb-m4wp-admin-table">
                <tr>
                    <th><label for="enable"><?php esc_html_e('Enable' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="enable" <?php checked('yes', $enable) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="enable" <?php checked('no', $enable) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" to enable the integration. ', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="implicit"><?php esc_html_e('Implicit' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="implicit" <?php checked('yes', $implicit) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="implicit" <?php checked('no', $implicit) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to subscribe people without asking them explicitly.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="checkbox_txt"><?php esc_html_e('Checkbox Label Text' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="text" name="checkbox_txt" value="<?php echo $checkbox_txt ?>">
                        <p class="description">
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="precheck"><?php esc_html_e('Pre Check Checkbox' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="precheck" <?php checked('yes', $precheck) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="precheck" <?php checked('no', $precheck) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to check the checkbox by default.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="add_segment"><?php esc_html_e('Segment' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <select name="add_segment">
                            <option value="-1"><?php esc_html_e('--Select--', 'mautic-for-wordpress') ?></option>
                            <?php foreach($segment_list as $key => $segment) : ?>
                                <option value="<?php echo $segment['id'] ?>" <?php selected( $key , $add_segment ) ?>>
                                    <?php echo $segment['name'] ?>
                                </option>
                            <?php endforeach ; ?>
                        </select>
                        <p class="description">
                            <?php esc_html_e( 'Select segment in which the contact should be added.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="add_tag"><?php esc_html_e('Tags' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="text" name="add_tag" value="<?php echo $add_tag ?>">
                        <p class="description">
                            <?php esc_html_e( 'Enter tags separated by commas to assign to contact.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('mwb_m4wp_integration_nonce') ?>" />
            <input type="hidden" name="integration" value="<?php echo $_GET['id'] ?>" />
            <input type="hidden" name="action" value="mwb_m4wp_integration_save" />
            <div class="mwb-m4wp-admin-button-wrap">
                <button class="button mwb-m4wp-admin-button" type="submit"><?php esc_html_e('Save' , 'mautic-for-wordpress') ?></button>
            </div>
        </form>
    </div>
    </div>
</div>