<?php
/**
 * Calendar settings for bwetc event posts
 */

namespace BWE\AppendToHead;

if (!defined('ABSPATH')) {
	die('No direct access allowed');
}

if (!class_exists('Settings')) {

    class Settings
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
         
            // allow editor to edit settings
           // $role = get_role('editor');
           //$role->add_cap('manage_options');

            // register actions
			if ( is_admin() ) {
				add_action( 'admin_init', array( $this, 'admin_init' ) );
                add_action( 'admin_menu', array( &$this, 'add_menu' ) );
            }
		
        } // END public function __construct

          /**
         * Create settings Page
         *
         * @return error message
         */
        public function plugin_settings_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'bizsitesetc_calendar'));
            }
            //    echo 'setting page 2';
            // Render the settings template
          	include sprintf( '%s/templates/appendifier-settings.php', dirname( __FILE__ ) );
	

            // Render the settings template
            // include sprintf("%s/templates/admin_settings.php", dirname(__FILE__));
        } // END public function plugin_settings_page()

		 /**
             * Add css styles for calendar
             *
             * @return void
             */
            function add_styles()
            {
                wp_enqueue_style('bwetc-calendar-style.min', plugin_dir_url(__FILE__) . 'css/style.min.css', array(), '20180915', 'all');
            }
            /**
             * Add js for calendar
             *
             * @return void
             */
            function add_js()
            {
                wp_enqueue_script('calendar', plugin_dir_url(__FILE__) . 'js/calendar.js');
            }

			 /**
             * Add the  styles to the page
             *
             * @return void
             */
            function add_admin_styles()
            {
                wp_enqueue_style('admin-styles', plugin_dir_url(__FILE__) . 'css/admin.style.css', array(), '20180915', 'all');
            }
        /**
         * Hook into WP's adminInit action hook
         * Fields - calendar name, width, font, colors?
         */
        public function admin_init()
        {
          
          //  add_action('admin_enqueue_scripts', array( &$this, 'add_admin_styles' ) );
            
            register_setting('bwe_appendifier_settings-group', 'bwe_top_meta_data');
            register_setting('bwe_appendifier_settings-group', 'bwe_bottom_meta_data');
            

            // add your settings section
           add_settings_section(
            'bwetc_Appendifier_top_input_Section',
            '',
            array(&$this, 'bwetc_Appendifier_top_input_Section'),
            'bwe_appendifier_settings'
            );


           // add your settings section
           add_settings_section(
            'bwetc_Appendifier_bottom_input_Section',
            '',
            array(&$this, 'bwetc_Appendifier_bottom_input_Section'),
            'bwe_appendifier_settings'
            );



            // add your setting's fields
            add_settings_field(
                'bwe_appendifier_settings-bwe_top_meta_data',
                'Markup ( appends to the top of the HEAD element )',
                array(&$this, 'settingsFieldInputText'),
                'bwe_appendifier_settings',
                'bwetc_Appendifier_top_input_Section',
                array(
                    'field' => 'bwe_top_meta_data',
                    'default' => '',
                    'type' => 'textarea',
                )
            );
            add_settings_field(
                'bwe_appendifier_settings-bwe_bottom_meta_data',
                'Markup ( appends to the bottom of the HEAD element )',
                array(&$this, 'settingsFieldInputText'),
                'bwe_appendifier_settings',
                'bwetc_Appendifier_bottom_input_Section',
                array(
                    'field' => 'bwe_bottom_meta_data',
                    'default' => '',
                    'type' => 'textarea',
                )
            );


            // Possibly do additional adminInit tasks
        } // END public static function activate

         /**
		 * Add_menu
		 *
		 * @return void
		 */
		public function add_menu() {

			// Add a page to manage this plugin's settings.
			add_options_page(
				'Appendifier Settings',
				 __( 'Appendifier', 'bwe-appendifier' ),
				'manage_options',
				'appendifier-settings',
				array( &$this, 'plugin_settings_page' )
			);
		}

        /**
         * This function provides text inputs for settings fields
         * 
         * @param mixed $args creating array for input fields
         * 
         * @return mixed input fields
         */
        public function settingsFieldInputText($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            $default = $args['default'];
            $type = $args['type'];
            // Get the value of this setting
            $value = get_option($field);

            // echo a proper input type="text"
            echo sprintf('<textarea name="%s" id="%s"  rows="10" cols="50">%s</textarea>',  $field, $field, esc_html( $value ) );
           

        } // END public function settings_field_input_text($args)

         /**
         * Adds top section description
         *
         * @return form
         */
        public function bwetc_Appendifier_top_input_Section()
        {
           // Think of this as help text for the section.
           //echo '<h3>'. __('Meta Data: ') . '</h3>';
          // echo '<p>' . __( 'Append META data' ) . '</p>';
          echo '<h3>'. __('Google Analytics: ') . '</h3>';
          echo '<p>' . __( 'Add google analytics tracking code as the first item into the HEAD element.') . '</p>';
     

        }

        /**
         * Adds bottom section description
         *
         * @return form
         */
        public function bwetc_Appendifier_bottom_input_Section()
        {
           // Think of this as help text for the section.
           echo '<h3>'. __('Structured Data: ') . '</h3>';
           echo '<p>' . __('Structured data is a standardized format for providing information about a page and classifying the page content.') . '</p>';
           echo '<p>' . __( 'Google recommends using the JSON-LD script tag in the HEAD element of a page.' ) . '</p>';
           echo '<p>' . __( 'Go to Google\'s Structured Data Markup Helper tool at <a href="https://www.google.com/webmasters/markup-helper/u/0/" >https://www.google.com/webmasters/markup-helper/u/0/</a> and tag the data on your page then copy html code.' ) . '</p>';
           echo '<p>' . __( 'After getting the JSON-LD structured data from Google\'s helper tool, add html snippet to the Mark Up TEXTAREA element below which will append the code to the bottom of the HEAD element.') . '</p>';
            
        }

         /**
         * Adds calendar events section
         *
         * @return form
         */
        public function bwetc_Appendifier_description()
        {
            echo __('Structured data is a standardized format for providing information about a page and classifying the page content. Google recommends using the JSON-LD script tag in the <head> of a page. After getting the JSON-LD structured data from Google\'s helper tool, add html snippet to the Data Structure Mark Up block which will put the code in the <head> tag.');
            echo __( 'Go to Google\'s Structured Data Markup Helper tool at https://www.google.com/webmasters/markup-helper/u/0/ and tag the data on your page then copy html and paste below.' );
        }

    } // END class Plant_Post_Settings
} // END if(!class_exists('Plant_Post_Settings'))
