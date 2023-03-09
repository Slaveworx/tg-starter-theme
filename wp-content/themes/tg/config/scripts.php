<?php
/**
 * Enqueue scripts
 */
function tg_scripts()
{

	wp_enqueue_script('theme-custom-scripts', get_template_directory_uri() . '/static/js/scripts.min.js', array(), _S_VERSION, true);
	wp_enqueue_script('swiper-slider-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), _S_VERSION, true);

}
add_action('wp_enqueue_scripts', 'tg_scripts');