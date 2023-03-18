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
<?php TG::get_context(); ?>

<main id="primary" class="site-main">
    <?php TG::load_component("hero"); ?>
    <?php TG::load_component("faq-block"); ?>
    <?php TG::load_component("slider-full-width"); ?>
    <?php TG::load_component("box"); ?>
</main><!-- #main -->

<?php
get_footer();
