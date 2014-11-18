<?php
/**
 * @package image-in-postlist
 * @version 1.0
 */
/*
Plugin Name: image in postlist
Plugin URI: http://www.funsite.eu/plugins/image-in-postlist
Description: This plugin adds the featured image icon to the postlist.
Author: Gerhard Hoogterp
Version: 1.0
Author URI: http://www.funsite.eu/
*/

if (!function_exists('funsite_array_insert_after')) {
	function funsite_array_insert_after($key, &$array, $new_key, $new_value) {
	if (array_key_exists($key, $array)) {
		$new = array();
		foreach ($array as $k => $value) {
		$new[$k] = $value;
		if ($k === $key) {
			$new[$new_key] = $new_value;
		}
		}
		return $new;
	}
	return FALSE;
	}
}

add_filter('manage_posts_columns', 'my_columns');
function my_columns($columns) {
	$columns = funsite_array_insert_after('title',$columns,'image','Featured image');
    return $columns;
}

add_action('manage_posts_custom_column',  'my_show_columns');
function my_show_columns($name) {
    global $post;
    switch ($name) {
        case 'image':
				$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
				if ($post_thumbnail_id) {
					$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail'); 
					echo '<img src="'.$thumb[0].'" alt="" style="width:50px">';
				} else {
					echo "-";
				}
    }
}

//	-------------------------------------------------------------------------------------- 
//	Extra links in the plugin overview
//	--------------------------------------------------------------------------------------
function image_in_postlist_PluginLinks($links, $file) {
		$base = plugin_basename(__FILE__);
		if ($file == $base) {
			$links[] = '<a href="https://wordpress.org/support/view/plugin-reviews/image-in-postlist" title="'.__('a review would be appriciated!','myPlugins').'">' . __('reviews','myPlugins') . '</a>';
			$links[] = '<a href="http://www.funsite.eu/plugins/">' . __('Other plugins written by me','myPlugins') . '</a>';
		}
		return $links;
	}

add_filter('plugin_row_meta', 'image_in_postlist_PluginLinks',10,2);
?>