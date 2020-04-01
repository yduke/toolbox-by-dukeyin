<?php
/**
 * @package Toolbox by DukeYin
 * @version 1.0.0
 */
/*
Plugin Name: Toolbox by DukeYin
Plugin URI: http://www.dukeyin.com/about/
Description: This will need visitors to type Chinese to submit comments, against spamers.
Author: DukeYin
Version: 1.0.0
Author URI: http://www.dukeyin.com/
*/

function refused_spam_comments( $comment_data ) {  
$pattern = '/[一-龥]/u';  
if(!preg_match($pattern,$comment_data['comment_content'])) {  
wp_die( __('Need chinese charactors to submit your comment.','chinese-comment-only') );  
}  
return( $comment_data );  
}  
add_filter('preprocess_comment','refused_spam_comments');

function duke_comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
  }

  return $comment_text;
}
add_filter( 'comment_text' , 'duke_comment_add_at', 20, 2);
