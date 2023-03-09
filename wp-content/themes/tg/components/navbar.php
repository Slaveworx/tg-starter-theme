<?php

/************************************
 * 
 * * NAVBAR COMPONENT
 * ? version 1.0
 * ! styles -> src/components/navbar.scss
 * 
 ************************************/
?>

<!-- ########################### -->
<!-- ### COMPONENT - CONTENT ### -->
<!-- ########################### -->

<header id="masthead" class="site-header">

    <div class="site-branding">
        <?php
        TG::img("logo.png", ["logo"]);
        ?>

    </div><!-- .site-branding -->


    <nav id="site-navigation" class="main-navigation" data-visible="false">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'menu-1',
                'menu_id' => 'primary-menu',
            )
        );
        ?>
    </nav><!-- #site-navigation -->

    <div class="theme-burger-wrapper" aria-controls="site-navigation">
        <div class="burger-icon"></div>
    </div>


</header><!-- #masthead -->

<!-- ########################### -->
<!-- ### COMPONENT - SCRIPTS ### -->
<!-- ########################### -->
<script>
    jQuery(document).ready(($) => {
        const headerNav = $("#site-navigation");
        const navBurger = $(".theme-burger-wrapper");
        const navbar = $(".site-header");
        const body = $(document.body);



        navBurger.click(() => {
            const visibility = headerNav.attr("data-visible");

            if (visibility === "false") {
                //when clicked to open
                body.toggleClass("scroll-blocked");
                headerNav.attr("data-visible", "true");
                navBurger.toggleClass("close");
                navbar.addClass("menu-open");
            } else {
                body.toggleClass("scroll-blocked");
                headerNav.attr("data-visible", "false");
                navBurger.toggleClass("close");
                navbar.removeClass("menu-open");
            }
        });
    });
</script>