<?php
/**
 * Enqueue styles
 */
function tg_styles()
{

    wp_enqueue_style('theme-custom-fonts', 'https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap', array(), _S_VERSION);
    wp_enqueue_style('theme-custom-styles', get_template_directory_uri() . '/static/css/styles.min.css', array(), _S_VERSION);
    wp_enqueue_style('swiper-slider-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css', array(), _S_VERSION);
    /**
     * If there is need for custom fonts
     */
    // wp_enqueue_style('theme-custom-fonts', 'https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap', array(), _S_VERSION);

}
add_action('wp_enqueue_scripts', 'tg_styles');