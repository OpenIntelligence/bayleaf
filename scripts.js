/* global bayleafScreenReaderText */
( function( $ ) {

"use strict";

var isIos          = /iPad|iPhone|iPod/.test( navigator.userAgent ) && ! window.MSStream,
	header         = $( '#masthead' ),
	body           = $( 'body' ),
	navWrapper     = header.find( '#site-navigation' ),
	mainMenu       = header.find( '#' + bayleafScreenReaderText.menu ),
	menuToggle     = header.find( '.menu-toggle' ),
	scrollDisabled = false,
	scrollTop      = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0,
	positionCache;

if ( isIos ) {
	document.documentElement.classList.add( 'ios-device' );
}

( function() {
	onRaf( function() {
		scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
	} );
} )();

function scrollDisable() {
	if ( scrollDisabled ) {
		return;
	}

	positionCache = scrollTop;
	body.addClass( 'no-scroll' ).css({
		top: -1 * scrollTop
	});

	scrollDisabled = true;
}

function scrollEnable() {
	if ( ! scrollDisabled ) {
		return;
	}

	scrollTop = positionCache;
	body.removeClass( 'no-scroll' );
	$( window ).scrollTop( scrollTop );

	scrollDisabled = false;
}

function onRaf( callback ) {
	var raf = window.requestAnimationFrame ||
		window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame ||
		window.msRequestAnimationFrame ||
		window.oRequestAnimationFrame,
		caf = window.cancelAnimationFrame ||
		window.mozCancelAnimationFrame,
		id;

	if ( raf ) {
		loop();
	}

	function loop() {
		var goRaf;
		goRaf = callback();
		if ( true !== goRaf ) {
			id = raf( loop );
		} else if ( caf && undefined !== typeof( id ) ) {
			caf( id );
		}
	}
}

// ObjectFit fallback.
( function() {
	var container, imageSource, i;
	if ( false === 'objectFit' in document.documentElement.style ) {
		container = document.querySelectorAll( '.entry-thumbnail, .gallery-icon a' );
		for ( i = 0; i < container.length; i++ ) {
			imageSource = container[i].querySelector( 'img' ).src;
			container[i].querySelector( 'img' ).style.visibility = 'hidden';
			container[i].style.backgroundSize = 'cover';
			container[i].style.backgroundImage = 'url(' + imageSource + ')';
			container[i].style.backgroundPosition = 'center center';
		}
	}
}() );

/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */

function skipLinkFocusFix() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', focusFix );
	}

	function focusFix() {
		var id = location.hash.substring( 1 ),
			element;
		if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
			return;
		}
		element = document.getElementById( id );
		if ( element ) {
			if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
				element.tabIndex = -1;
			}
			element.focus();
		}
	}
}
skipLinkFocusFix();

/**
 * Toggle Primary menu on smaller screens.
 *
 * @since 1.0.0
 */
function toggleMenu() {

	menuToggle.click(
		function() {
			$( this ).toggleClass( 'toggled-btn' );
			$( this ).attr( 'aria-expanded', $( this ).hasClass( 'toggled-btn' ) );
			if ( menuToggle.hasClass( 'toggled-btn' ) ) {
				navWrapper.slideDown( 'slow' );
			} else {
				navWrapper.slideUp( 'slow' );
			}
		}
	);
}
toggleMenu();

/**
 * Toggle sub-menus of primary navigation.
 *
 * @since 1.0.0
 */
function toggleSubMenu() {
	var menuItems = mainMenu.find( '.menu-item-has-children, .page_item_has_children' );

	menuItems.hover(
		function() {
			$( this ).addClass( 'toggled-on' );
		},
		function() {
			$( this ).removeClass( 'toggled-on' );
		}
	);

	menuItems.find( 'a' ).on( 'focus.bayleaf blur.bayleaf', function() {
		$( this )
			.parents( '.menu-item, .page_item' )
			.toggleClass( 'toggled-on' );
	} );
}
toggleSubMenu();

/**
 * Drop down navigation menu on larger touch screens.
 *
 * @since 1.0.0
 */
function toggleMenuTouchScreen() {
	var menuItems  = mainMenu.find( '.menu-item-has-children, .page_item_has_children' ),
		parentLink = menuItems.children( 'a' );

	if ( menuToggle.is( ':hidden' ) ) {
		body.on( 'touchstart.bayleaf', function( e ) {
			if ( ! $( e.target ).closest( '#primary-menu' ) ) {
				menuItems.removeClass( 'toggled-on' );
			}
		} );
		parentLink.on( 'touchstart.bayleaf click.bayleaf', function( e ) {
			var el = $( this ).parent( 'li' );
			if ( ! el.hasClass( 'toggled-on' ) ) {
				e.preventDefault();
				el.addClass( 'toggled-on' );
				el.siblings( '.toggled-on' ).removeClass( 'toggled-on' );
			}
		} );
	} else {
		parentLink.unbind( 'touchstart.bayleaf' );
	}
}

/**
 * Toggle navigation menu on touchscreen.
 *
 * @since 1.0.0
 */
if ( 'ontouchstart' in window || navigator.maxTouchPoints ) {
	$( window ).on( 'resize.bayleaf', toggleMenuTouchScreen );
	toggleMenuTouchScreen();
}

/**
 * Make embedded videos responsive.
 *
 * Identify video iframes in main content and wrap them in a div containing 'video-container'
 * class. Now make it responsive using css.
 */

