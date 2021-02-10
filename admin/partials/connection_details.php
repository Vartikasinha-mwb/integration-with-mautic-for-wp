<div class="connection-detail-wrap">
    <table class="form-table">
        <tr>
            <th><?php esc_html_e('Status' , 'mautic-for-wordpress') ?></th>
            <td><?php echo $connection_status ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Authentication Type' , 'mautic-for-wordpress') ?></th>
            <td><?php echo $auth_type ?></td>
        </tr>
        <tr>
            <th><?php esc_html_e('Admin Email' , 'mautic-for-wordpress') ?></th>
            <td>
                <?php echo $user_email ?>
            </td>
        </tr>
    </table>
</div>
<a class="button"
    href="<?php echo  wp_nonce_url( admin_url('/?m4wp_reset=1') , 'm4wp_auth_nonce' , 'm4wp_auth_nonce' )  ?>">
    <?php esc_html_e( 'Reset Connection', 'mautic-for-wordpress' ) ?>
</a>