<?php

namespace TG;
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
//****************************************
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package tg
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container-medium">
		<section class="error-404 not-found">
			<header class="page-header">
				<?php TG::img("logo.png", "404 Image", ["page-header__image"]); ?>

				<h1 class="page-header__title">
					<?php esc_html_e('Oops! That page can&rsquo;t be found.', THEME_TEXT_DOMAIN); ?>
				</h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p class="page-content__text">
					<?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', THEME_TEXT_DOMAIN); ?>
				</p>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->
	</div>
</main><!-- #main -->

<?php
get_footer();
