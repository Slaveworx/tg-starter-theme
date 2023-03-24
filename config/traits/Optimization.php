<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file contains all Optimizations           

//****************************************

namespace TG;

trait Optimization
{

    //************************************ */
    // Static Assets Optimization
    //************************************ */

    /**
     * Dequeue blocking scripts and re-enqueue them with the 'defer' attribute.
     *
     * This function iterates through the PLUGIN_SCRIPTS array, checks if each script is enqueued,
     * dequeues the script, and then re-enqueues it with the 'defer' attribute by setting the fifth
     * parameter of wp_enqueue_script() to true. This optimizes the loading of the script files
     * by allowing the browser to load the scripts asynchronously, thus improving the page load speed.
     */
    public function dequeue_blocking_scripts()
    {
        foreach (PLUGIN_SCRIPTS as $handle) {
            if (wp_script_is($handle, 'enqueued')) {
                wp_dequeue_script($handle);
                wp_enqueue_script($handle, wp_scripts()->registered[$handle]->src, array(), wp_scripts()->registered[$handle]->ver, true);
            }
        }
    }

    /**
     * Dequeue blocking styles and re-enqueue them with the 'print' media type.
     *
     * This function iterates through the PLUGIN_STYLES array, checks if each style is enqueued,
     * dequeues the style, and then re-enqueues it with the 'print' media type by setting the fifth
     * parameter of wp_enqueue_style() to 'print'. This method is used to optimize the loading of
     * the stylesheets by initially setting them to a non-blocking media type, which can later be
     * changed to 'all' using JavaScript.
     */
    public function dequeue_blocking_styles()
    {
        foreach (PLUGIN_STYLES as $handle) {
            if (wp_style_is($handle, 'enqueued')) {
                wp_dequeue_style($handle);
                wp_enqueue_style($handle, wp_styles()->registered[$handle]->src, array(), wp_styles()->registered[$handle]->ver, 'print');
            }
        }
    }

    /**
     * Add the 'defer' attribute to the script tags of enqueued PLUGIN_SCRIPTS.
     *
     * This function checks if the given script handle is part of the PLUGIN_SCRIPTS array.
     * If it is, the 'defer' attribute is added to the script tag, which allows the browser
     * to load the script asynchronously and in a non-blocking manner, improving the page load speed.
     *
     * @param string $tag    The complete script tag for the enqueued script.
     * @param string $handle The script's registered handle.
     * @param string $src    The source URL of the enqueued script.
     * @return string        The modified script tag with the 'defer' attribute added.
     */
    public function add_defer_attribute($tag, $handle, $src)
    {
        if (in_array($handle, PLUGIN_SCRIPTS)) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }


    /**
     * Add the 'onload' attribute to the style tags of enqueued PLUGIN_STYLES.
     *
     * This function checks if the given style handle is part of the PLUGIN_STYLES array.
     * If it is, and the 'media' attribute is not set to 'print', the 'media' attribute
     * is changed to 'print' and the 'onload' attribute is added. If the 'media' attribute
     * is already set to 'print', only the 'onload' attribute is added. The 'onload' attribute
     * changes the 'media' attribute back to 'all' once the stylesheet has loaded, ensuring
     * that the stylesheet is loaded in a non-blocking manner, improving page load speed.
     *
     * @param string $html   The complete style tag for the enqueued style.
     * @param string $handle The style's registered handle.
     * @param string $href   The source URL of the enqueued style.
     * @param string $media  The media attribute value for the enqueued style.
     * @return string        The modified style tag with the 'media' and 'onload' attributes added or updated.
     */
    public function add_style_onload_attribute($html, $handle, $href, $media)
    {
        if (in_array($handle, PLUGIN_STYLES)) {
            // Check if media="print" or media='print' is present in the $html string
            $media_print_present = strpos($html, 'media="print"') !== false || strpos($html, "media='print'") !== false;

            // If media="print" or media='print' is not present, add media="print" and onload attribute
            if (!$media_print_present) {
                $html = preg_replace('/(media="[^"]*")/i', 'media="print" onload="this.media=\'all\'"', $html);
                $html = preg_replace("/(media='[^']*')/i", "media='print' onload=\"this.media='all'\"", $html);
            } else {
                // If media="print" or media='print' is present, only add the onload attribute
                $html = str_replace('media="print"', 'media="print" onload="this.media=\'all\'"', $html);
                $html = str_replace("media='print'", "media='print' onload=\"this.media='all'\"", $html);
            }
        }
        return $html;
    }



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
     * Generates preload link tags for font files in the theme's fonts directory.
     *
     * This function scans the theme's "static/fonts" directory for font files with extensions
     * woff, woff2, ttf, otf, and eot. It then generates preload link tags for each font file,
     * which can be inserted into the HTML head to improve font-loading performance.
     *
     * @return string A string containing the generated preload link tags, separated by newlines.
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

