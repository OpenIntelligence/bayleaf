import conf from './config';
import lib from './library';

/**
 * Toggle header widget on button click.
 */
class HeaderWidgetToggle {

	/**
	 * The constructor function.
	 *
	 * @since 1.3.5
	 */
	constructor() {

		const elem   = conf.elems;
		this.widget  = lib.get(elem.headWid);
		this.toggler = lib.get(elem.headWidToggle, this.header);

		if (null === this.widget) return;
		this.events();
	}

	/**
	 * JS event handling.
	 * 
	 * @since 1.3.5
	 */
	events() {

		lib.on('click', this.toggler, this.toggleWidget.bind(this));
	}

	/**
	 * Toggle navigation menu.
	 * 
	 * @since 1.3.5
	 */
	toggleWidget() {

		lib.toggleClass(this.toggler, conf.cls.toggler);
		lib.toggleClass(this.widget, conf.cls.toggled);
		lib.hasClass(this.widget, conf.cls.toggled) ? lib.scrollDisable() : lib.scrollEnable();
	}
}

export default HeaderWidgetToggle;