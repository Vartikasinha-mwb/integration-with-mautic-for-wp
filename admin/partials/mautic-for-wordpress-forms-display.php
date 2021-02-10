<?php
$helper = new Mautic_For_Wordpress_Settings_Helper();
$forms = $helper->get_forms(true);
$base_url = get_option( 'mwb_m4wp_base_url') ; 
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Mautic Forms', 'mautic-for-wordpress' ) ?></h3>
        <?php echo $helper->get_refresh_button_html("forms"); ?>
    </div>
    <?php if ( $forms ) : ?>
    <table class="form-table">
        <thead>
            <th><?php esc_html_e('Form id' , 'mautic-for-wordpress') ?></th>
            <th><?php esc_html_e('Form Name' , 'mautic-for-wordpress') ?></th>
            <th><?php esc_html_e('Status' , 'mautic-for-wordpress') ?></th>
            <th><?php esc_html_e('ShortCode' , 'mautic-for-wordpress') ?></th>
            <th><?php esc_html_e('View' , 'mautic-for-wordpress') ?></th>
        </thead>
        <tbody>
            <?php foreach($forms['forms'] as $key => $form) : 
                $form_link = $base_url.'/s/forms/view/'.$form['id']   ; 
                ?>
                <tr class="<?php  echo ($form['isPublished']) ?  'row-active' :  'row-inactive' ?>">
                    <td><?php echo $form['id'] ?></td>
                    <td><?php echo $form['name'] ?></td>
                    <td>
                        <?php 
                        echo ($form['isPublished']) ?  'Published' :  'Not Published'
                        ?>
                    </td>
                    <td>
                        <input disabled type="text" id="form-input-<?php echo $form['id'] ?>"  value="<?php echo '[mwb_m4wp_form id='.$form['id'].']' ?>"> 
                        <a href="#" class="mwb-m4wp-form-code" form_id="<?php echo $form['id'] ?>">
                            <span class="dashicons dashicons-editor-paste-text"></span>
                        </a>
                    </td>
                    <td>
                        <a class="mwb-m4wp-form-view button" form-id="<?php echo $form['id'] ?>" form-html="<?php echo htmlspecialchars( $form['cachedHtml'] ) ?>">
                            <?php esc_html_e('View' , 'mautic-for-wordpress') ?>
                        </a>
                        <a class="mwb-m4wp-form-edit button" href="<?php echo $form_link ?>" target="_blank">
                            <?php esc_html_e('Edit' , 'mautic-for-wordpress') ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach ; ?>
        </tbody>
    </table>
    <?php endif ; ?>
    <div id="mwb-m4wp-form-html">
    </div>
</div>
