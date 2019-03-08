<?php
/**
 * The template part for displaying site description
 *
 * @package Bayleaf
 * @since 1.0.0
 */

$bayleaf_description = get_bloginfo( 'description', 'display' );
if ( $bayleaf_description || is_customize_preview() ) : ?>
	<p<?php bayleaf_attr( 'site-description' ); ?>>
		<span class="site-desc">
		<?php
		echo $bayleaf_description; // WPCS xss ok. 'bloginfo' filter run through esc_html.
		?>
		</span>
	</p>
<?php endif; ?>
