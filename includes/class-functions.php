<?php

// All settings
$tbx_all_settings = get_option( 'tbx_general')['tbx_all_settings'] ?? '';
if( $tbx_all_settings == 'on' ){
	function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
   }
   add_action('admin_menu', 'all_settings_link');
}

//Gzip
$tbx_gzip = get_option( 'tbx_general')['tbx_gzip'] ?? '';
if( $tbx_gzip == 'on' ){
if(extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler"))
   add_action('wp', create_function('', '@ob_end_clean();@ini_set("zlib.output_compression", 1);'));
}

//Autolink
$tbx_autolink = get_option( 'tbx_general')['tbx_autolink'] ?? '';
if( $tbx_autolink == 'on' ){
	add_filter('the_content', 'make_clickable');
}

//Comment link unclickable
$tbx_unlink_cmt = get_option( 'tbx_comments')['tbx_unlink_cmt'] ?? '';
if( $tbx_unlink_cmt == 'on' ){
	remove_filter( 'comment_text', 'make_clickable',  9 );
}

/** Display website on new window when readers click Commenter's name **/
$tbx_cmter_new_window = get_option( 'tbx_comments')['tbx_cmter_new_window'] ?? '';
if( $tbx_cmter_new_window == 'on' ){
	function open_comment_author_link_in_new_window( $author_link ) {
		return str_replace( "<a", "<a target='_blank'", $author_link );
	}
	add_filter( 'get_comment_author_link', 'open_comment_author_link_in_new_window' );
}

/** Remove Width and Height of all images for responsive **/
$tbx_remove_image_wh = get_option( 'tbx_customize')['tbx_remove_image_wh'] ?? '';
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

$tbx_gravatar_cdn = get_option( 'tbx_optimize')['tbx_gravatar_cdn'] ?? '';
if($tbx_gravatar_cdn == 'on' ){
	function tbx_gravatar($avatar) {
	$avatar = str_replace(array("//gravatar.com/", "//secure.gravatar.com/", "//www.gravatar.com/", "//0.gravatar.com/", "//1.gravatar.com/", "//2.gravatar.com/", "//cn.gravatar.com/"), "//dn-qiniu-avatar.qbox.me/", $avatar);
	return $avatar;}
	add_filter( 'get_avatar', 'tbx_gravatar' );
}

/* emoji cdn */
$tbx_emoji_cdn = get_option( 'tbx_optimize')['tbx_emoji_cdn'] ?? '';
if( $tbx_emoji_cdn == 'on' ){
	function theme_wp_emoji_svgurl($url) {
	//	return set_url_scheme('//cdn.bootcss.com/twemoji/12.0.4/2/svg/');
		return set_url_scheme('//cdn.jsdelivr.net/npm/twemoji@11.2.0/2/svg/');
	//	return set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/twemoji/12.0.4/2/svg/');
	}
	add_filter('emoji_svg_url', 'theme_wp_emoji_svgurl');
}



/* Hide-n-Disable-comment-url-field*/
$tbx_disable_cmt = get_option( 'tbx_comments')['tbx_disable_cmt'] ?? '';
if( $tbx_disable_cmt == 'on' ){
	function Hide_n_Disable_comment_url_field($fields)
	{
		unset($fields['url']);
		return $fields;
	}
	add_filter('comment_form_default_fields','Hide_n_Disable_comment_url_field');
}


//show current user uploads only
$tbx_only_my_media = get_option( 'tbx_security')['tbx_only_my_media'] ?? '';
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
$tbx_some_chinese = get_option( 'tbx_comments')['tbx_some_chinese'] ?? '';
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
$tbx_add_at = get_option( 'tbx_comments')['tbx_add_at'] ?? '';
if( $tbx_add_at == 'on' ){
	function duke_comment_add_at( $comment_text, $comment = '') {
	  if( $comment->comment_parent > 0) {
		$comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
	  }

	  return $comment_text;
	}
	add_filter( 'comment_text' , 'duke_comment_add_at', 20, 2);
}
// Revisons
$tbx_revisons = get_option( 'tbx_optimize')['tbx_revisons'] ?? '';
if( $tbx_revisons > 0 ){
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', abs(get_option( 'tbx_optimize')['tbx_revisons']));
}

//length of Exerpt
$tbx_excerpt_length = get_option( 'tbx_customize')['tbx_excerpt_length'] ?? '';
if( $tbx_excerpt_length > 0 ){
	function tbx_excerpt_length( $length ) { 
		 return abs(get_option( 'tbx_customize')['tbx_excerpt_length']);  
	}  
	add_filter( 'excerpt_length', 'tbx_excerpt_length', 999 );
}

// Thumbnail list
$tbx_thum_list = get_option( 'tbx_general')['tbx_thum_list'] ?? '';
if( $tbx_thum_list == 'on' ){
	/****** Add Thumbnails in Manage Posts/Pages List ******/
	if ( !function_exists('AddThumbColumn') && function_exists('add_theme_support') ) {
		// for post and page
		add_theme_support('post-thumbnails', array( 'post', 'page' ) );
		function AddThumbColumn($cols) {
			$cols['thumbnail'] = __('Thumbnail');
			return $cols;
		}
		function AddThumbValue($column_name, $post_id) {
				$width = (int) 35;
				$height = (int) 35;
				if ( 'thumbnail' == $column_name ) {
					// thumbnail of WP 2.9
					$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
					// image from gallery
					$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
					if ($thumbnail_id)
						$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
					elseif ($attachments) {
						foreach ( $attachments as $attachment_id => $attachment ) {
							$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
						}
					}
						if ( isset($thumb) && $thumb ) {
							echo $thumb;
						} else {
							echo __('None');
						}
				}
		}
		// for posts
		add_filter( 'manage_posts_columns', 'AddThumbColumn' );
		add_action( 'manage_posts_custom_column', 'AddThumbValue', 10, 2 );
		// for pages
		add_filter( 'manage_pages_columns', 'AddThumbColumn' );
		add_action( 'manage_pages_custom_column', 'AddThumbValue', 10, 2 );
	}
}


//Remove ping to your own blog
$tbx_no_self_ping = get_option( 'tbx_optimize')['tbx_no_self_ping'] ?? '';
if($tbx_no_self_ping == 'on'){
	function no_self_ping( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link )
			if ( 0 === strpos( $link, $home ) )
				unset($links[$l]);
	}
	add_action( 'pre_ping', 'no_self_ping' );
}

