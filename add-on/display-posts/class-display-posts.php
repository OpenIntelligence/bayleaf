<?php
/**
 * A widget to customize display of Posts, pages and custom post types.
 *
 * @package Bayleaf
 * @since 1.0.0
 */

namespace bayleaf;

/**
 * Customize display of Posts, pages and custom post types.
 *
 * @since  1.0.0
 */
class Display_Posts {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {}

	/**
	 * Returns the instance of this class.
	 *
	 * @since  1.0.0
	 *
	 * @return object Instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_filter( 'bayleaf_widget_custom_classes', [ self::get_instance(), 'widget_classes' ], 10, 2 );
		add_filter( 'bayleaf_dp_wrapper_classes', [ self::get_instance(), 'wrapper_classes' ], 10, 3 );
		add_filter( 'bayleaf_dp_entry_classes', [ self::get_instance(), 'entry_classes' ], 10, 3 );
		add_filter( 'bayleaf_dp_styles', [ self::get_instance(), 'dp_styles' ], 10, 2 );
		add_filter( 'bayleaf_after_dp_widget_title', [ self::get_instance(), 'dp_wid_title' ], 10, 2 );
		add_filter( 'bayleaf_dp_excerpt_length', [ self::get_instance(), 'excerpt_length' ], 10, 2 );
		add_filter( 'bayleaf_dp_excerpt_more', [ self::get_instance(), 'excerpt_more' ], 10, 2 );
		add_action( 'widgets_init', [ self::get_instance(), 'register_custom_widget' ] );
		add_action( 'admin_enqueue_scripts', [ self::get_instance(), 'enqueue_admin' ] );
		add_action( 'bayleaf_dp_entry', [ self::get_instance(), 'dp_entry' ], 10, 3 );
		add_action( 'bayleaf_after_dp_loop', [ self::get_instance(), 'navigate' ] );
	}

	/**
	 * Register widget display styles.
	 *
	 * @param array $styles   Array of supported posts display styles.
	 * @param array $instance Settings for the current widget instance.
	 * @return array Array of supported display styles.
	 */
	public function dp_styles( $styles, $instance ) {
		return [
			'list-view1' => esc_html__( 'List View 1', 'bayleaf' ),
			'grid-view1' => esc_html__( 'Grid View 1', 'bayleaf' ),
			'grid-view2' => esc_html__( 'Grid View 2', 'bayleaf' ),
			'grid-view3' => esc_html__( 'Grid View 3', 'bayleaf' ),
			'slider1'    => esc_html__( 'Slider 1', 'bayleaf' ),
			'slider2'    => esc_html__( 'Slider 2', 'bayleaf' ),
		];
	}

	/**
	 * Add classes to widget's main wrapper.
	 *
	 * @param str   $classes  Comma separated widget classes.
	 * @param array $widget_data {
	 *     Current widget's data to generate customized output.
	 *     @type str   $widget_id  Widget ID.
	 *     @type int   $widget_pos Widget position in widgetlayer widget-area.
	 *     @type array $instance   Current widget instance settings.
	 *     @type str   $id_base    Widget ID base.
	 * }
	 * @return array Widget classes.
	 */
	public function widget_classes( $classes, $widget_data ) {
		$instance = $widget_data[2];
		if ( isset( $instance['styles'] ) && false !== strpos( $instance['styles'], 'grid' ) ) {
			$classes[] = 'posts-grid';
		}

		return $classes;
	}

	/**
	 * Register widget display posts entry wrapper classes.
	 *
	 * @param str    $classes  Comma separated entry posts classes.
	 * @param array  $instance Settings for the current widget instance.
	 * @param Object $widget   The widget instance.
	 * @return array Entry posts classes.
	 */
	public function wrapper_classes( $classes, $instance, $widget ) {
		$classes[] = 'index-view';

		if ( false !== strpos( $instance['styles'], 'grid' ) ) {
			$classes[] = 'flex-wrapper dp-grid';
		} else {
			$classes[] = 'dp-list';
		}

		if ( false !== strpos( $instance['styles'], 'slider' ) ) {
			$classes[] = 'slider-wrapper';
			$classes[] = 'widescreen';
		}

		return $classes;
	}

