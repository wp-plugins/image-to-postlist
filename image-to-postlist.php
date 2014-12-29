<?php
/**
 * @package image-to-postlist
 * @version 1.4
 */
/*
Plugin Name: image to postlist
Plugin URI: http://www.funsite.eu/plugins/image-to-postlist
Description: This plugin adds the featured image icon to the posts- and pageslists.
Author: Gerhard Hoogterp
Version: 1.4
Author URI: http://www.funsite.eu/
*/



class image_to_postlist_class {

	const FS_TEXTDOMAIN = 'image-to-postlist';
	const FS_PLUGINNAME = 'image-to-postlist';

	public $posttypelist = array('posts','pages');
	
    public function __construct() {
		add_action('init', array($this,'myTextDomain'));
		add_filter('plugin_row_meta', array($this,'image_to_postlist_PluginLinks'),10,2);

		foreach($this->posttypelist as $posttype) {
			add_filter('manage_'.$posttype.'_columns', array($this,'my_columns'));
			add_action('manage_'.$posttype.'_custom_column',  array($this,'my_show_columns'));
		}
}
    
    function array_insert_after($key, &$array, $new_key, $new_value) {
		if (array_key_exists($key, $array)) {
			$new = array();
			foreach ($array as $k => $value) {
				$new[$k] = $value;
				if ($k === $key) {
					$new[$new_key] = $new_value;
				}
			}
			return $new;
		} else {
			return FALSE;
		}
	}

	function my_columns($columns) {
		$columns = $this->array_insert_after('title',$columns,'image',__('Featured image',self::FS_TEXTDOMAIN));
		return $columns;
	}

	function my_show_columns($name) {
		global $post;
		switch ($name) {
			case 'image':
					$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
					if ($post_thumbnail_id) {
						$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail'); 
						echo '<img src="'.$thumb[0].'" alt="" style="width:50px">';
					} else {
						echo "--";
					}
		}
	}


	function myTextDomain() {
		load_plugin_textdomain(
			self::FS_TEXTDOMAIN,
			false,
			dirname(plugin_basename(__FILE__)).'/languages/'
		);
	}
	
	function image_to_postlist_PluginLinks($links, $file) {
		$base = plugin_basename(__FILE__);
		if ($file == $base) {
						$links[] = '<a href="https://wordpress.org/support/view/plugin-reviews/'.self::FS_PLUGINNAME.'#postform">' . __('Please rate me.',self::FS_TEXTDOMAIN) . '</a>';
		}
		return $links;
	}
}

$image_to_postlist = new image_to_postlist_class();
?>