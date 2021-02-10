<?php
$helper = new Mautic_For_Wordpress_Settings_Helper();
$segment_list = $helper->get_segment_options();
$integation_details = Mautic_For_Wordpress_Integration_Manager::get_integrations($_GET['id']) ; 
$integation = Mautic_For_Wordpress_Integration_Manager::get_integration( $integation_details ) ;
$enable = $integation->is_enabled() ; 
$implicit = $integation->is_implicit() ; 
$checkbox_txt = $integation->get_option( 'checkbox_txt' ) ;
$precheck = $integation->is_checkbox_precheck() ;
$add_segment = $integation->get_option( 'add_segment' ) ;
$add_tag = $integation->get_option( 'add_tag' )  ; 
$hide_row = $implicit ? 'row-hide' : '' ; 
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
        <?php echo $integation->get_name() ?>
    </div>
    <div class="mwb-m4wp-admin-form-wrap mwb-m4wp-admin-integration-form-wrap">
        <form action="" method="post">
            <table class="form-table mwb-m4wp-admin-table">
                <tr>
                    <th><label for="enable"><?php esc_html_e('Enable' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="enable" <?php checked( true, $enable) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="enable" <?php checked( false , $enable) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" to enable the integration. ', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="implicit"><?php esc_html_e('Implicit' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input class="mwb-m4wp-implicit-cb" type="radio" value="yes" name="implicit" <?php checked( true, $implicit) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input class="mwb-m4wp-implicit-cb" type="radio" value="no" name="implicit" <?php checked( false , $implicit) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to subscribe people without asking them explicitly.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr class="row-implicit <?php echo $hide_row ?>">
                    <th><label for="checkbox_txt"><?php esc_html_e('Checkbox Label Text' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="text" name="checkbox_txt" value="<?php echo $checkbox_txt ?>">
                        <p class="description">
                        </p>
                    </td>
                </tr>
                <tr class="row-implicit <?php echo $hide_row ?>">
                    <th><label for="precheck"><?php esc_html_e('Pre Check Checkbox' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="precheck" <?php checked( true , $precheck) ?>>
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="precheck" <?php checked( false , $precheck) ?>>
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to check the checkbox by default.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="add_segment"><?php esc_html_e('Segment' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <select name="add_segment" id="mwb-m4wp-segment-select">
                            <option value="-1"><?php esc_html_e('--Select--', 'mautic-for-wordpress') ?></option>
                            <?php foreach($segment_list as $key => $segment) : ?>
                                <option value="<?php echo $segment['id'] ?>" <?php selected( $segment['id'] , $add_segment ) ?>>
                                    <?php echo $segment['name'] ?>
                                </option>
                            <?php endforeach ; ?>
                        </select>
                        <?php echo $helper->get_refresh_button_html( "segments" ) ?>
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
            <input type="hidden" name="integration" value="<?php echo $integation->get_id() ?>" />
            <input type="hidden" name="action" value="mwb_m4wp_integration_save" />
            <div class="mwb-m4wp-admin-button-wrap">
                <button class="button mwb-m4wp-admin-button" type="submit"><?php esc_html_e('Save' , 'mautic-for-wordpress') ?></button>
            </div>
        </form>
    </div>
    </div>
</div>

