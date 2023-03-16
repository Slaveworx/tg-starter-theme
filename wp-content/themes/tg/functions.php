<?php

namespace TG;

/**
 * TG Theme Functions & Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package tg
 */


/**
 * Change Version with every theme release
 */
if (!defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

//### GENERAL CONFIGS ############################################
require_once(get_template_directory() . "/config/config.php"); //#
//################################################################

new TG();