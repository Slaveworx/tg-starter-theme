<?php

namespace TG;

//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0                            

//****************************************
// COMPONENT: navbar
//****************************************
?>



<!-- ******************************** -->
<!-- navbar component -->
<!-- ******************************** -->

<header id="masthead" class="site-header" data-row-index="<?php echo $row_index; ?>">

    <div class="site-branding">

        <a href="<?php echo home_url("/"); ?>"><img class="logo" src="<?php echo get_template_directory_uri() . '/static/img/logo.png'; ?>" alt="TG Starter Theme Logo" width="48" height="48"></a>


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