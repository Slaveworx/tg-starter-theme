<?php
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file should be used to create all your Custom Post Types                       
                                        
//****************************************


TG\TG::create_cpt(
    "portfolio",
    array(
        'menu_position'     => 0,
        'menu_icon'         => 'dashicons-paperclip',
        'supports'          => array('title', 'page-attributes', 'revisions')
    ),
    array('menu_name' => _x('Portfolio', THEME_TEXT_DOMAIN))
);

TG\TG::create_cpt(
    "technology",
    array(
        'menu_position'     => 0,
        'menu_icon'         => 'dashicons-media-code',
        'supports'          => array('title', 'page-attributes', 'revisions')
    ),
    array('menu_name' => _x('Technologies', THEME_TEXT_DOMAIN))
);

TG\TG::create_cpt(
    "course",
    array(
        'menu_position'     => 0,
        'menu_icon'         => 'dashicons-welcome-learn-more',
        'supports'          => array('title', 'page-attributes'),
        'has_archive'       => false
    ),
    array('menu_name' => _x('Courses', THEME_TEXT_DOMAIN))
);