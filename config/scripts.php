<?php
//****************************************
                                        
// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
// * This file should be used to enqueue your SCRIPTS                         
                                        
//****************************************

//*************************************************************************************************
//Enqueue Scripts that need to be always present
//*************************************************************************************************
function tg_scripts()
{
	wp_enqueue_script('theme-custom-scripts', get_template_directory_uri() . '/static/js/scripts.js', array(), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tg_scripts');
