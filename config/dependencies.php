<?php

namespace TG;
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
// * This file should be used to enqueue your CONDITIONAL STYLES AND SCRIPTS                        

//****************************************


/******************************************************************************************************
//* HERE you register Styles & Scripts that are not always needed and shall be enqueueue only when needed
 ******************************************************************************************************/

//JQUERY
TG::add_dependency(
    'jquery-min',
    '',
    get_template_directory_uri() . '/static/dependencies/js/jquery.min.js'
);

//ZEPTO (Lightweight Jquery Alternative) use Zepto(document).ready() instead of jQuery(document).ready() 
TG::add_dependency(
    'zepto',
    '',
    get_template_directory_uri() . '/static/dependencies/js/zepto.min.js'
);


//SWIPER
TG::add_dependency(
    'swiper',
    get_template_directory_uri() . '/static/dependencies/css/swiper-bundle.min.css',
    get_template_directory_uri() . '/static/dependencies/js/swiper-bundle.min.js'
);

//FANCYBOX
TG::add_dependency(
    'fancybox',
    get_template_directory_uri() . '/static/dependencies/css/fancybox.css',
    get_template_directory_uri() . '/static/dependencies/js/fancybox.umd.js'
);
