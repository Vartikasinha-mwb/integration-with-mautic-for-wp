<?php
$forms = MWB_M4WP_Mautic_Api::get_forms();
// echo '<pre>';
// print_r($forms);
// echo '</pre>';
?>
<div class="wrap">
    <div class="mwb-m4wp-admin-panel-head">
        <h3><?php esc_html_e( 'Mautic Forms', 'mautic-for-wordpress' ) ?></h3>
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
            <?php foreach($forms['forms'] as $key => $form) : ?>
                <tr>
                    <td><?php echo $form['id'] ?></td>
                    <td><?php echo $form['name'] ?></td>
                    <td>
                        <?php 
                        echo ($form['isPublished']) ?  'Published' :  'Not Published'
                        ?>
                    </td>
                    <td><?php echo '[mwb_m4wp_form id='.$form['id'].']' ?></td>
                    <td>
                        <a class="mwb-m4wp-form-view button" form-id="<?php echo $form['id'] ?>" form-html="<?php echo htmlspecialchars( $form['cachedHtml'] ) ?>">
                            <?php esc_html_e('View' , 'mautic-for-wordpress') ?>
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
