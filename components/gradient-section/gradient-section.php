<?php
global $tg;
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0                            

//****************************************
// COMPONENT: gradient-section
//****************************************
?>



<!-- ******************************** -->
<!-- gradient-section component -->
<!-- ******************************** -->

<section class="gradient-section" data-row-index="<?php echo $row_index;?>">
    <div class="container-small">
        <h2 class="gradient-section__title">The button below will open a simple yet <strong>powerful</strong> modal!</h2>
        <p class="gradient-section__description">Modals are flexible, simple and easy to use in TG Starter Theme</p>
        <a href="#" id="open-modal" class="btn__primary gradient-section__cta">Open Modal</a>
        <?php $tg->load_component("modal"); ?>
    </div>
</section>