<?php
/**
 * Hook into WordPress core or the Theme to modify default contents
 *
 * @package Bayleaf
 * @since 1.0.0
 */

/**
 * Extend the default WordPress body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bayleaf_body_classes( $classes ) {
	// Adds a class for Single and Index view pages.
	if ( is_singular() ) {
		$classes[] = 'singular-view';

		if ( is_singular( [ 'post', 'page' ] ) ) {

			if ( ! is_front_page() && ! has_post_thumbnail() ) {
				$classes[] = 'no-post-thumbnail';
			}
		} else {
			$classes[] = 'no-post-thumbnail';
		}
	} else {
		$classes[] = 'index-view';

		if ( ! ( is_home() || is_front_page() ) ) {
			$classes[] = 'archive-view';
		}
	}

	if ( is_home() || is_front_page() ) {
		if ( is_singular() && has_post_thumbnail() ) {
			$classes[] = 'has-header-image';
		} elseif ( get_header_image() ) {
			$classes[] = 'has-header-image';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'bayleaf_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function bayleaf_post_classes( $classes ) {
	// Adds a class for posts.
	$classes[] = 'entry';

	if ( ! ( is_singular() || in_array( 'product', $classes, true ) ) ) {
		$classes[] = 'fw-tab-6 fw-tabr-4';
	}

	return $classes;
}
add_filter( 'post_class', 'bayleaf_post_classes' );

/**
 * Adds a class to control maximum width of primary site elements.
 *
 * @since 1.0.0
 *
 * @param array $attr attribute values array.
 * @return array
 */
function bayleaf_primary_wrapper( $attr ) {
	$attr['class'] .= ' wrapper';
	return $attr;
}
add_filter( 'bayleaf_get_attr_header_items', 'bayleaf_primary_wrapper' );
add_filter( 'bayleaf_get_attr_footer_items', 'bayleaf_primary_wrapper' );
add_filter( 'bayleaf_get_attr_secondary_items', 'bayleaf_primary_wrapper' );
add_filter( 'bayleaf_get_attr_page_entry_header', 'bayleaf_primary_wrapper' );

/**
 * Adds a flex wrapper class to appropriate site elements.
 *
 * @since 1.0.0
 *
 * @param array $attr attribute values array.
 * @return array
 */
function bayleaf_flex_wrapper( $attr ) {
	if ( is_singular() ) {
		$attr['class'] .= ' wrapper';
	} else {
		$attr['class'] .= ' flex-wrapper';
	}
	return $attr;
}
add_filter( 'bayleaf_get_attr_site_main', 'bayleaf_flex_wrapper' );

/**
 * Adds class to site footer.
 *
 * @since 1.0.0
 *
 * @param array $attr attribute values array.
 * @return array
 */
function bayleaf_site_footer_classes( $attr ) {
	if ( is_active_sidebar( 'footer' ) ) {
		$attr['class'] .= ' has-footer-widgets';
	}
	return $attr;
}
add_filter( 'bayleaf_get_attr_site_footer', 'bayleaf_site_footer_classes' );

/**
 * Adding Custom Images Sizes to the WordPress Media Library (Admin).
 *
 * @since 1.0.0
 *
 * @param array $size_names Array of image sizes and their names.
 * @return array
 */
function bayleaf_custom_image_sizes_to_admin( $size_names ) {
	return array_merge( $size_names,
		[
			'bayleaf-small'  => esc_html__( 'Bayleaf Small', 'bayleaf' ),
			'bayleaf-medium' => esc_html__( 'Bayleaf Medium', 'bayleaf' ),
			'bayleaf-large'  => esc_html__( 'Bayleaf Large', 'bayleaf' ),
		]
	);
}
add_filter( 'image_size_names_choose', 'bayleaf_custom_image_sizes_to_admin' );

/**
 * Create dynamic css for theme color scheme.
 *
 * @since 1.0.0
 *
 * @param str $css Dynamically generated css string.
 * @return str
 */
function bayleaf_color_scheme_css( $css ) {

	$color = bayleaf_get_mod( 'bayleaf_color_scheme', 'color' ); // Escaped by bayleaf_escape function.
	if ( ! $color ) {
		return $css;
	}

	$rgb_color = bayleaf_hex_to_rgb( $color, true );

	$css .= sprintf( '
		a,
		.social-navigation ul.nav-menu--social a:hover,
		.social-navigation ul.nav-menu--social a:focus,
		.site-navigation ul ul a:hover,
		.site-navigation ul ul a:focus,
		.comment-metadata a:hover,
		.comment-metadata a:focus,
		.comment-author a:hover,
		.comment-author a:focus,
		.woocommerce div.product .star-rating,
		.dp-categories a:hover,
		.dp-categories a:focus,
		ul.products .button,
		ul.products a.added_to_cart,
		.woocommerce-tabs .wc-tabs li a:hover,
		.woocommerce-tabs .wc-tabs li a:focus,
		.entry-featured-content .quick-action,
		.dp-featured-content .quick-action {
			color: %1$s;
		}
		a.button,
		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.ui-slider .ui-slider-range.ui-slider-range,
		.ui-slider .ui-slider-handle.ui-slider-handle,
		.ui-widget-content {
			background-color: %1$s;
		}
		input[type="date"]:focus,
		input[type="time"]:focus,
		input[type="datetime-local"]:focus,
		input[type="week"]:focus,
		input[type="month"]:focus,
		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="tel"]:focus,
		input[type="number"]:focus,
		textarea:focus,
		select:focus {
			-webkit-box-shadow: inset 0 0 1px %1$s;
	        		box-shadow: inset 0 0 1px %1$s;
		}
		.site-description,
		.slider1 .sub-entry {
			background-color: rgba( %2$s, 0.8 );
		}
	', $color, $rgb_color );

	return $css;
}
add_filter( 'bayleaf_inline_styles', 'bayleaf_color_scheme_css' );

/**
 * Create dynamic css for theme color scheme.
 *
 * @since 1.0.0
 *
 * @param str $css Dynamically generated css string.
 * @return str
 */
function bayleaf_editor_color_scheme_css( $css ) {

	$color = bayleaf_get_mod( 'bayleaf_color_scheme', 'color' ); // Escaped by bayleaf_escape function.
	if ( ! $color ) {
		return $css;
	}

	$css .= sprintf( '
		a,
		.editor-rich-text__tinymce a,
		.wp-block-freeform.block-library-rich-text__tinymce a {
			color: %1$s;
		}
		a.button,
		.wp-block-freeform.block-library-rich-text__tinymce a.button {
			background-color: %1$s;
		}
	', $color );

	return $css;
}
add_filter( 'bayleaf_dynamic_classic_editor_styles', 'bayleaf_editor_color_scheme_css' );
add_filter( 'bayleaf_gutenberg_styles', 'bayleaf_editor_color_scheme_css' );

/**
 * Disable google fonts if user do not want to use them.
 *
 * @since 1.0.0
 *
 * @param array $fonts Google fonts array.
 * @return array
 */
function bayleaf_disable_google_fonts( $fonts ) {

	if ( '' === bayleaf_get_mod( 'bayleaf_use_google_fonts', 'none' ) ) {
		return [];
	}

	return $fonts;
}
add_filter( 'bayleaf_fonts', 'bayleaf_disable_google_fonts' );
