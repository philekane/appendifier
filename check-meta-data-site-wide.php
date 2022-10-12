<?php
/**
 *
 * @package   BWE Add To Head
 * @version   1.0.0.1
 * @copyright 2022 Business Websites Et-cetera
 * @license   GPL-2.0-or-later
 */

namespace BWE\AppendToHead;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Check_Meta_Data_Site_Wide {

 	/**
     * Construct the plugin object
     */
    public function __construct()
	{
		add_action( 'after_setup_theme',  [ $this, 'check_for_site_wide_meta_data_addition' ]  );
	}

	/**
	 * adds google analytics script to the head of page 
	 *
	 * @return void
	 */
	function check_for_site_wide_meta_data_addition()
	{
		if ( get_option( 'bwe_top_meta_data' ) ) {
			$top_markup = get_option( 'bwe_top_meta_data' );
			$this->set_meta_data( $top_markup, -1000);
		}
		if ( get_option( 'bwe_bottom_meta_data' ) ) {
			$bottom_markup = get_option( 'bwe_bottom_meta_data' );
			$this->set_meta_data( $bottom_markup, 10 );
		}
	}

	function set_meta_data( $data, $priority ) {

		add_action( 'wp_head',
			function () use ( $data ) {
				$this->add_meta_data( $data );
			},
			$priority,
			-1
		);
	}

	function add_meta_data( $data ) {
		ob_start();
		echo $data;
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string;
	}

}