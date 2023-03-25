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

//**************************************************************/
// Theme Version
//***************************************************************/
/**
 * Change Version with every theme release
 */
if (!defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

//**************************************************************/
// Theme Text Domain
//***************************************************************/
/**
 * Define theme text domain
 */
if (!defined('THEME_TEXT_DOMAIN')) {
	define('THEME_TEXT_DOMAIN', "tg");
}

//**************************************************************/
// Built-in Cache System
//***************************************************************/
/**
 * Define if the theme should use efficient cache setting or not (might not work in all servers)
 */
if (!defined('USE_CACHE')) {
	define('USE_CACHE', true);
}

//**************************************************************/
// Static Assets Optimization - RENDER BLOCKING RESOURCES
//***************************************************************/
/**
 * List the Handles of any --->plugin scripts<---- that you want to optimize
 */
if (!defined('PLUGIN_SCRIPTS')) {
	define('PLUGIN_SCRIPTS', array(
		//Include the handlers for the scripts you want to optimize here
	));
}

/**
 * List the Handles of any --->plugin styles<---- that you want to optimize
 */
if (!defined('PLUGIN_STYLES')) {
	define('PLUGIN_STYLES', array(
		"classic-theme-styles",
		"woocommerce-general",
		"woocommerce-layout",
		"woocommerce-smallscreen",
		"jetpack_css"
	));
}

//**************************************************************/
// END Static Assets Optimization 
//***************************************************************/

//### GENERAL CONFIGS ############################################
require_once(get_template_directory() . "/config/config.php"); //#
//################################################################

new TG();
