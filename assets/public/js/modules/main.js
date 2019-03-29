import lib from './library';
import ScrollToTop from './scrolltop';
import Menu from './menu';
import Styling from './styling';
import ResVid from './resvid';
import Hwidget from './hwidget';
import Comments from './comments';

class Main {

	/**
	 * The constructor function.
	 *
	 * @since 1.3.5
	 *
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		// Update global data on window scroll.
		lib.updateOnScroll();

		// Update global data on window resize.
		lib.updateOnResize();

		// Add slider functionality.
		lib.addSlider('.slider-wrapper', '.dp-entry', '', {next: '.dp-next-slide', prev: '.dp-prev-slide'});

		// Add dropdown menu functionality.
		this.navigationMenu();

		// Add responsive videos functionality.
		this.responsiveVideos();

		// Add scroll back to top functionality.
		this.scrollBackTop();

		// Add comments toggle functionality.
		this.commentsToggle();

		// Add responsive styling functionality.
		this.responsiveStyling();

		// Add header widget toggle functionality.
		this.headerWidgetToggle();

		// Add reveal on scroll functionality to brick elements.
		lib.addRosObject('.brick', 'fadeInUp');

		// Add reveal on scroll functionality to brick elements.
		lib.addRosObject('.dp-grid > .dp-entry', '', 0, 150);
	}

	/**
	 * Navigation menu functionality.
	 * 
	 * @since 1.3.5
	 */
	navigationMenu() {

		new Menu();
	}

	/**
	 * Responsive videos functionality.
	 * 
	 * @since 1.3.5
	 */
	responsiveVideos() {

		new ResVid();
	}

	/**
	 * Header widget toggle functionality.
	 * 
	 * @since 1.3.5
	 */
	headerWidgetToggle() {

		new Hwidget();
	}

	/**
	 * Scroll back to top functionality.
	 * 
	 * @since 1.3.5
	 */
	scrollBackTop() {

		new ScrollToTop();
	}

	/**
	 * Comments toggle functionality.
	 * 
	 * @since 1.3.5
	 */
	commentsToggle() {

		new Comments();
	}

	/**
	 * Responsive styling functionality.
	 * 
	 * @since 1.3.5
	 */
	responsiveStyling() {

		new Styling();
	}
}

export default Main;
