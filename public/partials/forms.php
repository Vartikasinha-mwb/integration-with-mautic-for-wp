<?php
$base_url = get_option('mwb_m4wp_base_url' , '') ; 
$form_id = (isset($attr['id']) && !empty($attr['id']) ) ? $attr['id'] : 0; 
$form_url = $base_url.'/form/generate.js?id='.$form_id ;
?>
<?php if($base_url != '' && $form_id != 0 ) : ?>
<div id="mwb-m4wp-form-<?php echo $form_id ?>" class="mwb-m4wp-form-container">
    <script type="text/javascript" src="<?php echo $form_url ?>"></script>
<div>
<?php endif ; ?>