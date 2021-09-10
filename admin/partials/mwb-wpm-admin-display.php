<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Wp_Mautic_Integration
 * @subpackage Wp_Mautic_Integration/admin/partials
 */

$helper = Mwb_Wpm_Settings_Helper::get_instance();
// phpcs:ignore WordPress.Security.NonceVerification
$current = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'connection';
// phpcs:ignore WordPress.Security.NonceVerification
$notification = '';
if ( wp_cache_get( 'mwb_m4wp_notice' ) ) {
	$notification = wp_cache_get( 'mwb_m4wp_notice' );
}

?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
			<div class="admin-display-header">
				<!-- <h1 class="mwb-header-title"> -->
					<?php esc_html_e( 'Integration with Mautic for WP', 'wp-mautic-integration' ); ?>
				<!-- </h1> -->
			</div>
			<div class="" id="doc-sup-style">
				<a href="https://docs.makewebbetter.com/wp-mautic-integration/?utm_source=MWB-wpmautic-org&utm_medium=MWB-org-backend&utm_campaign=MWB-wpmautic-doc" class="doc_support_link_color" target="_blank"
					><?php esc_html_e( 'Documentation', 'wp-mautic-integration' ); ?></a> <span>|</span>
				<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-wpmautic-org&utm_medium=MWB-org-backend&utm_campaign=MWB-wpmautic-suppport" class="doc_support_link_color" target="_blank"
					><?php esc_html_e( 'Support', 'wp-mautic-integration' ); ?></a>
			</div>
	</div>
</header>
<?php if ( '' !== $notification ) : ?>
<div class="mwb-notification-bar mwb-bg-white mwb-r-8">
	<span class="mwb-notification-txt"><?php echo esc_html( $notification ); ?></span>
	<span class="dashicons dashicons-no mwb-notification-close"></span>
</div>
<?php endif; ?>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			//phpcs:disable
			echo $helper->get_settings_tab_html( $current );
			//phpcs:enable
			?>
		</ul>
	</nav>
	<section class="mwb-section">
		<?php $helper->load_admin_template( $current ); ?>
		<div class="mwb-section-footer">
			<p class="mwb-version-txt"><?php echo esc_html( $helper->get_plugin_version_txt() ); ?></p>
		</div>
	</section>
</main>
