<?php
namespace TG;

trait Templates{

    /** Change Default Archive Templates Directory*/
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

        /**
         * !ERROR : Right now, when a page has a page template set in the backend and it gets deleted, then if we navigate to the page it will show an error:
         * ! Warning: include(C:\Users\slave\Local Sites\tg\app\public/wp-content/themes/tg/template-pages/flexible-content/page-flexible-content.php): Failed to open stream: No such file or directory
         * ? TODO: Need to find a way to detect that deletion and fallback to default page template...
         */

        if (is_page()) {
            if (is_page()) {
                $pagename = get_query_var('pagename');
                $page_template_slug = get_page_template_slug();

                //load minified scripts for the custom page template
                if ($page_template_slug) {
                    $slug = dirname($page_template_slug);
                    $page_template_name = pathinfo(basename($page_template_slug), PATHINFO_FILENAME);

                    // Load the JavaScript file if it exists
                    $js_file = sprintf('%s/template-pages/%s/%s.js', get_template_directory(), $slug, $page_template_name);
                    $js_file_to_enqueue = sprintf('%s/static/js/template-pages/%s/%s.js', get_template_directory_uri(), $slug, $page_template_name);
                    if (file_exists($js_file)) {
                        wp_enqueue_script($page_template_name . "-min-page-template", $js_file_to_enqueue, array('jquery'), _S_VERSION, true);
                    }
                }

                if (empty($page_template_slug)) {
                    $template_filenames = array(
                        "{$pagename}.php",
                        "page-{$pagename}.php",
                        "page-{$pagename}",
                        'page.php',
                    );
                    foreach ($template_filenames as $filename) {
                        $subdirectories = scandir(get_stylesheet_directory() . '/template-pages');
                        foreach ($subdirectories as $subdirectory) {
                            if ('.' !== $subdirectory && '..' !== $subdirectory) {
                                $page_template = get_stylesheet_directory() . '/template-pages/' . $subdirectory . '/' . $filename;
                                if (file_exists($page_template)) {
                                    $template = $page_template;
                                    break 2;
                                }
                            }
                        }
                    }
                } else {
                    $template = get_stylesheet_directory() . '/template-pages/' . $page_template_slug;
                }
            }
            return $template;
        }
    }


    /** Make Wordpress look for page templates in the custom folder */
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