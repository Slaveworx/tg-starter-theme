<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file should be used to add your theme support functions                  

//****************************************

//************************************ */
// General
//************************************ */
add_theme_support('automatic-feed-links');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

//************************************ */
// HTML 5
//************************************ */
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )
);

//************************************ */
// Widgets
//************************************ */
add_theme_support('customize-selective-refresh-widgets');

//************************************ */
// Post Formats
//************************************ */
add_theme_support(
    'post-formats',
    array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    )
);

//************************************ */
// Menus
//************************************ */
add_theme_support('menus');


//************************************ */
// Woocommerce
//************************************ */
add_theme_support('woocommerce');
