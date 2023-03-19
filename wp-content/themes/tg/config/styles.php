<?php

/**
 * Enqueue STYLES
 */

//*********************************************************************************************
//Enqueue Styles that need to be always present
//*********************************************************************************************
function tg_styles()
{


    wp_enqueue_style('theme-custom-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), _S_VERSION);
    wp_enqueue_style('theme-custom-styles', get_template_directory_uri() . '/static/css/main.css', array(), _S_VERSION);
   

}
add_action('wp_enqueue_scripts', 'tg_styles');
