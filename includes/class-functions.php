<?php

// All settings
if( ( isset(get_option( 'tbx_general')['tbx_all_settings']) ) == 'on' ){
	function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
   }
   add_action('admin_menu', 'all_settings_link');
}

//Gzip
if( ( isset(get_option( 'tbx_general')['tbx_gzip']) ) == 'on' ){
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
if( ( isset(get_option( 'tbx_general')['tbx_autolink']) ) == 'on' ){
add_filter('the_content', 'make_clickable');
}

//Comment link unclickable
if( ( isset(get_option( 'tbx_general')['tbx_unlink_cmt']) ) == 'on' ){
	remove_filter( 'comment_text', 'make_clickable',  9 );
}

/** Display website on new window when readers click Commenter's name **/
if( ( isset(get_option( 'tbx_general')['tbx_cmter_new_window']) ) == 'on' ){
	function open_comment_author_link_in_new_window( $author_link ) {
		return str_replace( "<a", "<a target='_blank'", $author_link );
	}
	add_filter( 'get_comment_author_link', 'open_comment_author_link_in_new_window' );
}

/** Remove Width and Height of all images for responsive **/
if( ( isset(get_option( 'tbx_general')['tbx_remove_image_wh']) ) == 'on' ){
	add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
	add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
	add_filter( 'the_content', 'remove_thumbnail_dimensions', 10 );
	function remove_thumbnail_dimensions( $html ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
}

/* emoji cdn */
if( ( isset(get_option( 'tbx_general')['tbx_emoji_cdn']) ) == 'on' ){
	function theme_wp_emoji_svgurl($url) {
	//	return set_url_scheme('//cdn.bootcss.com/twemoji/12.0.4/2/svg/');
		return set_url_scheme('//cdn.jsdelivr.net/npm/twemoji@11.2.0/2/svg/');
	//	return set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/twemoji/12.0.4/2/svg/');
	}
	add_filter('emoji_svg_url', 'theme_wp_emoji_svgurl');
}


/* Hide-n-Disable-comment-url-field*/
if( ( isset(get_option( 'tbx_general')['tbx_disable_cmt']) ) == 'on' ){
	function Hide_n_Disable_comment_url_field($fields)
	{
		unset($fields['url']);
		return $fields;
	}
	add_filter('comment_form_default_fields','Hide_n_Disable_comment_url_field');
}


//show current user uploads only
if( ( isset(get_option( 'tbx_general')['tbx_only_my_media']) ) == 'on' ){
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




























