<?php // phpcs:ignore: WordPress.Files.FileName.InvalidClassFileName
/**
 *
 * PHP version 7.2
 *
 * @category Append_To_Head
 * @package  Biz_Sites_Etc_Append_To_Head
 * @author   Phil Kane <pkane-pluginDev@spindry.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @version  1.0.0.1
 * @link     https://business-websites-etc.com *
 */

namespace BWE\AppendToHead; // phpcs:ignore: WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedNamespaceFound

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

if ( ! class_exists( 'Settings' ) ) {

	/**
	 * Setting class
	 */
	class Settings {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			// register actions.
			if ( is_admin() ) {
				add_action( 'admin_init', array( $this, 'admin_init' ) );
				add_action( 'admin_menu', array( &$this, 'add_menu' ) );
			}
		}

		/**
		 * Create settings Page
		 *
		 * @return void
		 */
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'my-textdomain' ) );
			}
			// Render the settings template.
			include sprintf( '%s/templates/appendifier-settings.php', __DIR__ );
		}

		/**
		 * Hook into WP's adminInit action hook
		 * Fields - calendar name, width, font, colors?
		 */
		public function admin_init() {
			register_setting( 'bwe_appendifier_settings-group', 'bwe_top_meta_data' );
			register_setting( 'bwe_appendifier_settings-group', 'bwe_bottom_meta_data' );
			register_setting( 'bwe_appendifier_settings-group', 'bwe_gtm_id' );

			// add your settings section.
			add_settings_section(
				'bwetc_appendifier_top_input_section',
				'',
				array( &$this, 'bwetc_appendifier_top_input_section' ),
				'bwe_appendifier_settings'
			);

			// add your settings section.
			add_settings_section(
				'bwetc_appendifier_bottom_input_section',
				'',
				array( &$this, 'bwetc_appendifier_bottom_input_section' ),
				'bwe_appendifier_settings'
			);

			// add your setting's fields.
			add_settings_field(
				'bwe_appendifier_settings-bwe_gtm_id',
				'Google Tag Manager Id ( e.g., GTM-XXXXXX )',
				array( &$this, 'settings_field_input_text' ),
				'bwe_appendifier_settings',
				'bwetc_appendifier_top_input_section',
				array(
					'field'   => 'bwe_gtm_id',
					'default' => '',
					'type'    => 'text',
				)
			);

			// add your setting's fields.
			add_settings_field(
				'bwe_appendifier_settings-bwe_top_meta_data',
				'Markup ( appends to the top of the HEAD element )',
				array( &$this, 'settings_field_input_text' ),
				'bwe_appendifier_settings',
				'bwetc_appendifier_top_input_section',
				array(
					'field'   => 'bwe_top_meta_data',
					'default' => '',
					'type'    => 'textarea',
				)
			);

			add_settings_field(
				'bwe_appendifier_settings-bwe_bottom_meta_data',
				'Markup ( appends to the bottom of the HEAD element )',
				array( &$this, 'settings_field_input_text' ),
				'bwe_appendifier_settings',
				'bwetc_appendifier_bottom_input_section',
				array(
					'field'   => 'bwe_bottom_meta_data',
					'default' => '',
					'type'    => 'textarea',
				)
			);
		}

		/**
		 * Add_menu.
		 *
		 * @return void
		 */
		public function add_menu() {
			// Add a page to manage this plugin's settings.
			add_options_page(
				'Appendifier Settings',
				__( 'Appendifier', 'my-textdomain' ),
				'manage_options',
				'appendifier-settings',
				array( &$this, 'plugin_settings_page' )
			);
		}

		/**
		 * This function provides text inputs for settings fields
		 *
		 * @param mixed $args creating array for input fields.
		 *
		 * @return mixed input fields
		 */
		public function settings_field_input_text( $args ) {
			//pr( $args, 141);
			// Get the field name from the $args array.
			$field   = $args['field'];
			$default = $args['default'];
			$type    = $args['type'];
			// Get the value of this setting.
			$value = get_option( $field );

			if ( 'textarea' === $type ) {
				// echo a proper input type="textarea".
				echo sprintf( '<textarea name="%s" id="%s"  rows="10" cols="50">%s</textarea>', $field, $field, esc_html( $value ) );
			} else {
				echo sprintf( '<input type="%s" name="%s" id="%s" value=%s>', $type, $field, $field, esc_html( $value ) );
			}
		}

		/**
		 *
		 * Adds top section description
		 *
		 * @return mixed html.
		 */
		public function bwetc_appendifier_top_input_section() {
			// Think of this as help text for the section.
			echo '<h3>' . esc_html_e( 'Google Tag Manager ', 'my-textdomain' ) . '</h3>';
			echo '<p>' . esc_html_e( 'If you are using the Google Tag Manager, get the Container ID from your account and add it to the input field below. This will add the code snippet in the upper portion of the head tag and the code snippet after the opening body tag.', 'my-textdomain' ) . '</p>';
		}

		/**
		 * Adds bottom section description
		 *
		 * @return mixed html
		 */
		public function bwetc_appendifier_bottom_input_section() {
			// Think of this as help text for the section.
			echo '<h3>' . esc_html_e( 'Structured Data: ', 'my-textdomain' ) . '</h3>';
			echo '<p>' . esc_html_e( 'Structured data is a standardized format for providing information about a page and classifying the page content.', 'my-textdomain' ) . '</p>';
			echo '<p>' . esc_html_e( 'Google recommends using the JSON-LD script tag in the HEAD element of a page.', 'my-textdomain' ) . '</p>';
			echo '<p>' . esc_html_e( 'Go to Google\'s Structured Data Markup Helper tool at <a href="https://www.google.com/webmasters/markup-helper/u/0/" >https://www.google.com/webmasters/markup-helper/u/0/</a> and tag the data on your page then copy html code.', 'my-textdomain' ) . '</p>';
			echo '<p>' . esc_html_e( 'After getting the JSON-LD structured data from Google\'s helper tool, add html snippet to the Mark Up TEXTAREA element below which will append the code to the bottom of the HEAD element.', 'my-textdomain' ) . '</p>';
		}

		/**
		 * Adds information section
		 *
		 * @return mixed
		 */
		public function bwetc_appendifier_description() {
			echo esc_html_e( 'Structured data is a standardized format for providing information about a page and classifying the page content. Google recommends using the JSON-LD script tag in the <head> of a page. After getting the JSON-LD structured data from Google\'s helper tool, add html snippet to the Data Structure Mark Up block which will put the code in the <head> tag.', 'my-textdomain' );
			echo esc_html_e( 'Go to Google\'s Structured Data Markup Helper tool at https://www.google.com/webmasters/markup-helper/u/0/ and tag the data on your page then copy html and paste below.', 'my-textdomain' );
		}
	}
}
