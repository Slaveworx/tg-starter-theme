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

    public static function tg_custom_cache_mechanism()
    {
        // Set cache TTL to 1 year (31536000 seconds)
        $cache_ttl = 31536000;

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
