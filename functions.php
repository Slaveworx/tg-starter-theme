<?php
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
//****************************************
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

/**
 * Define theme text domain
 */
if (!defined('THEME_TEXT_DOMAIN')) {
	define('THEME_TEXT_DOMAIN', "tg");
}

/**
 * Define if the theme should use efficient cache setting or not (might not work in all servers)
 */
if (!defined('USE_CACHE')) {
	define('USE_CACHE', true);
}

//### GENERAL CONFIGS ############################################
require_once(get_template_directory() . "/config/config.php"); //#
//################################################################

new TG();
