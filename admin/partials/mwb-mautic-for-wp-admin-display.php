<?php
$helper  = MWB_Mautic_For_WP_Settings_Helper::get_instance();
$current = isset( $_GET['tab'] ) ? $_GET['tab'] : 'connection';
$notification = '';
if ( wp_cache_get( 'mwb_m4wp_notice' ) ) {
	$notification = wp_cache_get( 'mwb_m4wp_notice' );
}

?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title">
			<?php esc_html_e( 'Mautic WordPress Integration', 'makewebbetter-mautic-for-wordpress' ); ?>
		</h1>
		<a href="https://makewebbetter.com/contact-us/" target="_blank"
			class="mwb-link"><?php esc_html_e( 'Support', 'makewebbetter-mautic-for-wordpress' ); ?></a>
	</div>
</header>
<?php if ( $notification !== '' ) : ?>
<div class="mwb-notification-bar mwb-bg-white mwb-r-8">
	<span class="mwb-notification-txt"><?php echo esc_html( $notification ); ?></span>
	<span class="dashicons dashicons-no mwb-notification-close"></span>
</div>
<?php endif; ?>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			echo $helper->get_settings_tab_html( $current );
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
