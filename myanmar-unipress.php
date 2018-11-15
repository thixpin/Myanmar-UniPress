<?php
/**
 * @package myanmar-unipress
 * @version 1.0.0
 */
/*
Plugin Name: Myanmar UniPress
Plugin URI: http://wordpress.org/extend/plugins/myanmar-unipress/
Description: Myanmar UniPress will check myanmar content and convert to browser encoding if the content font is not equal to brower font. It use Parabaik Converter, Myanmar Font Tagger Script(by Ko Thant Thet Khin Zaw), ZGDetector of sanlinnaing (for content type checking) and the browser font detecting idea from Ko Ei maung. 
Author: thixpin
Version: 1.0.0
Author URI: http://fb.me/thixpin
*/

require 'Bunny.php';
require 'adminpanel.php';

/********* add action link in plugin list ***********/
add_filter( 'plugin_action_links',   'unipress_actions', 10, 2 );
function unipress_actions( $links, $file ) {
	$this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="'. esc_url( admin_url( 'options-general.php?page=myanmar-unipress' ) ) .'">'. __( 'Configure', 'myanmar-unipress' ) .'</a>';
		array_unshift( $links, $settings_link );
	} 
	return $links;
}

/********* Load javascript and css in eader ***********/
function unipress_header(){
    if(!is_admin()){
        echo "<script src='".plugin_dir_url( __FILE__ )."_inc/js/rabbit.js'></script>";
        if(get_option('IndicateConverted') == 1){
            echo "<link rel='stylesheet' href='".plugin_dir_url( __FILE__ )."_inc/css/bunny.css'/>";
        }
    }
}

/********* Load javascript in footer ***********/
function unipress_footer(){
    if(!is_admin()){
        echo "<script src='".plugin_dir_url( __FILE__ )."_inc/js/bunny.js'></script>";
	}
}

/********* Save post/page title and content as unicode ***********/
function filter_post_data( $data , $postarr ) {
    $data['post_title']     = Bunny::edit_mmtext($data['post_title']);
    $data['post_content']   = Bunny::edit_mmtext($data['post_content']);
    return $data;
}

/********* Save coment text as unicode ***********/
function filter_comment_content($content) {
    return Bunny::edit_mmtext($content);
}

/********* Check and convert the search text to unicode ***********/
function filter_search($query) {
    if ( !is_admin() && $query->is_main_query() ) {
        if ($query->is_search) {
            $query->query['s'] = Bunny::edit_mmtext($query->query['s']);
            $query->query_vars['s'] = Bunny::edit_mmtext($query->query_vars['s']);
        }
    }
}

/********* Registers an editor stylesheet ***********/
function unipress_add_editor_styles( $mceInit ) {
    //$styles  = 'body.mce-content-body { background-color: #' . get_theme_mod( 'background-color', '#FFF' ) . '}';
    $styles  = '#tinymce .column{ width: 48%; display: inline-block; vertical-align: top; margin-bottom: 10px; background: #F1F1F1; } ';
    $styles .= ' #tinymce .column:nth-of-type(2n+2) { margin-left: 4%; } ';
    $styles .= ' #tinymce .column img{ max-width: 100%; margin: 0; margin-bottom: 10px; }';
    if ( isset( $mceInit['content_style'] ) ) {
        $mceInit['content_style'] .= ' ' . $styles . ' ';
    } else {
        $mceInit['content_style'] = $styles . ' ';
    }
    return $mceInit;
}


/********* TinyMCE Buttons ***********/
function unipress_buttons() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }

    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
        return;
    }

    add_filter( 'mce_external_plugins', 'unipress_add_buttons' );
    add_filter( 'mce_buttons', 'unipress_register_buttons' );
}
 
function unipress_add_buttons( $plugin_array ) {
    $plugin_array['uni_to_zg'] = plugin_dir_url( __FILE__ ).'_inc/js/tinymce_buttons.js';
    $plugin_array['zg_to_uni'] = plugin_dir_url( __FILE__ ).'_inc/js/tinymce_buttons.js';
    return $plugin_array;
}
 
function unipress_register_buttons( $buttons ) {
    array_push( $buttons, 'uni_to_zg' );
    array_push( $buttons, 'zg_to_uni' );
    return $buttons;
}

/********* load js for font detecting and converting ***********/
add_action('wp_head', 'unipress_header');
add_action('wp_footer','unipress_footer');

/********* Edit contents before save ***********/
add_action('pre_get_posts','filter_search');
add_filter('wp_insert_post_data', 'filter_post_data' , '99', 2 );
add_filter('pre_comment_content', 'filter_comment_content');

/********* Add converter buttons in text editor ***********/
add_action( 'init', 'unipress_buttons' );
add_filter('tiny_mce_before_init','unipress_add_editor_styles');

?>