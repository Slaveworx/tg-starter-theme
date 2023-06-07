<?php
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
//****************************************
namespace TG;

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package tg
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php if (!empty(get_bloginfo('description'))) : echo get_bloginfo('description');
										else : echo get_bloginfo('name');
										endif; ?>">
	<meta name="keywords" content="starter theme, wordpress, tg starter theme">
	<link rel="canonical" href="<?php echo get_permalink(); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<?php echo TG::generate_preload_links(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	
	<div id="page" class="site">
		<?php TG::load_component("navbar"); ?>
		<a class="skip-link screen-reader-text" href="#primary">
			<?php esc_html_e('Skip to content', THEME_TEXT_DOMAIN); ?>
		</a>