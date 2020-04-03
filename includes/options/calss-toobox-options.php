<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('tbx__Settings' ) ):
class tbx__Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
		$tbx_title = __( 'Toolbox', 'toolbox-by-dukeyin' );
        add_options_page( $tbx_title, $tbx_title, 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
			array(
                'id'    => 'tbx_general',
                'title' => __( 'General Settings', 'toolbox-by-dukeyin' )
            ),
            array(
                'id'    => 'tbx_basics',
                'title' => __( 'Basic Settings', 'toolbox-by-dukeyin' )
            ),
            array(
                'id'    => 'tbx_advanced',
                'title' => __( 'Advanced Settings', 'toolbox-by-dukeyin' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
		/** General */
		    'tbx_general' => array(
                array(
                    'name'  => 'tbx_all_settings',
                    'label' => __( 'Show All Settings', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Enable Hidden Administration Feature displaying All Site Settings.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
            ),
		/** Basic */
            'tbx_basics' => array(
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'toolbox-by-dukeyin' ),
                    'desc'              => __( 'Text input description', 'toolbox-by-dukeyin' ),
                    'placeholder'       => __( 'Text Input placeholder', 'toolbox-by-dukeyin' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'toolbox-by-dukeyin' ),
                    'desc'              => __( 'Number field with validation callback `floatval`', 'toolbox-by-dukeyin' ),
                    'placeholder'       => __( '1.99', 'toolbox-by-dukeyin' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'        => 'textarea',
                    'label'       => __( 'Textarea Input', 'toolbox-by-dukeyin' ),
                    'desc'        => __( 'Textarea description', 'toolbox-by-dukeyin' ),
                    'placeholder' => __( 'Textarea placeholder', 'toolbox-by-dukeyin' ),
                    'type'        => 'textarea'
                ),
                array(
                    'name'        => 'html',
                    'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'toolbox-by-dukeyin' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Checkbox Label', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'A radio button', 'toolbox-by-dukeyin' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Dropdown description', 'toolbox-by-dukeyin' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Password description', 'toolbox-by-dukeyin' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'File description', 'toolbox-by-dukeyin' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
            ),
	/** Advanced */
            'tbx_advanced' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Color description', 'toolbox-by-dukeyin' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Password description', 'toolbox-by-dukeyin' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'WP_Editor description', 'toolbox-by-dukeyin' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Multi checkbox description', 'toolbox-by-dukeyin' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
