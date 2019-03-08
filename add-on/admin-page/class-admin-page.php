<?php
/**
 * Display Bayleaf theme admin page in Dashboard > Appearance.
 *
 * @package Bayleaf
 * @since 1.3.4
 */

namespace bayleaf;

/**
 * Display Bayleaf theme admin page in Dashboard > Appearance.
 *
 * @since  1.3.4
 */
class Admin_Page {

	/**
	 * Constructor method.
	 *
	 * @since  1.3.4
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.3.4
	 */
	public function init() {
		global $pagenow;

		// Add Welcome message on Theme activation.
		if ( is_admin() && 'themes.php' === $pagenow && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', [ $this, 'welcome_theme_notice' ], 99 );
		}
	}

	/**
	 * Display Welcome Message on Theme activation.
	 *
	 * @since  1.3.4
	 *
	 * @return void
	 */
	public function welcome_theme_notice() {
		// Since Manta is not the active theme, let user know Manta Plus will not work.
		printf(
			'<div class="updated notice is-dismissible theme-welcome-notice">
				<p>%s</p><p>%s</p>
					<ol class="bayleaf-admin-tips">
						<li>%s</li>
						<li>%s</li>
						<li>%s<a href="%s">%s</a>%s</li>
						<li>%s</li>
					</ol>
				<p>%s</p>
			</div>',
			esc_html__( 'Hi there!', 'bayleaf' ),
			esc_html__( 'Thanks for trying Bayleaf. Here are some quick tips to get you started.', 'bayleaf' ),
			esc_html__( 'Use "Display Posts" widget from Appearance > Widgets to create various posts layout.', 'bayleaf' ),
			esc_html__( 'Use "Blank Widget" widget from Appearance > Widgets to create vertical gaps between widgets.', 'bayleaf' ),
			esc_html__( 'Visit ', 'bayleaf' ),
			esc_url( 'https://vedathemes.com/bayleaf-pro/documentation/' ),
			esc_html__( 'quick setup guide', 'bayleaf' ),
			esc_html__( ' to get started', 'bayleaf' ),
			esc_html__( 'Contact us at contact@vedathemes.com for any help', 'bayleaf' ),
			esc_html__( 'Thank You', 'bayleaf' )
		);

		?>
		<style type="text/css" media="screen">

			.notice.theme-welcome-notice {
				padding: 2.5em 5em;
				background: rgba(0,0,0,.01);
				border: 1em solid rgba(255,255,255,.85);
			}

			.notice.theme-welcome-notice p,
			.notice.theme-welcome-notice a {
				font-size: 14px;
			}

		</style>

		<?php
	}
}

$admin_page = new Admin_Page();
$admin_page->init();
