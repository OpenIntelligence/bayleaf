<?php
/**
 * The template part for displaying categories of current post
 *
 * @package Bayleaf
 * @since 1.0.0
 */

$bayleaf_categories_list = get_the_category_list( esc_html_x( ', ', 'Used between category list items, there is a space after the comma.', 'bayleaf' ) );

if ( $bayleaf_categories_list ) :
?>
	<span<?php bayleaf_attr( 'meta-categories' ); ?>>
		<?php
		if ( is_singular() ) {
			printf( '<span class="meta-title">%s</span>',
				bayleaf_get_icon( [ 'icon' => 'folder' ] )
			); // WPCS xss ok. 'bayleaf_get_icon'  returns escaped value.
		}
		echo $bayleaf_categories_list; // WPCS xss ok. Contains HTML, other values escaped.
		?>
	</span><!-- .meta-categories -->
<?php
endif;