	/**
	 * Register widget display posts entry classes.
	 *
	 * @param str    $classes  Comma separated entry posts classes.
	 * @param array  $instance Settings for the current widget instance.
	 * @param Object $widget   The widget instance.
	 * @return str Entry posts classes.
	 */
	public function entry_classes( $classes, $instance, $widget ) {

		if ( false !== strpos( $instance['styles'], 'grid' ) ) {
			if ( 'grid-view2' === $instance['styles'] ) {
				$classes[] = 'entry fw-tab-6 fw-tabr-6';
			} else {
				$classes[] = 'entry fw-tab-6 fw-tabr-4';
			}
		}

		return $classes;
	}

	/**
	 * Display widget content to front-end.
	 *
	 * @param array  $args     Widget display arguments.
	 * @param array  $instance Settings for the current widget instance.
	 * @param Object $widget   The widget instance.
	 */
	public function dp_entry( $args, $instance, $widget ) {
		$display = $this->get_style_args( $instance['styles'] );
		if ( ! empty( $display ) ) {
			if ( false !== strpos( $instance['styles'], 'grid' ) ) {
				echo '<div class="entry-index-wrapper">';
				$this->dp_display_entry( $display, $instance['styles'] );
				echo '</div>';
			} else {
				$this->dp_display_entry( $display, $instance['styles'] );
			}
		}
	}

	/**
	 * Add items to widget title area.
	 *
	 * @param array $after_title Items before closing of widget title.
	 * @param array $instance    Settings for the current widget instance.
	 * @return str
	 */
	public function dp_wid_title( $after_title, $instance ) {
		$link_html = '';

		// Change only if theme specific after_title args has not been altered.
		if ( '</span></h3>' !== $after_title ) {
			return $after_title;
		}

		if ( $instance['taxonomy'] && ! empty( $instance['terms'] ) ) {
			foreach ( $instance['terms'] as $cur_term ) {
				$term_link = get_term_link( $cur_term, $instance['taxonomy'] );
				if ( ! is_wp_error( $term_link ) ) {
					$link_html = sprintf( '<span class="dp-term-links"><a class="term-link" href="%1$s">%2$s %3$s</a></span>', esc_url( $term_link ), esc_html__( 'View All', 'bayleaf' ), bayleaf_get_icon( array( 'icon' => 'long-arrow-right' ) ) );
					break;
				}
			}
		}

		return '</span>' . $link_html . '</h3>';
	}

	/**
	 * Display entry content to front-end.
	 *
	 * @param array $display_args Content display arguments.
	 * @param str   $style  Current display post style.
	 */
	public function dp_display_entry( $display_args, $style ) {
		foreach ( $display_args as $args ) {
			if ( is_array( $args ) ) {
				bayleaf_markup( 'sub-entry', [ [ [ $this, 'dp_display_entry' ], $args, $style ] ] );
			} else {
				switch ( $args ) {
					case 'title':
						$this->title();
						break;
					case 'date':
						$this->date();
						break;
					case 'ago':
						$this->ago();
						break;
					case 'author':
						$this->author();
						break;
					case 'content':
						$this->content();
						break;
					case 'excerpt':
						$this->excerpt( $style );
						break;
					case 'category':
						$this->category();
						break;
					case 'meta':
						$this->meta();
						break;
					case 'thumbnail-small':
						$this->featured( 'thumbnail', $style );
						break;
					case 'thumbnail-medium':
						$this->featured( 'bayleaf-medium', $style );
						break;
					case 'thumbnail-large':
						$this->featured( 'bayleaf-large', $style );
						break;
					case 'no-thumb':
						$this->featured( false, $style );
						break;
					default:
						do_action( 'bayleaf_display_dp_item', $args );
						break;
				}
			}
		}
	}

