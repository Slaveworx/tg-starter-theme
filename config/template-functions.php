<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file should be used to write your custom functions                            

//****************************************


//************************************ */
// Remove auto P from Contact form 7
//************************************ */
add_filter('wpcf7_autop_or_not', '__return_false');



//************************************ */
// Remove WP Logo from admin bar
//************************************ */
function remove_wp_logo_admin_bar()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'remove_wp_logo_admin_bar');



//************************************ */
// Remove the Wordpress Link from the login page logo
//************************************ */
function remove_wordpress_link_on_login()
{
?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var logoLink = document.querySelector('#login h1 a');
            logoLink.removeAttribute('href');
        });
    </script>
<?php
}
add_action('login_enqueue_scripts', 'remove_wordpress_link_on_login');
