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

// User Registration Plugin Added.
?>
<div class="mwb-m4wp-admin-panel-main">
	<div class="m4wp-overview__banner">
		<img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . '/src/images/Mautic-for-WordPress-Banner.jpg' ); ?>" alt="Overview banner image">
	</div>
	<div class="m4wp-overview__content">
		<div class="m4wp-overview__content-description">
			<h2><?php echo esc_html_e( 'What is Integration with Mautic for WP?', 'wp-mautic-integration' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'Integration with Mautic for WP plugin is the simple and easy-to-use plugin that helps you to sync your WordPress website data with your Mautic. It is a hassle-free solution for you, if you are willing to capture leads, signups, and subscribers easily by integrating your website with Mautic.',
					'wp-mautic-integration'
				);
				?>
				<br>
				<?php
				esc_html_e(
					'You can also integrate multiple WordPress forms with Mautic directly using either Basic or OAuth2 Mautic API. Integration with Mautic for WordPress plugin also supports you to add Mautic forms into your web pages through shortcodes.',
					'wp-mautic-integration'
				);
				?>
			</p>
			<h3><?php esc_html_e( 'USING THE INTEGRATION WITH MAUTIC FOR WP YOU CAN:', 'wp-mautic-integration' ); ?></h3>
			<div class="m4wp-overview__features-wrapper">
				<ul class="m4wp-overview__features">
					<li><?php esc_html_e( 'Integrate Mautic with WP website.', 'wp-mautic-integration' ); ?></li>
					<li><?php esc_html_e( 'Sync your WordPress default registration and comment forms data.', 'wp-mautic-integration' ); ?></li>
					<li><?php esc_html_e( 'Have a dedicated Mautic dashboard over your WordPress panel.', 'wp-mautic-integration' ); ?></li>
					<li><?php esc_html_e( 'Assign tags and add segments to your captured leads with WordPress.', 'wp-mautic-integration' ); ?></li>
					<li><?php esc_html_e( 'Implement Mautic tracking code on your website.', 'wp-mautic-integration' ); ?></li>
					<li><?php esc_html_e( 'Embed Mautic forms into your WordPress content using shortcodes.', 'wp-mautic-integration' ); ?></li>
				</ul>
				<div class="m4wp-overview__video--url">
					<iframe id="video_display" width="470" height="295" src="https://www.youtube.com/embed/Rqm9SHbZAnk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
				</div>
			</div>
		</div>
		<h1> <?php esc_html_e( 'The Free Plugin Benefits', 'wp-mautic-integration' ); ?></h1>
		<div class="m4wp-overview__keywords">
			<div class="m4wp-overview__keywords-item">
				<div class="m4wp-overview__keywords-card">
					<div class="m4wp-overview__keywords-image">
						<img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . 'src/images/Icons_Integrate with Security.jpg' ); ?>" alt="Integration-security image">
					</div>
					<div class="m4wp-overview__keywords-text">
						<h3 class="m4wp-overview__keywords-heading"><?php echo esc_html_e( ' Integrate with Security', 'wp-mautic-integration' ); ?></h3>
						<p class="m4wp-overview__keywords-description">
							<?php
							esc_html_e(
								'Integrate Mautic with your WordPress website with secured API authentications - Basic & OAuth2. Implement  Mautic tracking code on your website by adding a Mautic instance URL to track your user’s data.',
								'wp-mautic-integration'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="m4wp-overview__keywords-item">
				<div class="m4wp-overview__keywords-card">
					<div class="m4wp-overview__keywords-image">
						<img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . 'src/images/Icons_Sync Mautic Forms & Integrations.jpg' ); ?>" alt="Sync Mautic Forms image">
					</div>
					<div class="m4wp-overview__keywords-text">
						<h3 class="m4wp-overview__keywords-heading"><?php echo esc_html_e( 'Sync Mautic Forms & Integrations', 'wp-mautic-integration' ); ?></h3>
						<p class="m4wp-overview__keywords-description"><?php echo esc_html_e( 'Sync your WordPress default registration and comment forms data with this WordPress Mautic integration plugin.', 'wp-mautic-integration' ); ?></p>
					</div>
				</div>
			</div>
			<div class="m4wp-overview__keywords-item">
				<div class="m4wp-overview__keywords-card">
					<div class="m4wp-overview__keywords-image">
						<img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . 'src/images/Icons_Data-driven Dashboard.jpg' ); ?>" alt="Data Driven image">
					</div>
					<div class="m4wp-overview__keywords-text">
						<h3 class="m4wp-overview__keywords-heading"><?php echo esc_html_e( 'Data-driven Dashboard', 'wp-mautic-integration' ); ?></h3>
						<p class="m4wp-overview__keywords-description">
							<?php
							echo esc_html_e(
								'Have a dedicated Mautic dashboard over your WordPress panel that shows each data in an easy-to-read graphical format like Created Leads, Pages Hit, Top Segments, etc.',
								'wp-mautic-integration'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="m4wp-overview__keywords-item">
				<div class="m4wp-overview__keywords-card">
					<div class="m4wp-overview__keywords-image">
						<img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . 'src/images/Icons_Tags & Segments .jpg' ); ?>" alt="Tags and Segments image">
					</div>
					<div class="m4wp-overview__keywords-text">
						<h3 class="m4wp-overview__keywords-heading"><?php echo esc_html_e( 'Tags & Segments', 'wp-mautic-integration' ); ?></h3>
						<p class="m4wp-overview__keywords-description">

							<?php
							echo esc_html_e(
								'Assign tags and add segments to your captured leads with WordPress for a more precise and structured contacts list.',
								'wp-mautic-integration'
							);
							?>

						</p>
					</div>
				</div>
			</div>
			<div class="m4wp-overview__keywords-item">

				<div class="m4wp-overview__keywords-card mwb-card-support">

					<div class="m4wp-overview__keywords-image">
						<a href="https://makewebbetter.com/contact-us/"><img src="<?php echo esc_html( plugin_dir_url( dirname( __FILE__ ) ) . 'src/images/Support.png' ); ?>" alt="Support image"></a>
					</div>
					<div class="m4wp-overview__keywords-text">
						<h3 class="m4wp-overview__keywords-heading"><?php echo esc_html_e( 'Support', 'wp-mautic-integration' ); ?></h3>
						<p class="m4wp-overview__keywords-description">
							<?php
							esc_html_e(
								"Phone, Email & Skype support. Our Support is ready to assist you regarding any query, issue, or feature request and if that doesn't help our Technical team will connect with you personally and have your query
								resolved.",
								'wp-mautic-integration'
							);
							?>
						</p>
					</div>
					<a href="https://makewebbetter.com/contact-us/" title=""></a>

				</div>
			</div>
		</div>
	</div>
</div>
<?php
// User Registration Plugin Added.
