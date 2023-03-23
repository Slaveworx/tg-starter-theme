<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file handles everything regarding templates             

//****************************************

namespace TG;

trait Templates
{

    /** Change the default archive templates directory.
     * @param string $template The path to the current archive template file.
     * @return string The path to the updated archive template file.
     */
    public function tg_archive_templates_dir($template)
    {
        if (is_archive()) {
            $post_type = get_query_var('post_type');
            $template_filenames = array(
                "archive-{$post_type}.php",
                'archive.php',
            );
            foreach ($template_filenames as $filename) {
                $subdirectories = scandir(get_stylesheet_directory() . '/template-archives'); // Replace 'archive-templates' with the name of the directory containing your subfolders
                foreach ($subdirectories as $subdirectory) {
                    if ('.' !== $subdirectory && '..' !== $subdirectory) {
                        $archive_template = get_stylesheet_directory() . '/template-archives/' . $subdirectory . '/' . $filename;
                        if (file_exists($archive_template)) {
                            $template = $archive_template;
                            $slug = basename(dirname($template));
                            $archive_template_name = pathinfo(basename($template), PATHINFO_FILENAME);
                            // Load the JavaScript file if it exists
                            $js_file = sprintf('%s/template-archives/%s/%s.js', get_template_directory(), $slug, $archive_template_name);
                            $js_file_to_enqueue = sprintf('%s/static/js/template-archives/%s/%s.js', get_template_directory_uri(), $slug, $archive_template_name);
                            if (file_exists($js_file)) {
                                wp_enqueue_script($archive_template_name . "-min-archive-template", $js_file_to_enqueue, array(), _S_VERSION, true);
                            }
                            break 2;
                        }
                    }
                }
            }
        }
        return $template;
    }


    /** Change Default Page Templates Directory */
    public function tg_page_templates_dir($template)
    {
        return $this->tg_templates_dir($template, 'page');
    }

    /** Change Default Singles Templates Directory */
    public function tg_single_templates_dir($template)
    {
        return $this->tg_templates_dir($template, 'single');
    }

    /**
     * Filters the path of the current template before including it.
     * Determines the appropriate template file for the current post or page,
     * and enqueues the corresponding JavaScript file if it exists. Supports
     * custom templates located in "template-singles" and "template-pages" directories.
     * @param string $template The path of the template to include.
     * @param string $type The type of template being loaded, either 'page' or 'single'.
     * @return string The modified template path.
     */
    private function tg_templates_dir($template, $type)
    {
        if (!is_singular() && !is_page()) {
            return $template;
        }

        $template_exists = null;
        $template_slug = get_page_template_slug();
        $post_type = get_post_type();

        //load minified scripts for the custom template
        if ($template_slug) {
            $slug = dirname($template_slug);
            $template_name = pathinfo(basename($template_slug), PATHINFO_FILENAME);

            // Load the JavaScript file if it exists
            $js_file = sprintf('%s/template-%ss/%s/%s.js', get_template_directory(), $type, $slug, $template_name);
            $js_file_to_enqueue = sprintf('%s/static/js/template-%ss/%s/%s.min.js', get_template_directory_uri(), $type, $slug, $template_name);
            if (file_exists($js_file)) {
                wp_enqueue_script($template_name . "-min-{$type}-template", $js_file_to_enqueue, array(), _S_VERSION, true);
            }
        }

        // Check if the current custom set template file exists
        if ($template_slug) {
            $path = get_template_directory() . "/template-{$type}s/" . $template_slug;
            $template_exists = file_exists($path);
            $template = $path;
        }

        if (empty($template_slug) || !$template_exists) {
            // If no custom template has been set or the current template file doesn't exist, look for a matching file in the "template-pages" or "template-singles" directory
            $template_filenames = array(
                "{$type}-{$post_type}.php",
                "{$type}.php",
                'index.php',
            );
            if (is_page()) {
                $pagename = get_query_var('pagename');
                $template_filenames = array_merge(array(
                    "{$pagename}.php",
                    "page-{$pagename}.php",
                    "page-{$pagename}",
                    'page.php',
                ), $template_filenames);
            }

            foreach ($template_filenames as $filename) {
                $subdirectories = scandir(get_stylesheet_directory() . "/template-{$type}s");
                foreach ($subdirectories as $subdirectory) {
                    if ('.' !== $subdirectory && '..' !== $subdirectory) {
                        $template_path = get_stylesheet_directory() . "/template-{$type}s/{$subdirectory}/{$filename}";
                        if (file_exists($template_path)) {

                            $template = $template_path;
                            $slug = basename(dirname($template_path));
                            $template_name = pathinfo(basename($template_path), PATHINFO_FILENAME);
                            // Load the JavaScript file if it exists
                            $js_file = sprintf('%s/template-%ss/%s/%s.js', get_template_directory(), $type, $slug, $template_name);
                            $js_file_to_enqueue = sprintf('%s/static/js/template-%ss/%s/%s.min.js', get_template_directory_uri(), $type, $slug, $template_name);

                            if (file_exists($js_file)) {
                                wp_enqueue_script($template_name . "-min-{$type}-template", $js_file_to_enqueue, array(), _S_VERSION, true);
                            }
                            break 2;
                        }
                    }
                }
            }
        }

        // If no matching template has been found, fall back to the default template
        if (!$template_exists && !file_exists($template)) {
            if (is_singular()) {
                $template = get_single_template();
            } elseif (is_page()) {
                $template = get_page_template();
            }
        }

        return $template;
    }


    /**
     * Filters the list of available page templates in the WordPress admin.
     * Modifies the list of available page templates by including custom templates
     * located in the 'template-pages' directory and its subdirectories.
     * @param array $page_templates An associative array of page templates in the format of 'template file' => 'Template Name'.
     * @return array The modified list of page templates.
     */
    function modify_wp_default_page_templates_dir($page_templates)
    {
        $template_dir = get_stylesheet_directory() . '/template-pages';
        $subdirectories = scandir($template_dir);
        foreach ($subdirectories as $subdirectory) {
            if ('.' !== $subdirectory && '..' !== $subdirectory) {
                $page_templates_subdirectory = array();
                $files = scandir($template_dir . '/' . $subdirectory);
                foreach ($files as $file) {
                    if ('.' !== $file && '..' !== $file && preg_match('|^([_a-zA-Z0-9-]+)\.php$|', $file, $matches)) {
                        if ($file === 'page.php') {
                            continue;
                        }
                        $template_file = $template_dir . '/' . $subdirectory . '/' . $file;
                        if ($file_data = get_file_data($template_file, array('Template Name'))) {
                            $template_name = str_replace(array('/*', '*/', '*'), '', $file_data[0]);
                        } else {
                            $template_name = str_replace(array('-', '_'), ' ', basename($file, '.php'));
                        }
                        $template_name = trim($template_name);
                        $page_templates_subdirectory[$subdirectory . '/' . $file] = $template_name;
                    }
                }
                $page_templates = array_merge($page_templates, $page_templates_subdirectory);
            }
        }
        return $page_templates;
    }
}
