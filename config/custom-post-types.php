<?php
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
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



// register_post_type(
//     'portfolio',
//     array(
//         'labels' => array(
//             'name' => _x('Portfolio Item', 'textdomain'),
//             'singular_name' => _x('Portfolio Items', 'textdomain'),
//             'menu_name' => _x('Portfolio', 'textdomain'),
//             'name_admin_bar' => _x('Portfolio', 'textdomain'),
//             'add_new' => __('Add Item', 'textdomain'),
//             'add_new_item' => __('Add Item', 'textdomain'),
//             'new_item' => __('New', 'textdomain'),
//             'edit_item' => __('Edit', 'textdomain'),
//             'view_item' => __('View', 'textdomain'),
//             'all_items' => __('All Portfolio Items', 'textdomain'),
//             'search_items' => __('Portfolio Items', 'textdomain'),
//         ),
//         'public' => true,
//         'has_archive' => true,
//         // 'rewrite' => array('slug' => 'press'),
//         'show_in_rest' => true,
//         'menu_icon' => 'dashicons-paperclip',
//         'show_in_menu' => true,
//         'menu_position' => 0,
//         'capability_type' => 'post',
//         'supports' => array('title', 'page-attributes', 'revisions')

//     )
// );

// register_post_type(
//     'technologies',
//     array(
//         'labels' => array(
//             'name' => _x('Technology', 'textdomain'),
//             'singular_name' => _x('Technologies', 'textdomain'),
//             'menu_name' => _x('Technologies', 'textdomain'),
//             'name_admin_bar' => _x('Technologies', 'textdomain'),
//             'add_new' => __('Add Item', 'textdomain'),
//             'add_new_item' => __('Add Item', 'textdomain'),
//             'new_item' => __('New', 'textdomain'),
//             'edit_item' => __('Edit', 'textdomain'),
//             'view_item' => __('View', 'textdomain'),
//             'all_items' => __('All Technologies', 'textdomain'),
//             'search_items' => __('Technologies', 'textdomain'),
//         ),
//         'public' => true,
//         'has_archive' => false,
//         'show_in_rest' => true,
//         'menu_icon' => 'dashicons-media-code',
//         'show_in_menu' => true,
//         'menu_position' => 0,
//         'capability_type' => 'post',
//         'supports' => array('title', 'page-attributes')

//     )
// );


// register_post_type(
//     'courses',
//     array(
//         'labels' => array(
//             'name' => _x('Course', 'textdomain'),
//             'singular_name' => _x('Courses', 'textdomain'),
//             'menu_name' => _x('Courses', 'textdomain'),
//             'name_admin_bar' => _x('Courses', 'textdomain'),
//             'add_new' => __('Add Item', 'textdomain'),
//             'add_new_item' => __('Add Item', 'textdomain'),
//             'new_item' => __('New', 'textdomain'),
//             'edit_item' => __('Edit', 'textdomain'),
//             'view_item' => __('View', 'textdomain'),
//             'all_items' => __('All Courses', 'textdomain'),
//             'search_items' => __('Courses', 'textdomain'),
//         ),
//         'public' => true,
//         'has_archive' => true,
//         'show_in_rest' => true,
//         'menu_icon' => 'dashicons-welcome-learn-more',
//         'show_in_menu' => true,
//         'menu_position' => 0,
//         'capability_type' => 'post',
//         'supports' => array('title', 'page-attributes')

//     )
// );
