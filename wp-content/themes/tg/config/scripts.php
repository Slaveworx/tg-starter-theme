<?php

/**
 * Enqueue scripts
 */

//*************************************************************************************************
//Enqueue Scripts that need to be always present
//*************************************************************************************************
function tg_scripts()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('theme-custom-scripts', get_template_directory_uri() . '/static/js/scripts.js', array(), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tg_scripts');
