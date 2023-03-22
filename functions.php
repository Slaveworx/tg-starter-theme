<?php

global $tg;

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

/**
 * Define theme text domain
 */
if (!defined('THEME_TEXT_DOMAIN')) {
	define('THEME_TEXT_DOMAIN', "tg");
}

/**
 * Define if the theme should use efficient cache setting or not
 */
if (!defined('USE_CACHE')) {
	define('USE_CACHE', true);
}

//### GENERAL CONFIGS ############################################
require_once(get_template_directory() . "/config/config.php"); //#
//################################################################

$tg = new TG\TG();
