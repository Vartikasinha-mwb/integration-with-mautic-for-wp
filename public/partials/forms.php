<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Makewebbetter_Mautic_For_Wordpress
 * @subpackage Makewebbetter_Mautic_For_Wordpress/public/partials
 */

$base_url = get_option( 'mwb_m4wp_base_url', '' );
$form_id  = ( isset( $attr['id'] ) && ! empty( $attr['id'] ) ) ? $attr['id'] : 0;
$form_url = $base_url . '/form/generate.js?id=' . $form_id;
?>
<?php if ( '' !== $base_url && 0 !== $form_id ) : ?>
<div id='mwb-m4wp-form-<?php echo esc_attr( $form_id ); ?>' class="mwb-m4wp-form-container">
	<?php
	//phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript 
	?>
<script type="text/javascript" src="<?php echo esc_attr( $form_url ); ?>"></script>
	<?php
	//phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript 
	?>
<div>
<?php endif;
?>
