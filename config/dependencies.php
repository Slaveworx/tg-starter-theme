<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
// * This file should be used to enqueue your CONDITIONAL STYLES AND SCRIPTS                        

//****************************************


/******************************************************************************************************
//* HERE you register Styles & Scripts that are not always needed and shall be enqueueue only when needed
 ******************************************************************************************************/

//JQUERY
TG\TG::add_dependency(
    'jquery-min',
    '',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js'
);

//ZEPTO (Lightweight Jquery Alternative) use Zepto(document).ready() instead of jQuery(document).ready() 
TG\TG::add_dependency(
    'zepto',
    '',
    'https://zeptojs.com/zepto.min.js'
);


//SWIPER
TG\TG::add_dependency(
    'swiper',
    'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css',
    'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js'
);

//FANCYBOX
TG\TG::add_dependency(
    'fancybox',
    'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
    'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js'
);
