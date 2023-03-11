<?php
//#####################################
// COMPONENT: navbar
// @version 1.0
// @package tg
//#####################################
?>

<!-- ########################### -->
<!-- navbar -->
<!-- ########################### -->

<header id="masthead" class="site-header">

    <div class="site-branding">
        
        <a href="<?php echo home_url("/");?>"><?php TG::img("logo.png", ["logo"]);?></a>
        

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