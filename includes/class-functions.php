<?php

// All settings
$tbx_all_settings = isset(get_option( 'tbx_general')['tbx_all_settings']) ? get_option( 'tbx_general')['tbx_all_settings'] : '';
if( $tbx_all_settings == 'on' ){
	function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
   }
   add_action('admin_menu', 'all_settings_link');
}

//Gzip
$tbx_gzip = isset(get_option( 'tbx_general')['tbx_gzip']) ? get_option( 'tbx_general')['tbx_gzip'] : '';
if( $tbx_gzip == 'on' ){
	function tbx_gzip_compression(){
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
			ob_start('ob_gzhandler'); 
		}else{
			ob_start(); 
		}
	}
	add_action('init', 'tbx_gzip_compression');
}

//Autolink
$tbx_autolink = isset(get_option( 'tbx_general')['tbx_autolink']) ? get_option( 'tbx_general')['tbx_autolink'] : '';
if( $tbx_autolink == 'on' ){
	add_filter('the_content', 'make_clickable');
}

//Comment link unclickable
$tbx_unlink_cmt = isset(get_option( 'tbx_comments')['tbx_unlink_cmt']) ? get_option( 'tbx_comments')['tbx_unlink_cmt'] : '';
if( $tbx_unlink_cmt == 'on' ){
	remove_filter( 'comment_text', 'make_clickable',  9 );
}

/** Display website on new window when readers click Commenter's name **/
$tbx_cmter_new_window = isset(get_option( 'tbx_comments')['tbx_cmter_new_window']) ? get_option( 'tbx_comments')['tbx_cmter_new_window'] : '';
if( $tbx_cmter_new_window == 'on' ){
	function open_comment_author_link_in_new_window( $author_link ) {
		return str_replace( "<a", "<a target='_blank'", $author_link );
	}
	add_filter( 'get_comment_author_link', 'open_comment_author_link_in_new_window' );
}

/** Remove Width and Height of all images for responsive **/
$tbx_remove_image_wh = isset(get_option( 'tbx_customize')['tbx_remove_image_wh']) ? get_option( 'tbx_customize')['tbx_remove_image_wh'] : '';
if( $tbx_remove_image_wh == 'on' ){
	add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
	add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
	add_filter( 'the_content', 'remove_thumbnail_dimensions', 10 );
	function remove_thumbnail_dimensions( $html ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
}

/* Gravater cdn */

$tbx_gravatar_cdn = @(get_option( 'tbx_optimize')['tbx_gravatar_cdn']) ?? '';
if($tbx_gravatar_cdn == 'on' ){
	function tbx_gravatar($avatar) {
	$avatar = str_replace(array("//gravatar.com/", "//secure.gravatar.com/", "//www.gravatar.com/", "//0.gravatar.com/", "//1.gravatar.com/", "//2.gravatar.com/", "//cn.gravatar.com/"), "//dn-qiniu-avatar.qbox.me/", $avatar);
	return $avatar;}
	add_filter( 'get_avatar', 'tbx_gravatar' );
}

/* emoji cdn */
$tbx_emoji_cdn = (get_option( 'tbx_optimize')['tbx_emoji_cdn']) ?? '';
if( $tbx_emoji_cdn == 'on' ){
	function theme_wp_emoji_svgurl($url) {
	//	return set_url_scheme('//cdn.bootcss.com/twemoji/12.0.4/2/svg/');
		return set_url_scheme('//cdn.jsdelivr.net/npm/twemoji@11.2.0/2/svg/');
	//	return set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/twemoji/12.0.4/2/svg/');
	}
	add_filter('emoji_svg_url', 'theme_wp_emoji_svgurl');
}



/* Hide-n-Disable-comment-url-field*/
$tbx_disable_cmt = isset(get_option( 'tbx_comments')['tbx_disable_cmt']) ? get_option( 'tbx_comments')['tbx_disable_cmt'] : '';
if( $tbx_disable_cmt == 'on' ){
	function Hide_n_Disable_comment_url_field($fields)
	{
		unset($fields['url']);
		return $fields;
	}
	add_filter('comment_form_default_fields','Hide_n_Disable_comment_url_field');
}


//show current user uploads only
$tbx_only_my_media = isset(get_option( 'tbx_security')['tbx_only_my_media']) ? get_option( 'tbx_security')['tbx_only_my_media'] : '';
if( $tbx_only_my_media == 'on' ){
	function km_upload_media( $wp_query_obj ) {
		global $current_user, $pagenow;
		if( !is_a( $current_user, 'WP_User') )
			return;
		if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
			return;
		if( !current_user_can( 'manage_options' ) && !current_user_can('manage_media_library') )
			$wp_query_obj->set('author', $current_user->ID );
		return;
	}
	add_action('pre_get_posts','km_upload_media');
	
	function km_media_library( $wp_query ) {
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
			if ( !current_user_can( 'manage_options' ) && !current_user_can( 'manage_media_library' ) ) {
				global $current_user;
				$wp_query->set( 'author', $current_user->id );
			}
		}
	}
	add_filter('parse_query', 'km_media_library' );
}

//Spam portaction some chinese please
$tbx_some_chinese = isset(get_option( 'tbx_comments')['tbx_some_chinese']) ? get_option( 'tbx_comments')['tbx_some_chinese'] : '';
if( $tbx_some_chinese == 'on' ){
	function refused_spam_comments( $comment_data ) {  
	$pattern = '/[一-龥]/u';  
	if(!preg_match($pattern,$comment_data['comment_content'])) {  
	wp_die( __('Need chinese charactors to submit your comment.','toolbox-by-dukeyin') );  
	}  
	return( $comment_data );  
	}  
	add_filter('preprocess_comment','refused_spam_comments');
}


// Add at in comments
$tbx_add_at = isset(get_option( 'tbx_comments')['tbx_add_at']) ? get_option( 'tbx_comments')['tbx_add_at'] : '';
if( $tbx_add_at == 'on' ){
	function duke_comment_add_at( $comment_text, $comment = '') {
	  if( $comment->comment_parent > 0) {
		$comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
	  }

	  return $comment_text;
	}
	add_filter( 'comment_text' , 'duke_comment_add_at', 20, 2);
}