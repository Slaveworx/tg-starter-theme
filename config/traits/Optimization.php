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
    // Google Font Management System
    //************************************ */

    /**
     * Downloads Google Fonts locally, updates the CSS file with new local URLs, and stores the fonts in a local directory.
     * @param string $handler A unique identifier for the font used as a prefix in the generated filenames.
     * @param string $googleFontsLink The URL to the Google Fonts stylesheet containing the desired fonts.
     */

    public static function use_google_font($handler, $googleFontsLink)
    {
        // Check if the ./static/fonts/ directory contains any files with the provided handler
        $fontsDir = get_template_directory() . "/static/fonts/";
        $fontFiles = glob($fontsDir . $handler . "-*.{woff,woff2,ttf,otf,eot}", GLOB_BRACE);

        // If there are files with the handler in the directory, return without downloading the fonts again
        if (!empty($fontFiles)) {
            return;
        }

        //else download and prepare fonts

        $cssContent = file_get_contents($googleFontsLink);

        preg_match_all("/(@font-face\s*\{[^\}]*\})/", $cssContent, $fontFaceMatches);
        preg_match_all("/url\((.*?)\)/", $cssContent, $urlMatches);
        $fontFaceBlocks = $fontFaceMatches[1];
        $fontUrls = $urlMatches[1];

        $updatedFontFaces = [];

        foreach ($fontUrls as $key => $fontUrl) {
            $fontData = file_get_contents($fontUrl);

            // Get the font extension (e.g. woff2)
            $fontExtension = pathinfo(parse_url($fontUrl, PHP_URL_PATH), PATHINFO_EXTENSION);

            // Create a unique font name with the handler as a prefix
            $fontFilename = $handler . "-font-" . ($key + 1) . "." . $fontExtension;

            // Save the font in the "./assets/fonts" directory
            file_put_contents($fontsDir . $fontFilename, $fontData);

            // Replace the original URL in the @font-face block with the new local URL
            $updatedFontFace = str_replace($fontUrl, "../fonts/" . $fontFilename, $fontFaceBlocks[$key]);
            $updatedFontFaces[] = $updatedFontFace;
        }

        // Append the updated @font-face statements to the "./assets/css/fonts.css" file
        $fontsCssPath = get_template_directory() . "/static/css/fonts.css";
        $existingCssContent = file_exists($fontsCssPath) ? file_get_contents($fontsCssPath) : "";
        $updatedCssContent = $existingCssContent . implode("\n", $updatedFontFaces);
        file_put_contents($fontsCssPath, $updatedCssContent);
    }

    /**
     * Watches the styles.php file for imported Google Fonts, updates the handlers list, and stores the handlers in a text file.
     */
    public static function watch_for_imported_google_fonts()
    {
        $stylesFilePath = get_template_directory() . "/config/styles.php";
        $stylesFileContent = file_get_contents($stylesFilePath);

        $handlersFile = get_template_directory() . "/config/sources/assets/optimized-font-handlers.txt";
        $existingHandlers = file_exists($handlersFile) ? file_get_contents($handlersFile) : "";
        $handlersArray = $existingHandlers ? explode(",", $existingHandlers) : [];

        $updatedHandlersArray = [];

        // Add a regular expression pattern to match the use_google_font() function calls
        $pattern = '/TG\\\TG::use_google_font\("(.*?)",/';

        if (preg_match_all($pattern, $stylesFileContent, $matches)) {
            // Loop through the matched handlers and add them to the updatedHandlersArray
            foreach ($matches[1] as $handler) {
                $updatedHandlersArray[] = $handler;
            }
        }

        // Save the updated handlers back to the file
        file_put_contents($handlersFile, implode(",", $updatedHandlersArray));
    }




    /**
     * Cleans up unused font files and their associated @font-face statements from the static/fonts and static/css/fonts.css directories.
     * Also updates the optimized-font-handlers.txt file to remove the handlers of the deleted fonts.
     */
    public static function clean_unused_fonts()
    {

        $handlersFile = get_template_directory() . "/config/sources/assets/optimized-font-handlers.txt";
        $existingHandlers = file_exists($handlersFile) ? file_get_contents($handlersFile) : "";
        $handlersArray = $existingHandlers ? explode(",", $existingHandlers) : [];

        $fontsDir = get_template_directory() . "/static/fonts/";
        $fontFiles = glob($fontsDir . "*.{woff,woff2,ttf,otf,eot}", GLOB_BRACE);

        foreach ($fontFiles as $fontFile) {
            $fontFilename = basename($fontFile);
            $handlerFound = false;

            foreach ($handlersArray as $handler) {
                if (strpos($fontFilename, $handler) === 0) {
                    $handlerFound = true;
                    break;
                }
            }

            if (!$handlerFound) {
                // Remove the unused font file
                unlink($fontFile);

                // Remove the associated @font-face statement from the fonts.css file
                $fontsCssPath = get_template_directory() . "/static/css/fonts.css";
                $cssContent = file_get_contents($fontsCssPath);
                $pattern = "/@font-face\s*\{[^}]+" . preg_quote($fontFilename) . "[^}]*\}/";
                $updatedCssContent = preg_replace($pattern, "", $cssContent);
                file_put_contents($fontsCssPath, $updatedCssContent);
            }
        }

        // Remove the handler from the optimized-font-handlers.txt file
        $updatedHandlersArray = array_filter($handlersArray, function ($handler) use ($fontFiles) {
            foreach ($fontFiles as $fontFile) {
                if (strpos(basename($fontFile), $handler) === 0) {
                    return true;
                }
            }
            return false;
        });

        // Save the updated handlers back to the file
        file_put_contents($handlersFile, implode(",", $updatedHandlersArray));
    }

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
