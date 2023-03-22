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

    //************************************ */
    // HTML Validation
    //************************************ */

    /**
     * Removes the type attribute and trailing slash from a given HTML tag.
     * This function is often used as a filter for script and style tags to make the HTML5 code more concise.
     * @param string $tag The HTML tag to be processed.
     * @param string $handle The script or style handle, used by WordPress to identify the enqueued asset.
     * @return string The modified HTML tag without the type attribute and trailing slash.
     */

    function remove_type_attribute_and_trailing_slash($tag, $handle)
    {
        $tag = preg_replace("/type=['\"]text\/(javascript|css)['\"]\s*/", '', $tag);
        $tag = str_replace(' />', '>', $tag);
        return $tag;
    }

    //************************************ */
    // Font Management System
    //************************************ */

    /**
     * Generates preload link tags for all font files found in the static/fonts directory.
     * The generated links improve font loading performance by instructing the browser to download the fonts before they are requested.
     * @return string The concatenated preload link tags for all font files.
     */

    public static function generate_preload_links()
    {
        $preloadLinks = [];
        $fontsPath = get_template_directory() . "/static/fonts/";
        $fontFiles = glob($fontsPath . "*.{woff,woff2,ttf,otf,eot}", GLOB_BRACE);

        foreach ($fontFiles as $fontFile) {
            $fontFilename = basename($fontFile);
            $fontUrl = get_template_directory_uri() . "/static/fonts/" . $fontFilename;

            // Determine the font type based on the file extension
            $fontExtension = pathinfo($fontFile, PATHINFO_EXTENSION);

            switch ($fontExtension) {
                case 'woff':
                    $fontType = 'woff';
                    break;
                case 'woff2':
                    $fontType = 'woff2';
                    break;
                case 'ttf':
                    $fontType = 'truetype';
                    break;
                case 'otf':
                    $fontType = 'opentype';
                    break;
                case 'eot':
                    $fontType = 'embedded-opentype';
                    break;
                default:
                    $fontType = '';
            }

            // Generate the preload link tag
            $preloadLink = '<link rel="preload" as="font" href="' . $fontUrl . '" ' . 'crossorigin>';
            $preloadLinks[] = $preloadLink;
        }

        return implode("\n", $preloadLinks);
    }
}
