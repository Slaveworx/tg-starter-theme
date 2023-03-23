<?php
namespace TG;
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file should be used to create all your Custom Post Types                       
                                        
//****************************************

//Register your Custom Post types here...

TG::create_cpt(
    "portfolio",
    array(
        'menu_position'     => 0,
        'menu_icon'         => 'dashicons-paperclip',
        'supports'          => array('title', 'page-attributes', 'revisions')
    ),
    array('menu_name' => _x('Portfolio', THEME_TEXT_DOMAIN))
);