<?php
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
//****************************************
/**
 * The template for displaying Woocommerce Content
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package tg
 */

get_header();
?>
<!-- ******************************** -->
<!-- WOOCOMMERCE -->
<!-- ******************************** -->
<main id="primary" class="site-main woocommerce-theme-support">
	<div class="container-medium">
		<?php

		if (class_exists('WooCommerce')) {
			woocommerce_content();
		} ?>

	</div>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
