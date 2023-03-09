<header id="masthead" class="site-header">

    <div class="site-branding">
        <?php
        TG::img("logo.png", ["logo"]);
        ?>

    </div><!-- .site-branding -->


    <nav id="site-navigation" class="main-navigation">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'menu-1',
                'menu_id' => 'primary-menu',
            )
        );
        ?>
        <?php TG::img("burger.svg", true, ["theme-burger"]); ?>
    </nav><!-- #site-navigation -->

</header><!-- #masthead -->