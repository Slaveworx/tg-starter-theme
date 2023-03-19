<?php

namespace TG;

/**
 * TG Theme Functions & Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package tg
 */


/**
 * Change Version with every theme release
 */
if (!defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

/**
 * Define theme text domain
 */
if (!defined('THEME_TEXT_DOMAIN')) {
	define('THEME_TEXT_DOMAIN', "tg");
}

/*************************************************************************************************
 ** Disable Gutenberg CSS by Default (COMMENT THIS FUNCTION OUT IF YOU ARE USING GUTENBERG BLOCKS)
 *************************************************************************************************/

function remove_wp_block_library_css(){
 wp_dequeue_style( 'wp-block-library' );
 wp_dequeue_style( 'wp-block-library-theme' );
 wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

/*********************************************************************************************** */


//### GENERAL CONFIGS ############################################
require_once(get_template_directory() . "/config/config.php"); //#
//################################################################

new TG();