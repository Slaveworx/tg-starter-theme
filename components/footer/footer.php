<?php
//****************************************                                 
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                                         
//****************************************
// COMPONENT: footer
//****************************************
?>

<!-- ******************************** -->
<!-- footer component -->
<!-- ******************************** -->

<?php 
//****************************************
//                PROPS                  *
//****************************************
// Define your component props here
// ### Example: $message = $message ?? "No message was passed to the component";
?>

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

