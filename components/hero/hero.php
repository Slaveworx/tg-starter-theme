<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0                            

//****************************************
// COMPONENT: hero
//****************************************
?>



<!-- ******************************** -->
<!-- hero component -->
<!-- ******************************** -->

<section class="hero" data-row-index="<?php echo $row_index; ?>">
    <div class="container-small">
        <h1 class="hero__title"><?php echo $title; ?></h1>
        <p class="hero__description"><?php echo $description; ?></p>
        <a href="<?php echo $btn_link; ?>" class="hero__cta btn__primary"><?php echo $btn_text; ?></a>
    </div>
</section>