<?php
/*
Plugin Name: Hook Listener
Description: Get a button on the admin bar who display a list of the actions called on the current page !
Author: Hugo Derré
Version: 1.0.0
Author URI: https://hugoderre.fr/
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'HL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once(HL_PLUGIN_PATH . 'includes/hooks.php');
require_once(HL_PLUGIN_PATH . 'includes/class-hl-listener.php');

function h1_enqueue_scripts() {

    $custom_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/custom.js' ));
    $css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/style.css' ));

    wp_enqueue_script( 'hl_ajax_js', plugins_url( 'assets/js/custom.js', __FILE__ ), array(), $custom_ver );
    wp_localize_script( 'hl_ajax_js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
    wp_register_style( 'style',    plugins_url( 'assets/css/style.css',    __FILE__ ), false,   $css_ver );
    wp_enqueue_style ( 'style' );
}
add_action('wp_enqueue_scripts', 'h1_enqueue_scripts');
add_action('admin_enqueue_scripts', 'h1_enqueue_scripts');

