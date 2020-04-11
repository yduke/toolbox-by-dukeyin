<?php
/**
 * @package Toolbox by DukeYin
 * @since 1.0.0
 * @package  Toolbox by DukeYin
 *
 * @wordpress-plugin
 * Plugin Name: Toolbox by DukeYin
 * Plugin URI: http://www.dukeyin.com/about/
 * Description: This will need visitors to type Chinese to submit comments, against spamers.
 * Author: DukeYin
 * Version: 1.0.0
 * Author:            Duke Yin
 * Author URI:        https://www.dukeyin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       toolbox-by-dukeyin
 * Domain Path:       /languages
*/
if (!defined('WPINC')) {
    die;
}
define('TOOLBOX_BY_DUKEYIN_VERSION', '1.0.0');
define('TOOLBOX_BY_DUKEYIN_PATH', plugin_dir_url(__FILE__));

require_once plugin_dir_path( __FILE__ ) . 'includes/options/class.settings-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/options/calss-toobox-options.php';

require plugin_dir_path( __FILE__ ) . 'includes/class-functions.php';

new tbx__Settings();


//setting link
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'tbx_plugin_action_links');
 
function tbx_plugin_action_links($links)
{
        $links[] = '<a href="' . get_admin_url(null, 'options-general.php?page=toolbox-by-dukeyin') . '">' . __('Settings','toolbox-by-dukeyin') . '</a>';
        return $links;
}