(function() {
	var wrapper, elem,
		main = document.getElementById( 'main' ),
		bin = main ? main.getElementsByClassName( 'entry-content' )[0] : null,
		selectors = [
			'iframe[src*="youtube.com"]',
			'iframe[src*="vimeo.com"]',
			'iframe[src*="dailymotion.com"]',
			'iframe[src*="videopress.com"]'
		],
		frames = bin ? bin.querySelectorAll( selectors.join( ',' ) ) : [],
		length = frames.length,
		i = 0;

	for ( ; i < length; i++ ) {
		elem = frames[i];
		wrapper = document.createElement( 'div' );
		wrapper.className = 'video-container';
		elem.parentNode.insertBefore( wrapper, elem );
		wrapper.appendChild( elem );
	}
}() );

/**
 * Toggle header widget area on click of a button.
 *
 * @since 1.0.0
 */
function headerWidgetToggle() {
	var actionButton = header.find( '.action-toggle' ),
		widgetArea   = header.find( '#header-widget-area' );
	actionButton.click(
		function() {
			if ( scrollDisabled ) {
				scrollEnable();
			} else {
				scrollDisable();
			}
			widgetArea.toggleClass( 'makeitvisible' );
			$( this ).toggleClass( 'toggled-btn' );

			if ( widgetArea.hasClass( 'makeitvisible' ) ) {
				widgetArea.fadeIn( 'slow' );
				$( this ).attr( 'aria-expanded', true );
			} else {
				widgetArea.fadeOut( 'slow' );
				$( this ).attr( 'aria-expanded', false );
			}
		}
	);
}
headerWidgetToggle();

/**
 * Toggle a class on scroll to display scroll button.
 *
 * @since 1.0.0
 */

function scrollToTop() {
	var scrlTotop;
	scrlTotop = document.getElementsByClassName( 'scrl-to-top' );
	if ( ! scrlTotop.length ) {
		return;
	}
	scrlTotop = scrlTotop[0];

	onRaf( function() {
		if ( 300 < scrollTop ) {
			scrlTotop.classList.add( 'makeitvisible' );
		} else {
			scrlTotop.classList.remove( 'makeitvisible' );
		}
	} );

	scrlTotop.addEventListener( 'click', function() {
		$( 'html, body' ).animate({ scrollTop: 0 }, 'slow' );
	} );
}
scrollToTop();

/**
 * Toggle a class on scroll to display scroll button.
 *
 * @since 1.0.0
 */

function fadeInScroll() {

	var object = $( '.brick' ),
		windowHeight = $( window ).height();

	// Loop through each object in the array.
	$.each( object, function() {
		var entries, brick = $( this ), offset = $( this ).offset().top;

		if ( brick.hasClass( 'posts-grid' ) ) {
			entries = brick.find( '.dp-grid > .dp-entry' );
		} else if ( brick.hasClass( 'display_feeds' ) ) {
			entries = brick.find( '.podcast-details, .podcast-feeds' );
		}

		if ( undefined !== entries ) {
			$.each( entries, function( index ) {
				$( this ).css( { 'transition-delay': index * 150 + 'ms' } );
			} );
		}

		onRaf( function() {
			var top = offset - scrollTop,
				percent = Math.floor( top / windowHeight * 100 );

			if ( percent < 80 ) {
				brick.addClass( 'fadeInUp' );
				return true;
			}
		} );
	});
}
fadeInScroll();

/**
 * Toggle post comment area.
 *
 * @since 1.0.0
 */

function toggleComments() {
	var wrapper, comments, toggle;
	wrapper  = $( '#comments' );
	comments = wrapper.find( '.comments-area' );
	toggle   = wrapper.find( '.comments-toggle' );

	toggle.click( function() {
		comments.slideToggle( 'slow' );
		$( this ).toggleClass( 'toggled' );
	} );
}
toggleComments();

/**
 * Adjust widget featured image style.
 *
 * @since 1.0.0
 */

function featuredBgWidth() {
	var widget = $( '.has-featured-img, .slider2, .df-podcast' );

	// Loop through each widget in the array.
	$.each( widget, function() {

		if ( 800 > $( this ).width() ) {
			$( this ).removeClass( 'widescreen' );
		} else {
			$( this ).addClass( 'widescreen' );
		}
	});
}
featuredBgWidth();

/**
 * Toggle a class on scroll to display scroll button.
 *
 * @since 1.0.0
 */

function postsSlider() {
	var containers = $( '.slider-wrapper' );

	containers.each( function() {
		var container  = $( this ),
			slides     = container.find( '.dp-entry' ),
			nextBtn    = container.find( '.dp-next-slide' ),
			prevBtn    = container.find( '.dp-prev-slide' ),
			current    = slides.first(),
			prev;

		current.addClass( 'makeitvisible firstslide' );

		nextBtn.click( function() {
			slideTimer( 'next' );
		} );

		prevBtn.click( function() {
			slideTimer( 'prev' );
		} );

		function slideTimer( direction ) {
			var height;
			direction = direction || 'next';
			prev      = current;

			if ( 'next' === direction ) {
				current = current.next( '.dp-entry' );
				current = ( 0 === current.length ) ? slides.first() : current;
			}

			if ( 'prev' === direction ) {
				current = current.prev( '.dp-entry' );
				current = ( 0 === current.length ) ? slides.last() : current;
			}

			current.addClass( 'makeitvisible' );
			prev.removeClass( 'makeitvisible' );
			if ( container.hasClass( 'slider2' ) ) {
				height = current.height();
				container.animate({
					height: height
				}, 1000 );
			}
		}
	} );
}
postsSlider();

} )( jQuery );
