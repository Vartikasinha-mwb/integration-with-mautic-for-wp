<div class="connection-form-wrap">
    <form method="post">
        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Status' , 'mautic-for-wordpress') ?></th>
                <td>
                    <?php 
                        if(get_option('mwb_m4wp_connection_status' , false)){
                            echo '<span class="span-connected">Connected</span>' ; 
                        }else{
                            echo '<span class="span-disconnected">DisConnected</span>' ; 
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Type' , 'mautic-for-wordpress') ?></th>
                <td>
                    <select name="authentication_type" id="mwb-m4wp-auth-type">
                        <option value="basic" <?php selected('basic' , $type) ?>>
                            <?php esc_html_e('Basic' , 'mautic-for-wordpress') ?>
                        </option>
                        <option value="oauth2" <?php selected('oauth2' , $type) ?>>
                            <?php esc_html_e('OAuth2' , 'mautic-for-wordpress') ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Mautic Url' , 'mautic-for-wordpress') ?></th>
                <td>
                    <input id="input-baseurl" type="text" value="<?php echo $baseurl ?>" name="baseurl" />
                </td>
            </tr>
            <tr class="mwb-m4wp-oauth-row <?php echo $row_oauth2 ?>">
                <th><?php esc_html_e('Client id' , 'mautic-for-wordpress') ?></th>
                <td>
                    <input id="input-id" type="password" value="<?php echo $client_id ?>" name="client_id" />
                </td>
            </tr>
            <tr class="mwb-m4wp-oauth-row <?php echo $row_oauth2 ?>">
                <th><?php esc_html_e('Client Secret' , 'mautic-for-wordpress') ?></th>
                <td>
                    <input id="input-secret" type="password" value="<?php echo $client_secret ?>" name="client_secret" />
                </td>
            </tr>
            <tr class="mwb-m4wp-basic-row <?php echo $row_basic ?>">
                <th><?php esc_html_e('Username' , 'mautic-for-wordpress') ?></th>
                <td>
                    <input id="input-username" type="text" value="<?php echo $username ?>" name="username" />
                </td>
            </tr>
            <tr class="mwb-m4wp-basic-row <?php echo $row_basic ?>">
                <th><?php esc_html_e('Password' , 'mautic-for-wordpress') ?></th>
                <td>
                    <input id="input-password" type="password" value="<?php echo $password ?>" name="password" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="mwb_m4wp_save" />
        <button id="mwb-m4wp-save-btn" type="submit" class="button"><?php esc_html_e('Save' , 'mautic-for-wordpress') ?></button>
    </form>
    <?php if(!empty($credentials)): ?>
        <button type="button" class="button" id="mwb-fwpro-test-connection"><?php esc_html_e('Test Connection' , 'mautic-for-wordpress') ?></button>
    <?php endif ; ?>
    
    <?php if( $type=="oauth2" ) : ?>
    <a class="button"
        href="<?php echo  wp_nonce_url( admin_url('/?m4wp_auth=1') , 'm4wp_auth_nonce' , 'm4wp_auth_nonce' )  ?>">
        <?php esc_html_e('Authorize App' , 'mautic-for-wordpress') ?>
    </a>
    <?php endif ; ?>
</div>