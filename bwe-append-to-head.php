<?php //phpcs:ignore: WordPress.Files.FileName.InvalidClassFileName
/**
 * BWE Appendifier
 *
 * @package           bwe-appendifier
 * @author            Phil Kane
 * @copyright         2024 Business Websites Et-cetera
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:  Biz Sites, Etc. Appendifier
 * Plugin URI:   https://plugins.business-websites-etc.com
 * Description:  Appendifier - appends meta data to <head> element of web page
 * Version:      1.1.0
 * Author:       Phil Kane, Business Websites, Et cetera
 * Author URI:   https://business-websites-etc.com/about
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  biz-sites-etc-append_to_head
 * Domain Path:  /languages
 */

/*
The biz_sites_etc_append_to_head is free software: you can redistribute
it and/or modifyit under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 2 of the License,
or any later version.

biz_sites_etc_append_to_head is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with biz_sites_etc_append_to_head. If not, see {URI to Plugin License}.
*/

namespace BWE\AppendToHead; //phpcs:ignore: WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedNamespaceFound

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

if ( ! class_exists( 'BWE\AppendToHead\Append_To_Head' ) ) {

	/**
	 * Add meta data to <head> element of a page or for every page of site
	 *
	 * @category Add_Meta_Data_To_Head
	 * @package  Biz_Sites_Etc_Add_To_Head_Of_Page
	 * @author   Phil Kane <pkane-pluginDev@spindry.com>
	 * @license  MIT https://opensource.org/licenses/MIT
	 * @link     https://business-websites-etc.com
	 */
	class Append_To_Head {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {

			// Initialize Settings.
			include_once sprintf( '%s/check-meta-data-site-wide.php', __DIR__ );
			$check_meta_data_site_wide = new Check_Meta_Data_Site_Wide();

			include_once sprintf( '%s/admin/settings.php', __DIR__ );
			$settings = new Settings();

			include_once sprintf( '%s/blocks/add-meta-data-to-page.php', __DIR__ );
			$register_meta_data_structure_blocks = new Blocks\Data_Structure_Meta();

			if ( is_admin() ) {
				$plugin = plugin_basename( __FILE__ );
				add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ) );
			}
		} // END public function __construct

		/**
		 * Add the settings link to the plugins page.
		 *
		 * @param mixed $links get links and add appendifier link.
		 * @return $links
		 */
		public function plugin_settings_link( $links ) {
			$settings_link = '<a href="options-general.php?page=appendifier-settings">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		/**
		 * Activate the plugin
		 *
		 * @return void
		 */
		public static function activate() {
			// Do nothing.
		}

		/**
		 * Deactivate the plugin
		 *
		 * @return void
		 */
		public static function deactivate() {
			// Do nothing.
		}
	}
}

if ( class_exists( 'BWE\AppendToHead\Append_To_Head' ) ) {

	// Installation and uninstallation hooks.
	register_activation_hook( __FILE__, array( 'BWE\AppendToHead\Append_To_Head', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'BWE\AppendToHead\Append_To_Head', 'deactivate' ) );
	// instantiate the plugin class.
	$append_to_head = new Append_To_Head(); // phpcs:ignore: WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

}
