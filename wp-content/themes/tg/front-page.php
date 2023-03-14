<?php

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
    <?php TG::load_component("faq-block"); ?>
    <?php TG::get_context(); ?>


    <?php
    foreach ($ctx['courses'] as $post) {
        setup_postdata($post); ?>
        <p><?php echo the_title(); ?></p>
    <?php  }

    ?>
</main><!-- #main -->

<?php
get_footer();
