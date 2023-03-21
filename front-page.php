<?php

global $tg;

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
    <?php $tg->load_component("hero"); ?>
    <?php $tg->load_component("left-right-section"); ?>
    <?php $tg->load_component("faq-block"); ?>
    <?php $tg->load_component("slider-full-width"); ?>
    <?php $tg->load_component("text-right-img-left"); ?>
    <?php $tg->load_component("text-left-img-right"); ?>
    <?php $tg->load_component("gradient-section"); ?>
</main><!-- #main -->

<?php
get_footer();
