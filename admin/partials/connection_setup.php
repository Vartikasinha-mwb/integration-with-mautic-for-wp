<div class="connection-form-wrap">
    <form method="post">
        <table class="form-table">
            <tr>
                <th>Status</th>
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
                <th>Type</th>
                <td>
                    <select name="authentication_type" id="mwb-m4wp-auth-type">
                        <option value="basic" <?php selected('basic' , $type) ?>>Basic</option>
                        <option value="oauth2" <?php selected('oauth2' , $type) ?>>OAuth2</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Mautic Url</th>
                <td>
                    <input id="input-baseurl" type="text" value="<?php echo $baseurl ?>" name="baseurl" />
                </td>
            </tr>
            <tr class="mwb-m4wp-oauth-row <?php echo $row_oauth2 ?>">
                <th>Client id</th>
                <td>
                    <input id="input-id" type="password" value="<?php echo $client_id ?>" name="client_id" />
                </td>
            </tr>
            <tr class="mwb-m4wp-oauth-row <?php echo $row_oauth2 ?>">
                <th>Client Secret</th>
                <td>
                    <input id="input-secret" type="password" value="<?php echo $client_secret ?>" name="client_secret" />
                </td>
            </tr>
            <tr class="mwb-m4wp-basic-row <?php echo $row_basic ?>">
                <th>Username</th>
                <td>
                    <input id="input-username" type="text" value="<?php echo $username ?>" name="username" />
                </td>
            </tr>
            <tr class="mwb-m4wp-basic-row <?php echo $row_basic ?>">
                <th>Password</th>
                <td>
                    <input id="input-password" type="password" value="<?php echo $password ?>" name="password" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="mwb_m4wp_save" />
        <button id="mwb-m4wp-save-btn" type="submit" class="button">Save</button>
    </form>
    <?php if(!empty($credentials)): ?>
        <button type="button" class="button" id="mwb-fwpro-test-connection">Test Connection</button>
    <?php endif ; ?>
    
    <?php if( $type=="oauth2" &&  !get_option('mwb_m4wp_oauth2_success', false)) : ?>
    <a class="button"
        href="<?php echo  wp_nonce_url( admin_url('/?m4wp_auth=1') , 'm4wp_auth_nonce' , 'm4wp_auth_nonce' )  ?>">Authorize
        App</a>

    <?php endif ; ?>
</div>