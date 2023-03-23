<?php

//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file should be used to enqueue your STYLES                         

//****************************************


//*********************************************************************************************
//Enqueue Styles that need to be always present
//*********************************************************************************************

function tg_styles()
{
    wp_enqueue_style('theme-custom-styles', get_template_directory_uri() . '/static/css/main.css', array(), _S_VERSION);
    wp_enqueue_style('style-css', get_template_directory_uri() . '/style.css', array(), _S_VERSION);
}
add_action('wp_enqueue_scripts', 'tg_styles');

/*************************************************************************************************
 ** Disable Gutenberg CSS by Default (COMMENT THIS FUNCTION OUT IF YOU ARE USING GUTENBERG BLOCKS)
 *************************************************************************************************/

function remove_gutenberg_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block CSS
}

add_action('wp_enqueue_scripts', 'remove_gutenberg_css', 100);

/*********************************************************************************************** */
