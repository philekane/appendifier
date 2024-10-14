<?php // phpcs:ignore: WordPress.Files.FileName.InvalidClassFileName
/**
 * BWE Appendifier
 *
 * @package   BWE Add To Head
 * @version   1.0.0.1
 * @copyright 2022 Business Websites Et-cetera
 * @license   GPL-2.0-or-later
 */

namespace BWE\AppendToHead; // phpcs:ignore: WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedNamespaceFound

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Undocumented class
 */
class Check_Meta_Data_Site_Wide {

	/**
	 * Construct the plugin object
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'check_for_site_wide_meta_data_addition' ) );
	}

	/**
	 * Adds google analytics script to the head of page.
	 *
	 * @return void
	 */
	public function check_for_site_wide_meta_data_addition() {
		if ( get_option( 'bwe_top_meta_data' ) ) {
			$top_markup = get_option( 'bwe_top_meta_data' );
			$this->set_meta_data( $top_markup, -999 );
		}

		if ( get_option( 'bwe_gtm_id' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_tag_manager_after_body' ) );
			$gtm_id = get_option( 'bwe_gtm_id' );
			// add google tag to head.
			$this->set_gtm( $gtm_id, -1000 );
		}

		if ( get_option( 'bwe_bottom_meta_data' ) ) {
			$bottom_markup = get_option( 'bwe_bottom_meta_data' );
			$this->set_meta_data( $bottom_markup, 10 );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function add_tag_manager_after_body() {
		$gtm_id         = get_option( 'bwe_gtm_id' );
		$bwe_add_gtm_js = 'assets/js/bwe-add-gtm.js';
		wp_enqueue_script(
			'bwe_add_gtm_js',
			plugins_url( $bwe_add_gtm_js, __FILE__ ),
			array(),
			'1.0',
			true
		);
		// get the gtm from the options and then localize it so it can be accessed by js.
		wp_localize_script(
			'bwe_add_gtm_js',
			'BWEGTM',
			array(
				'id' => $gtm_id,
			),
		);
	}

	/**
	 * Sets the google tag manager to the <head>tag.
	 *
	 * @param int $gtm_id   the google tag manager Id.
	 * @param int $priority low priority number puts it at the top of the <head> tag.
	 * @return void
	 */
	public function set_gtm( $gtm_id, $priority ) {
		add_action(
			'wp_head',
			function () use ( $gtm_id ) {
				$this->add_gtm( $gtm_id );
			},
			$priority,
			-1
		);
	}

	/**
	 * Undocumented function
	 *
	 * @param int $gtm_id google tag manager id.
	 * @return void
	 */
	public function add_gtm( $gtm_id ) {
		ob_start();
		?>
			<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','<?php echo esc_attr( $gtm_id ); ?>');</script>
			<!-- End Google Tag Manager -->
		<?php
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Sets Meta Data that gets put in wp head.
	 *
	 * @param mixed $data   meta data from options.
	 * @param int   $priority priority number.
	 * @return void
	 */
	public function set_meta_data( $data, $priority ) {
		add_action(
			'wp_head',
			function () use ( $data ) {
				$this->add_meta_data( $data );
			},
			$priority,
			-1
		);
	}

	/**
	 * Adds Meta Data that gets put in wp head.
	 *
	 * @param mixed $data meta data got from set.
	 * @return void
	 */
	public function add_meta_data( $data ) {
		ob_start();
		echo $data; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