	/**
	 * Enqueue scripts and styles to admin.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin() {
		$screen = get_current_screen();
		if ( ! in_array( $screen->id, array( 'page', 'widgets', 'customize' ), true ) ) {
			return;
		}

		wp_enqueue_style(
			'bayleaf_display_posts_admin_style',
			get_template_directory_uri() . '/add-on/display-posts/admin/displayposts.css',
			array(),
			BAYLEAF_THEME_VERSION,
			'all'
		);
		wp_enqueue_script(
			'bayleaf_display_posts_admin_js',
			get_template_directory_uri() . '/add-on/display-posts/admin/displayposts.js',
			[ 'jquery' ],
			BAYLEAF_THEME_VERSION,
			true
		);
	}

	/**
	 * Get args for displaying elements for specific dp style.
	 *
	 * @param str $style Style for this widget instance.
	 * @return array
	 */
	public function get_style_args( $style ) {
		/*
		 * Default element display instructions.
		 * Instructions array to display particular HTML element as per given sequence.
		 */

		switch ( $style ) {
			case 'list-view1':
				$d = [ 'thumbnail-medium', [ 'title', 'excerpt' ] ];
				break;
			case 'grid-view1':
				$d = [ 'thumbnail-medium', [ 'title' ] ];
				break;
			case 'grid-view2':
				$d = [ 'thumbnail-medium', [ 'category', 'title' ] ];
				break;
			case 'grid-view3':
				$d = [ 'thumbnail-medium', [ 'category', 'title' ] ];
				break;
			case 'slider1':
				$d = [ 'thumbnail-large', [ 'category', 'title', 'excerpt' ] ];
				break;
			case 'slider2':
				$d = [ 'thumbnail-large', [ [ 'title', 'excerpt' ] ] ];
				break;
			default:
				$d = [];
		}

		return apply_filters( 'bayleaf_dp_style_args', $d, $style );
	}

	/**
	 * Display post entry title.
	 *
	 * @since 1.0.0
	 */
	public function title() {
		if ( get_the_title() ) {
			the_title(
				sprintf(
					'<div class="dp-title"><a class="dp-title-link" href="%s" rel="bookmark">',
					esc_url( get_permalink() )
				),
				'</a></div>'
			);
		}
	}

	/**
	 * Display post entry date.
	 *
	 * @since 1.0.0
	 */
	public function date() {

		printf( '<div class="dp-date"><time datetime="%s">%s</time></div>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date( 'M j, Y' ) )
		);
	}

	/**
	 * Display human readable post entry date.
	 *
	 * @since 1.0.0
	 */
	public function ago() {

		/* translators: %s: human-readable time difference */
		$time = sprintf( esc_html_x( '%s ago', 'human-readable time difference', 'bayleaf' ),
		esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) );

