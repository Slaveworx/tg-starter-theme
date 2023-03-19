<?php

namespace TG;

/******************************************************************************************************
//* HERE you register Styles & Scripts that are not always needed and shall be enqueueue only when needed
//*  --> add_dependency($handle, $style_src="", $scripts_src="", $script_args=array(), $inFooter=true)
 * @param string $handle The unique handle name for the dependency.
 * @param string $style_src The URL of the stylesheet to register for the dependency.
 * @param string $script_src The URL of the script to register for the dependency.
 * @param array $script_args Optional. An array of arguments to pass to the wp_register_script() dependencies array.
 * @param bool $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>. Default 'true'.
 ******************************************************************************************************/

//SWIPER
TG::add_dependency(
    'swiper',
    'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css',
    'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js'
);

//FANCYBOX
TG::add_dependency(
    'fancybox',
    'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
    'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js'
);


    // function swiper_scripts()
    // {
    //     wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css', array(), _S_VERSION);
    //     wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), _S_VERSION, true);
    // }

    // add_action('wp_enqueue_scripts', 'swiper_scripts');




// if (TG::$dependencies['fancybox'] === true) {
//     function fancybox_scripts()
//     {
//         wp_enqueue_style('fancybox-css', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), _S_VERSION);
//         wp_enqueue_script('fancybox-js', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array(), _S_VERSION, true);
//     }

//     add_action('wp_enqueue_scripts', 'fancybox_script');
// }
