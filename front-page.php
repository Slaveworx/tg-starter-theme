<?php

namespace TG;

/**
 * The template for displaying front page
 *
 * This is the template that displays the front page by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cubiqtemplate
 */

get_header();
?>




<main id="primary" class="site-main">
    <?php TG::load_component("hero", ['title' => 'This is TG Starter Theme', 'description' => 'A modern wordpress starter themes packed with amazing features to give you the freshest developer joy!', 'btn_link' => '#', 'btn_text' => 'Check out our features']); ?>
    <?php TG::load_component("left-right-section"); ?>
    <?php TG::load_component("faq-block"); ?>
    <?php TG::load_component("slider-full-width"); ?>
    <?php TG::load_component("text-right-img-left"); ?>
    <?php TG::load_component("text-left-img-right"); ?>
    <?php TG::load_component("gradient-section"); ?>
</main><!-- #main -->

<?php
get_footer();
