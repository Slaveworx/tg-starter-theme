<?php

namespace TG;
//****************************************
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                                     
//****************************************
// COMPONENT: text-left-img-right
//****************************************
?>

<!-- ******************************** -->
<!-- text-left-img-right component -->
<!-- ******************************** -->

<section class="text-left-img-right" data-row-index="<?php echo $row_index; ?>">
    <div class="grid grid-auto-lg">

        <div class="left-block">
            <div class="wrapper">
                <h2 class="left-block__title">Lorem Ipsum Title Left</h2>
                <p class="left-block__description">Fatback short ribs ex, prosciutto landjaeger filet mignon buffalo ut pork belly biltong turducken. Strip steak id esse prosciutto consectetur. Shankle excepteur anim beef bresaola labore in ipsum, cupim pork chop buffalo jowl. Turducken mollit sed, laborum pancetta elit proident leberkas brisket duis pariatur prosciutto aute sunt. Do esse velit enim veniam. Ball tip officia strip steak short ribs occaecat ex.</p>
            </div>

        </div>

        <div class="right-block">
            <div class="wrapper">
                <?php TG::img("img2.webp", "Just a dummy image to serve as example", ["right-block__image"]); ?>
            </div>

        </div>

    </div>
</section>