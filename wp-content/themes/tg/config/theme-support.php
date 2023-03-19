<?php
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
// * This file should be used to add your theme support functions                  
                                        
//****************************************


add_theme_support('automatic-feed-links');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
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
add_theme_support( 'customize-selective-refresh-widgets' );
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

add_theme_support('menus');