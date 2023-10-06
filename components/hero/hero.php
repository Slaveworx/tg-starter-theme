<?php
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
//****************************************
// COMPONENT: hero
//****************************************
?>

<!-- ******************************** -->
<!-- hero component -->
<!-- ******************************** -->

<?php 
//****************************************
//                PROPS                  *
//****************************************
// Define your component props here
$hero_title = $hero_title ?? "TG Starter Theme";
$hero_description = $hero_description ?? "No message was passed to the component";
$btn_link = $btn_link ?? "#";
$btn_text = $btn_text ?? "Get Started";
?>

<section class="hero" data-row-index="<?php echo $row_index; ?>">
    <div class="hero__overlay"></div>
    <div class="container-small">
        <h1 class="hero__title"><?php echo $hero_title; ?></h1>
        <p class="hero__description"><?php echo $hero_escription; ?></p>
        <a href="<?php echo $btn_link; ?>" class="hero__cta btn__primary"><?php echo $btn_text; ?></a>
    </div>
</section>