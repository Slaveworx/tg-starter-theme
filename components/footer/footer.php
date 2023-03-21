<?php
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0                            
                                        
//****************************************
// COMPONENT: footer
//****************************************
?>



<!-- ******************************** -->
<!-- footer component -->
<!-- ******************************** -->

<footer id="colophon" class="site-footer" data-row-index="<?php echo $row_index;?>">
    <div class="site-info">
        <a href="<?php echo esc_url(__('https://tiagogalvao.com', THEME_TEXT_DOMAIN)); ?>">
            <?php
            /* translators: %s: CMS name, i.e. WordPress. */
            printf(esc_html__('© %s %s', THEME_TEXT_DOMAIN), date("Y"), 'Tiago M. Galvão');
            ?>
        </a>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

