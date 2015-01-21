<?php
/**
 * @package image-to-postlist
 * @version 1.5
 */
/*
Plugin Name: image to postlist
Plugin URI: http://www.funsite.eu/plugins/image-to-postlist
Description: This plugin adds the featured image icon to the posts- and pageslists.
Author: Gerhard Hoogterp
Version: 1.5
Author URI: http://www.funsite.eu/
Text Domain: image-to-postlist
Domain Path: /languages
*/

if (!class_exists('basic_plugin_class')) {
	require(plugin_dir_path(__FILE__).'basics/basic_plugin.class');
}

class image_to_postlist_class extends basic_plugin_class {

	function getPluginBaseName() { return plugin_basename(__FILE__); }
	function getChildClassName() { return get_class($this); }

	public function __construct() {
		parent::__construct();

		$post_types = get_post_types( array('public'   => true,'_builtin' => false), 'names', 'and');
		$this->posttypelist=array_merge($this->posttypelist,$post_types);
		
		foreach($this->posttypelist as $posttype) {
			add_filter('manage_'.$posttype.'_columns', array($this,'my_columns'));
			add_action('manage_'.$posttype.'_custom_column',  array($this,'my_show_columns'));
		}
	}

	function pluginInfoRight($info) { 
		print __("<h3>Extra custom posttypes found:</h3>",self::FS_TEXTDOMAIN);

		$post_types = get_post_types( array('public'   => true,'_builtin' => false), 'names', 'and');
		if (empty($post_types)) {
			print __("None",self::FS_TEXTDOMAIN);
		} else {
			foreach($post_types as $t) {
				print $t.' ';
			}
		}

	}
	
	const FS_TEXTDOMAIN = 'image-to-postlist';
	const FS_PLUGINNAME = 'image-to-postlist';

	public $posttypelist = array('posts','pages');	
	
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
		$columns = $this->array_insert_after('title',$columns,'fs_image',__('Featured image',self::FS_TEXTDOMAIN));
		return $columns;
	}

	function my_show_columns($name) {
		global $post;
		switch ($name) {
			case 'fs_image':
					$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
					if ($post_thumbnail_id) {
						$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail'); 
						echo '<a href="'.admin_url().'post.php?post='.$post->ID.'&action=edit">';
						echo '<img src="'.$thumb[0].'" alt="" style="width:50px"></a>';
					} else {
						echo "--";
					}
		}
	}
}

$image_to_postlist = new image_to_postlist_class();
?>