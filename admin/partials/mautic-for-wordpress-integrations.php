<?php
$integrations = Mautic_For_Wordpress_Admin::get_integrations() ; 
$segment_list = Mautic_For_Wordpress_Admin::get_segment_options();
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
                <?php foreach($integrations as $key => $integration) : ?>
                    <tr>
                        <td class="name"><?php echo $integration['name'] ; ?></td>
                        <td class="des"><?php echo $integration['des'] ; ?></td>
                        <td class="status">
                            <label class="switch">
                            <input type="checkbox">
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
        <form action="" method="post">
            <table class="form-table mwb-m4wp-admin-table">
                <tr>
                    <th><label for="enable"><?php esc_html_e('Enable' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="enable">
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="enable">
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" to enable the integration. ', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="implicit"><?php esc_html_e('Implicit' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="implicit">
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="implicit">
                        <label><?php esc_html_e( 'No', 'mautic-for-wordpress' ) ?></label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to subscribe people without asking them explicitly.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="checkbox_txt"><?php esc_html_e('Checkbox Label Text' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="text" name="checkbox_txt">
                        <p class="description">
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="precheck"><?php esc_html_e('Pre Check Checkbox' , 'mautic-for-wordpress' ) ?></label></th>
                    <td>
                        <input type="radio" value="yes" name="precheck">
                        <label><?php esc_html_e( 'Yes', 'mautic-for-wordpress' ) ?></label>
                        <input type="radio" value="no" name="precheck">
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
                                <option value="<?php echo $segment['id'] ?>">
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
                        <input type="text" name="add_tag">
                        <p class="description">
                            <?php esc_html_e( 'Enter tags separated by commas to assign to contact.', 'mautic-for-wordpress' ) ?>
                        </p>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce('mwb_m4wp_integration_nonce') ?>" />
            <input type="hidden" name="integration" value="mwb_m4wp_registration" />
            <input type="hidden" name="action" value="mwb_m4wp_integration_save" />
            <button class="button" type="submit"><?php esc_html_e('Save' , 'mautic-for-wordpress') ?></button>
        </form>
        
    </div>
</div>
