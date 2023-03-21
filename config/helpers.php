<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
// * This file should be used to write your custom functions                            

//****************************************
/**
 * Remove auto P from Contact form 7
 */

add_filter('wpcf7_autop_or_not', '__return_false');