// custom addmin footer text
$tbx_admin_footer = get_option('tbx_customize')['tbx_admin_footer'] ?? '';
if($tbx_admin_footer != ''){
	function custom_admin_footer() {
		echo get_option('tbx_customize')['tbx_admin_footer'];
	} 
	add_filter('admin_footer_text', 'custom_admin_footer');
}

//Enable Short code in Widgets
$tbx_widget_shortcode = get_option('tbx_customize')['tbx_widget_shortcode'] ?? '';
if($tbx_widget_shortcode == 'on'){
	if ( !is_admin() ){
		add_filter('widget_text', 'do_shortcode', 11);
	}
}

//Disable RSS
$tbx_disable_rss = get_option('tbx_customize')['tbx_disable_rss'] ?? '';
if($tbx_disable_rss == 'on'){
	function fb_disable_feed() {
	wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!', 'toolbox-by-dukeyin') );
	}
	add_action('do_feed', 'fb_disable_feed', 1);
	add_action('do_feed_rdf', 'fb_disable_feed', 1);
	add_action('do_feed_rss', 'fb_disable_feed', 1);
	add_action('do_feed_rss2', 'fb_disable_feed', 1);
	add_action('do_feed_atom', 'fb_disable_feed', 1);
}

//re-attach midea option
$tbx_reattach_media = get_option( 'tbx_optimize')['tbx_reattach_media'] ?? '';
if($tbx_reattach_media == 'on'){
	add_filter("manage_upload_columns", 'upload_columns');
	add_action("manage_media_custom_column", 'media_custom_columns', 0, 2);
	function upload_columns($columns) {
		unset($columns['parent']);
		$columns['better_parent'] = "Parent";
		return $columns;
	}
	function media_custom_columns($column_name, $id) {
		$post = get_post($id);
		if($column_name != 'better_parent')
		return;
		if ( $post->post_parent > 0 ) {
			if ( get_post($post->post_parent) ) {
				$title =_draft_or_post_title($post->post_parent);
			}
			?>
			<strong><a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title ?></a></strong>, <?php echo get_the_time(__('Y/m/d')); ?>
			<br />
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Re-Attach','toolbox-by-dukeyin'); ?></a>
			<?php
		} else {
			?>
			<?php _e('(Unattached)'); ?><br />
			<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Attach','toolbox-by-dukeyin'); ?></a>
			<?php
		}
	}
}

//custom admin logo
$tbx_custom_login_logo = get_option('tbx_customize')['tbx_custom_login_logo'] ?? '';
if($tbx_custom_login_logo != ''){
	function custom_login_logo() {
		echo '
			<style type="text/css">
			.login h1 a { 
				background-image:url('.get_option('tbx_customize')['tbx_custom_login_logo'].');
				background-size:contain;width: 100%;
			}
			</style>
		';
	}
	add_action('login_head', 'custom_login_logo');
}