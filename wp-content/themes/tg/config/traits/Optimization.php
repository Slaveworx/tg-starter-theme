<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
// * This file contains all Optimizations           

//****************************************

namespace TG;

trait Optimization
{
    // Remove type attribute and trailing slash from enqueued styles (Improves HTML score )

    function remove_type_attribute_and_trailing_slash($tag, $handle)
    {
        $tag = preg_replace("/type=['\"]text\/(javascript|css)['\"]\s*/", '', $tag);
        $tag = str_replace(' />', '>', $tag);
        return $tag;
    }
}
