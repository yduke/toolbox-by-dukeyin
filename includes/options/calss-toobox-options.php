<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */

if ( !class_exists( 'tbx__Settings' ) ):
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
                'title' => __( 'General', 'toolbox-by-dukeyin' )
            ),
			array(
                'id'    => 'tbx_comments',
                'title' => __( 'Comments', 'toolbox-by-dukeyin' )
            ),
            array(
                'id'    => 'tbx_optimize',
                'title' => __( 'Optimization', 'toolbox-by-dukeyin' )
            ),
            array(
                'id'    => 'tbx_security',
                'title' => __( 'Security', 'toolbox-by-dukeyin' )
            ),
			array(
                'id'    => 'tbx_customize',
                'title' => __( 'Customization', 'toolbox-by-dukeyin' )
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
				
				array(
                    'name'  => 'tbx_gzip',
                    'label' => __( 'Force Gzip', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Force webiste transfer in gzip mode.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				
				array(
                    'name'  => 'tbx_autolink',
                    'label' => __( 'Auto Link', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Make web addresses, emails a clickble like in content.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
            ),
			
			
		/** tbx_comments */
	'tbx_comments' => array(
                array(
                    'name'  => 'tbx_unlink_cmt',
                    'label' => __( 'Link in Comments unclickable', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Check this to make links in comments unclickable.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				
				array(
                    'name'  => 'tbx_disable_cmt',
                    'label' => __( 'Disable URL field', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Disable URL field in Comment from.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				
				array(
                    'name'  => 'tbx_cmter_new_window',
                    'label' => __( 'Coment link in new window', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Display website on new window when readers click Commenter\'s name', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'tbx_some_chinese',
                    'label' => __( 'Some Chinese Please', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Comments must have some chinese.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'tbx_add_at',
                    'label' => __( 'Auto add @ in Reply', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Auto add a @author to reply.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
            ),
			
		
/** tbx_optimize */
            'tbx_optimize' => array(
				array(
                    'name'  => 'tbx_emoji_cdn',
                    'label' => __( 'Emoji CDN', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Use free CDN to load emoji svg and png.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'    => 'tbx_gravatar_cdn',
                    'label'   => __( 'Gravatar CDN', 'toolbox-by-dukeyin' ),
                    'desc'    => __( 'Replace current gravatar with Qiniu CDN', 'toolbox-by-dukeyin' ),
                    'type'    => 'checkbox',
                ),

            ),
/** tbx_security */
            'tbx_security' => array(
				array(
                    'name'  => 'tbx_only_my_media',
                    'label' => __( 'show current user uploads only', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'show current user uploads only.', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
            ),
/** tbx_customize */
            'tbx_customize' => array(
				array(
                    'name'  => 'tbx_remove_image_wh',
                    'label' => __( 'Remove image Width and Height', 'toolbox-by-dukeyin' ),
                    'desc'  => __( 'Remove Width and Height of all images for responsive', 'toolbox-by-dukeyin' ),
                    'type'  => 'checkbox'
                ),
            ),
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
