<?php

namespace TG;
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0                            

//****************************************
// COMPONENT: text-right-img-left
//****************************************
?>



<!-- ******************************** -->
<!-- text-right-img-left component -->
<!-- ******************************** -->

<section class="text-right-img-left" data-row-index="<?php echo $row_index; ?>">
    <div class="grid grid-auto-lg">

        <div class="left-block">
            <div class="wrapper">
                <?php TG::img("dummy.jpeg", "Just a dummy image to serve as example", ["left-block__image"]); ?>
            </div>
        </div>

        <div class="right-block">
            <div class="wrapper">
                <h2 class="right-block__title">Lorem Ipsum Title Right</h2>
                <p class="right-block__description">Fatback short ribs ex, prosciutto landjaeger filet mignon buffalo ut pork belly biltong turducken. Strip steak id esse prosciutto consectetur. Shankle excepteur anim beef bresaola labore in ipsum, cupim pork chop buffalo jowl. Turducken mollit sed, laborum pancetta elit proident leberkas brisket duis pariatur prosciutto aute sunt. Do esse velit enim veniam. Ball tip officia strip steak short ribs occaecat ex.</p>
            </div>

        </div>

    </div>
</section>