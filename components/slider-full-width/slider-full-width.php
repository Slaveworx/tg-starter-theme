<?php

namespace TG;

TG::use_dependency("swiper");

//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                                              
//****************************************
// COMPONENT: slider-full-width
//****************************************
?>


<!-- ******************************** -->
<!-- slider-full-width component -->
<!-- ******************************** -->

<section class="slider-full-width" data-row-index="<?php echo $row_index; ?>">


    <!-- Swiper -->
    <div id="slider-full-width" class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><?php TG::img("img1.webp", "An example red image inside a slider"); ?></div>
            <div class="swiper-slide"><?php TG::img("img2.webp", "An example red image inside a slider"); ?></div>
            <div class="swiper-slide"><?php TG::img("img3.webp", "An example red image inside a slider"); ?></div>
        </div>
        <div class="swiper-pagination"></div>
    </div>


</section>