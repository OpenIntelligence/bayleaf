<?php
/**
 * The template part for displaying tags of current post
 *
 * @package Bayleaf
 * @since 1.0.0
 */

$bayleaf_tags_list = get_the_tag_list( '', esc_html_x( ', ', 'Used between tags list items, there is a space after the comma.', 'bayleaf' ) );

if ( $bayleaf_tags_list ) :
?>
	<span<?php bayleaf_attr( 'meta-tags' ); ?>>
		<?php
		printf( '<span class="meta-title">%s</span>',
			bayleaf_get_icon( [ 'icon' => 'tags' ] )
		); // WPCS xss ok. 'bayleaf_get_icon'  returns escaped value.
		echo $bayleaf_tags_list; // WPCS xss ok. Contains HTML, other values escaped.
		?>
	</span><!-- .meta-tags -->
<?php
endif;
