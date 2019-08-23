<?php
/**
 * @package myanmar-unipress
 * @version 1.3.0
 */
/*
Plugin Name: Myanmar UniPress
Plugin URI: http://wordpress.org/extend/plugins/myanmar-unipress/
Description: Myanmar UniPress will check myanmar content and convert to browser encoding if the content font is not equal to brower font. It use Parabaik Converter, Myanmar Font Tagger Script(by Ko Thant Thet Khin Zaw), ZGDetector of sanlinnaing (for content type checking) and the browser font detecting idea from Ko Ei maung. 
Author: thixpin
Version: 1.3.0
Author URI: http://fb.me/thixpin
*/

define( 'UNIP_VERSION', '1.3.0' );

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
function unipress_enqueue_scripts(){

    $a = plugin_dir_url( __FILE__ );
    $v = UNIP_VERSION;

    // font embedding
    if(!is_admin()){
        $font = get_option('fontFamily') ; 
        wp_enqueue_style( 'embedded_css', $a . '_inc/fonts/?font=' . $font , array(), $v );
    }
    
    // Adding Zawgyi-Meta Tags for Facebook sharing.
    if(!is_admin() && is_single() && get_option('ShareAsZawgyi') == 1){
        $og_title =  Bunny::uni2zg(wp_get_document_title());
        $og_description = get_post_field('post_content', get_the_ID());
        $og_description = Bunny::uni2zg(wp_trim_words($og_description, 40 , ''));   
        ?><meta property="og:title" name="twitter:title" content="<?php echo $og_title; ?>"><?php echo PHP_EOL; 
        ?><meta property="og:description" name="twitter:description" content="<?php echo $og_description; ?>"> <?php echo PHP_EOL;
    }

    if(!is_admin() && get_option('BunnyDisabled') != 1){

        if(get_option('IndicateConverted') == 1){
            wp_enqueue_style( 'bunny_css', $a . '_inc/css/bunny.css', array(), $v );
        }
        
        wp_enqueue_script( 'rabbit', $a . '_inc/js/rabbit.js', array(), $v, false );
        wp_enqueue_script( 'bunny', $a . '_inc/js/bunny.js', array(), $v, true );
        
    }
}

/********* Save post/page title and content as unicode ***********/
function filter_post_data( $data , $postarr ) {
    if(get_option('DisableConvert2Save') != 1){
        $data['post_title']     = Bunny::edit_mmtext($data['post_title']);
        $data['post_content']   = Bunny::edit_mmtext($data['post_content']);
        $data['post_excerpt']   = Bunny::edit_mmtext($data['post_excerpt']);
        $data['post_name']      = Bunny::edit_mmtext($data['post_name']);
    }
    return $data;
}

/********* Save coment text as unicode ***********/
function filter_comment_content($content) {
    return Bunny::edit_mmtext($content);
}

/********* Check and convert the search text to unicode ***********/
function filter_search($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search) {
            $query->query['s'] = Bunny::edit_mmtext($query->query['s']);
            $query->query_vars['s'] = Bunny::edit_mmtext($query->query_vars['s']);
        }
    }
}

/********* Registers an editor stylesheet ***********/
function unipress_add_editor_styles($mceInit) {
    //$styles  = 'body.mce-content-body { background-color: #' . get_theme_mod( 'background-color', '#FFF' ) . '}';
    $styles  = '#tinymce .column{ width: 48%; display: inline-block; vertical-align: top; margin-bottom: 10px; background: #F1F1F1; } ';
    $styles .= ' #tinymce .column:nth-of-type(2n+2) { margin-left: 4%; } ';
    $styles .= ' #tinymce .column img{ max-width: 100%; margin: 0; margin-bottom: 10px; }';
    if (isset($mceInit['content_style'])) {
        $mceInit['content_style'] .= ' '.$styles.' ';
    } else {
        $mceInit['content_style'] = $styles.' ';
    }
    return $mceInit;
}


/********* TinyMCE Buttons ***********/
function unipress_buttons() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (get_user_option('rich_editing') !== 'true') {
        return;
    }

    add_filter('mce_external_plugins', 'unipress_add_buttons');
    add_filter('mce_buttons', 'unipress_register_buttons');
}
 
function unipress_add_buttons($plugin_array) {
    $v = UNIP_VERSION;
    $plugin_array['uni_to_zg'] = plugin_dir_url(__FILE__).'_inc/js/tinymce_buttons.js?v='.$v;
    $plugin_array['zg_to_uni'] = plugin_dir_url(__FILE__).'_inc/js/tinymce_buttons.js?v='.$v;
    return $plugin_array;
}
 
function unipress_register_buttons($buttons) {
    array_push($buttons, 'uni_to_zg');
    array_push($buttons, 'zg_to_uni');
    return $buttons;
}

/********* load js for font detecting and converting ***********/
add_action('wp_enqueue_scripts', 'unipress_enqueue_scripts');

/********* Edit contents before save ***********/
add_action('pre_get_posts', 'filter_search');
add_filter('wp_insert_post_data', 'filter_post_data', '99', 2);
add_filter('pre_comment_content', 'filter_comment_content');

/********* Add converter buttons in text editor ***********/
add_action('init', 'unipress_buttons');
add_filter('tiny_mce_before_init', 'unipress_add_editor_styles');

// // define the edit_post_link callback 
// function filter_edit_post_link( $link, $post_id, $text ) { 
//     // make filter magic happen here... 
//     return $link; 
// }; 
         
// // add the filter 
// add_filter( 'edit_post_link', 'filter_edit_post_link', 10, 3 );
?>