		printf( '<div class="dp-date">%s</div>', $time ); // WPCS xss ok. Variable escaped properly.
	}

	/**
	 * Display post entry author.
	 *
	 * @since 1.0.0
	 */
	public function author() {

		printf( '<div class="dp-author"><a href="%s"><span>%s</span></a></div>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author_meta( 'display_name' ) )
		);
	}

	/**
	 * Display post featured content.
	 *
	 * @since 1.0.0
	 *
	 * @param str $size Thumbanil Size.
	 * @param str $style  Current display post style.
	 */
	public function featured( $size, $style = '' ) {
		if ( bayleaf_get_mod( 'bayleaf_thumbnail_placeholder', 'none' ) || has_post_thumbnail() ) {

			if ( $style && in_array( $style, [ 'slider1', 'slider2' ], true ) ) {
				$featured_content = [
					[ [ $this, 'thumbnail' ], $size ],
				];
			} else {
				$featured_content = [
					[ 'bayleaf_get_template_partial', 'template-parts/meta', 'meta-permalink' ],
					[ [ $this, 'thumbnail' ], $size ],
				];
			}

			bayleaf_markup( 'dp-featured-content', $featured_content );
		}
	}

	/**
	 * Display post entry thumbnail.
	 *
	 * @since 1.0.0
	 *
	 * @param str $size Thumbanil Size.
	 */
	public function thumbnail( $size ) {
		if ( ! has_post_thumbnail() ) {
			return;
		}

		if ( $size ) {
			echo '<div class="dp-thumbnail">';
			the_post_thumbnail( $size );
			echo '</div>';
		}
	}

	/**
	 * Display post content.
	 *
	 * @since 1.0.0
	 */
	public function content() {
		echo '<div class="dp-content">';
		the_content();
		echo '</div>';
	}

	/**
	 * Display post content.
	 *
	 * @since 1.0.0
	 *
	 * @param str $style  Current display post style.
	 */
	public function excerpt( $style ) {

		// Short circuit filter.
		$check = apply_filters( 'bayleaf_display_posts_excerpt', false, $style );
		if ( false !== $check ) {
			return;
		}

		$text = get_the_content( '' );
		$text = wp_strip_all_tags( strip_shortcodes( $text ) );

		/** This filter is documented in wp-includes/post-template.php */
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		/**
		 * Filters the number of words in an excerpt.
		 *
		 * @since 1.0.0
		 *
		 * @param int $number The number of words. Default 55.
		 */
		$excerpt_length = apply_filters( 'bayleaf_dp_excerpt_length', 55, $style );

		// Generate excerpt teaser text and link.
		$exrpt_url   = esc_url( get_permalink() );
		$exrpt_text  = esc_html__( 'Continue Reading', 'bayleaf' );
		$exrpt_title = get_the_title();

		if ( 0 === strlen( $exrpt_title ) ) {
			$screen_reader = '';
		} else {
			$screen_reader = sprintf( '<span class="screen-reader-text">%s</span>', $exrpt_title );
		}

		$excerpt_teaser = sprintf( '<p class="dp-link-more"><a class="dp-more-link" href="%1$s">%2$s &rarr; %3$s</a></p>', $exrpt_url, $exrpt_text, $screen_reader );

		/**
		 * Filters the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @since 1.0.0
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$excerpt_more = apply_filters( 'bayleaf_dp_excerpt_more', ' ' . $excerpt_teaser, $style );
		$text         = wp_trim_words( $text, $excerpt_length, $excerpt_more );

		printf( '<div class="dp-excerpt">%s</div>', $text ); // WPCS xss ok.
	}

	/**
	 * Modify display post's excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length Excerpt length.
	 * @param str $style  Current display post style.
	 * @return int Excerpt length.
	 */
	public function excerpt_length( $length, $style ) {

		if ( 'slider1' === $style ) {
			$length = 0;
		}

		if ( 'slider2' === $style ) {
			$length = 25;
		}

		return $length;
	}

	/**
	 * Modify display post's excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param str $teaser Excerpt teaser.
	 * @param str $style  Current display post style.
	 * @return int Excerpt teaser.
	 */
	public function excerpt_more( $teaser, $style ) {

		if ( 'slider1' === $style ) {
			$exrpt_url  = esc_url( get_permalink() );
			$exrpt_text = esc_html__( 'Read More', 'bayleaf' );
			$teaser     = sprintf( '<p class="dp-link-more"><a class="dp-more-link" href="%1$s">%2$s</a></p>', $exrpt_url, $exrpt_text );
		}

		return $teaser;
	}

	/**
	 * Display slider navigation.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Settings & args for the current widget instance.
	 */
	public function navigate( $args ) {
		$instance = $args['instance'];
		$query    = $args['query'];

		if ( 1 >= $query->post_count ) {
			return;
		}

		if ( false === strpos( $instance['styles'], 'slider' ) ) {
			return;
		}

		$navigation  = sprintf(
			'<button class="dp-prev-slide">%1$s<span class="screen-reader-text">%2$s</span></button>',
			bayleaf_get_icon( [ 'icon' => 'angle-left' ] ),
			esc_html__( 'Previous Slide', 'bayleaf' )
		);
		$navigation .= sprintf(
			'<button class="dp-next-slide">%1$s<span class="screen-reader-text">%2$s</span></button>',
			bayleaf_get_icon( [ 'icon' => 'angle-right' ] ),
			esc_html__( 'Next Slide', 'bayleaf' )
		);

		if ( 'slider2' === $instance['styles'] ) {
			echo $navigation; // WPCS xss ok. Contains HTML, other values escaped.
		} else {
			printf( '<div class="dp-slide-navigate">%s</div>', $navigation ); // WPCS xss ok. Contains HTML, other values escaped.
		}
	}

	/**
	 * Display post categories.
	 *
	 * @since 1.0.0
	 */
	public function category() {
		echo '<div class="dp-categories">';
		the_category( ', ' );
		echo '</div>';
	}

	/**
	 * Display post meta.
	 *
	 * @since 1.0.0
	 */
	public function meta() {
		echo '<div class="dp-meta">';
		$this->author();
		esc_html_e( 'on', 'bayleaf' );
		$this->date();
		echo '</div>';
	}

	/**
	 * Register the custom Widget.
	 *
	 * @since 1.0.0
	 */
	public function register_custom_widget() {
		require_once get_template_directory() . '/add-on/display-posts/class-display-posts-widget.php';
		register_widget( 'bayleaf\Display_Posts_Widget' );
	}
}

Display_Posts::init();
