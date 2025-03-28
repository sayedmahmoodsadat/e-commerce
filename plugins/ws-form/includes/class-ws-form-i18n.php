<?php

	/**
	 * Define the internationalization functionality
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 */

	class WS_Form_i18n {

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			if(!WS_Form_Common::option_get('disable_translation', false)) {

				load_plugin_textdomain(
					'ws-form',
					false,
					dirname( plugin_basename( WS_FORM_PLUGIN_ROOT_FILE ) ) . '/languages'
				);
			}
		}
	}
