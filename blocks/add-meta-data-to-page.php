<?php
/**
 * Block for adding google data structure to <head> element.
 * Can add <style> meta_ and google analytics to <head> as well
 *
 * @author Phil Kane
 * @package Biz_Sites_Etc_Add_To_Head_Of_Page
 */

namespace BWE\AppendToHead\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
/**
 * Block class
 */
class Data_Structure_Meta {

	 /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Initialize Settings
			add_action('enqueue_block_assets', [ $this, 'enqueue_front_end_assets' ]);
			add_action('init', [ $this, 'register_meta_block' ]);
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
		}

	/**
	 * Registers all block assets so that they can be enqueued through Gutenberg in
	 * the corresponding context.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
	 */
	// register custom meta tag field
	function register_meta_block()
	{
		register_meta(
			'post',
			'bwetc_data_structure_markup',
				array(
				'show_in_rest'   => true,
				'single'         => true,
				'type'           => 'string',
				),
			);
	}

	function enqueue_editor_assets()
	{
		$data_meta_editor_css = 'add_meta_data_to_page/editor.css';
		wp_enqueue_style(
			'data-meta-editor',
			plugins_url( $data_meta_editor_css, __FILE__ ),
			array()
		);

		$data_meta_index_js = 'add_meta_data_to_page/index.js';
		wp_enqueue_script(
			'bwetc_data-meta-index',
			plugins_url( $data_meta_index_js, __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' )
		);
	}

	/**
	 *  Enqueues the style.css for the frontend
	 */
	function enqueue_front_end_assets()
	{
		$data_meta_style_css = 'add_meta_data_to_page/style.css';
		wp_enqueue_style(
			'bwetc_data-meta-style',
			plugins_url( $data_meta_style_css, __FILE__ ),
			array()
		);

		$data_meta_json_index_js = 'add_meta_data_to_page/json-index.js';
		wp_enqueue_script(
			'bwetc_data-structure-markup-index',
			plugins_url( $data_meta_json_index_js, __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ),
			'false',
			'true' // put in footer
		);
		global $post;

		if ( $post ) {
			$post_id = $post->ID;
			$post_meta = get_post_meta( $post_id );
			if ( isset( $post_meta['bwetc_data_structure_markup'] ) ) {
				$data_structure_markup = esc_html( $post_meta['bwetc_data_structure_markup'][0] );

				global $wp_query;
				wp_localize_script(
					'bwetc_data-structure-markup-index',
					'bwetcDataStructure',
					array(
						'markup' => esc_attr( $data_structure_markup ),
					),
				);
			}
		}
	}

}