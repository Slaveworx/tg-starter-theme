<?php

/************************************
 * 
 * * FOOTER COMPONENT
 * ? version 1.0
 * ! styles -> src/components/footer.scss
 * 
 ************************************/
?>

<!-- ########################### -->
<!-- ### COMPONENT - CONTENT ### -->
<!-- ########################### -->

<footer id="colophon" class="site-footer">
    <div class="site-info">
        <a href="<?php echo esc_url(__('https://tiagogalvao.com', 'tg')); ?>">
            <?php
            /* translators: %s: CMS name, i.e. WordPress. */
            printf(esc_html__('© %s %s', 'tg'), date("Y"), 'Tiago M. Galvão');
            ?>
        </a>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

<!-- ########################### -->
<!-- ### COMPONENT - SCRIPTS ### -->
<!-- ########################### -->
<script>
    jQuery(document).ready(($) => {

    });
</script>