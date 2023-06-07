<?php

namespace TG;
TG::use_dependency("swiper");

//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0                            

//****************************************
// COMPONENT: slider-full-width
//****************************************
?>


<!-- ******************************** -->
<!-- slider-full-width component -->
<!-- ******************************** -->

<section class="slider-full-width" data-row-index="<?php echo $row_index;?>">


    <!-- Swiper -->
    <div id="slider-full-width" class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><?php TG::img("slider.jpeg", "An example red image inside a slider which has the word slider in it"); ?></div>
            <div class="swiper-slide"><?php TG::img("slider.jpeg", "An example red image inside a slider which has the word slider in it"); ?></div>
            <div class="swiper-slide"><?php TG::img("slider.jpeg", "An example red image inside a slider which has the word slider in it"); ?></div>
            <div class="swiper-slide"><?php TG::img("slider.jpeg", "An example red image inside a slider which has the word slider in it"); ?></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>


</section>