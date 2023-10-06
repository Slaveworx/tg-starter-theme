<?php

namespace TG;
//****************************************                                      
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
//****************************************
// COMPONENT: welcome
//****************************************
?>

<!-- ******************************** -->
<!-- welcome component -->
<!-- ******************************** -->

<?php
//****************************************
//                PROPS                  *
//****************************************
// Define your component props here
// ### Example: $message = $message ?? "No message was passed to the component";
?>


<section class="welcome" data-row-index="<?php echo $row_index; ?>">
    <div class="container">
        <img class="welcome__monogram" src="<?php echo get_template_directory_uri() . '/static/img/monogram.svg'; ?>" alt="TG Starter Theme Logo">
        <h1 class="welcome__title">Start Creating with TG Starter Theme</h1>
    </div>
</section>