    //************************************ */
    // Cache
    //************************************ */

    /**
     * Adds or removes custom cache settings in the .htaccess file based on the USE_CACHE constant.
     *
     * This function checks if the custom cache settings for the TG Starter Theme have been added to the .htaccess file.
     * If the USE_CACHE constant is set to true and the custom cache settings have not been added, it adds the settings.
     * If the USE_CACHE constant is set to false and the custom cache settings are present, it removes the settings.
     */
    public static function tg_custom_cache_mechanism()
    {
        // Check if the rules have already been added
        $htaccess_file = ABSPATH . '.htaccess';
        $htaccess_contents = file_get_contents($htaccess_file);

        if (USE_CACHE && strpos($htaccess_contents, '# Begin TG Starter Theme Cache Settings') === false) {
            // Add the custom rules to the .htaccess file
            $new_rules = "\n# Begin TG Starter Theme Cache Settings\n";
            $new_rules .= "<IfModule mod_expires.c>\n";
            $new_rules .= "  ExpiresActive On\n";
            $new_rules .= "  ExpiresDefault \"access plus 1 year\"\n";
            $new_rules .= "  ExpiresByType image/jpeg \"access plus 1 year\"\n";
            $new_rules .= "  ExpiresByType image/png \"access plus 1 year\"\n";
            $new_rules .= "  ExpiresByType image/gif \"access plus 1 year\"\n";
            $new_rules .= "  ExpiresByType image/svg+xml \"access plus 1 year\"\n";
            $new_rules .= "  ExpiresByType image/webp \"access plus  1 year\"\n";
            $new_rules .= " ExpiresByType image/x-icon \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType text/css \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType text/javascript \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType application/javascript \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType application/x-javascript \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType font/ttf \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType font/otf \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType application/font-woff \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType application/font-woff2 \"access plus 1 year\"\n";
            $new_rules .= " ExpiresByType application/vnd.ms-fontobject \"access plus 1 year\"\n";
            $new_rules .= "</IfModule>\n\n";
            $new_rules .= "<IfModule mod_headers.c>\n";
            $new_rules .= " <FilesMatch \"\.(ico|css|js|gif|jpe?g|png|svg|webp|woff2?|ttf|otf)$\">\n";
            $new_rules .= " Header set Cache-Control \"public, max-age=31536000\"\n";
            $new_rules .= " </FilesMatch>\n";
            $new_rules .= "</IfModule>\n\n";
            $new_rules .= "<IfModule mod_deflate.c>\n";
            $new_rules .= " AddOutputFilterByType DEFLATE text/plain\n";
            $new_rules .= " AddOutputFilterByType DEFLATE text/html\n";
            $new_rules .= " AddOutputFilterByType DEFLATE text/xml\n";
            $new_rules .= " AddOutputFilterByType DEFLATE text/css\n";
            $new_rules .= " AddOutputFilterByType DEFLATE application/xml\n";
            $new_rules .= " AddOutputFilterByType DEFLATE application/xhtml+xml\n";
            $new_rules .= " AddOutputFilterByType DEFLATE application/rss+xml\n";
            $new_rules .= " AddOutputFilterByType DEFLATE application/javascript\n";
            $new_rules .= " AddOutputFilterByType DEFLATE application/x-javascript\n";
            $new_rules .= "</IfModule>\n";
            $new_rules .= "# End TG Starter Theme Cache Settings\n\n";
            file_put_contents($htaccess_file, $new_rules, FILE_APPEND | LOCK_EX);
        } elseif (!USE_CACHE && strpos($htaccess_contents, '# Begin TG Starter Theme Cache Settings') !== false) {
            // Remove the custom rules from the .htaccess file
            $htaccess_contents = preg_replace('/# Begin TG Starter Theme Cache Settings.*?# End TG Starter Theme Cache Settings\n\n/s', '', $htaccess_contents);
            file_put_contents($htaccess_file, $htaccess_contents, LOCK_EX);
        }
    }
}
