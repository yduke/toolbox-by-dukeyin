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



new tbx__Settings();



//Spam portaction
function refused_spam_comments( $comment_data ) {  
$pattern = '/[一-龥]/u';  
if(!preg_match($pattern,$comment_data['comment_content'])) {  
wp_die( __('Need chinese charactors to submit your comment.','toolbox-by-dukeyin') );  
}  
return( $comment_data );  
}  
add_filter('preprocess_comment','refused_spam_comments');

// Add at in comments
function duke_comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
  }

  return $comment_text;
}
add_filter( 'comment_text' , 'duke_comment_add_at', 20, 2);
