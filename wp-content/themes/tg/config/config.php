<?php

class TG
{
    private static $context_transient_name = "tg_transient_all_posts_cache_context";


    public function __construct()
    {
        //### STYLES ######################################################
        require_once(get_template_directory() . "/config/styles.php"); //##
        //#################################################################

        //### SCRIPTS #####################################################
        require_once(get_template_directory() . "/config/scripts.php"); //#
        //#################################################################

        //### HELPERS #####################################################
        require_once(get_template_directory() . "/config/helpers.php"); //#
        //#################################################################

        // Add theme Support
        add_action('after_setup_theme', array($this, 'theme_supports'));

        // Register Custom Post Types
        add_action('init', array($this, 'register_post_types'));

        //Register Custom Taxonomies
        add_action('init', array($this, 'register_taxonomies'));

        //Add All Post Types to Context
        add_action('init', array($this, 'add_all_posts_to_context'));

        //Change wordpress's logic of where to look for available page templates
        add_filter('theme_page_templates', array($this, 'modify_wp_default_page_templates_dir'));

        //Change default archive page templates directory
        add_filter('archive_template', array($this, 'tg_archive_templates_dir'));

        //Change default page templates directory
        add_filter('page_template', array($this, 'tg_page_templates_dir'));
    }



    /** Set Context Values */
    public static function set_context($key, $value)
    {
        // Save the updated context in the transient
        $transient_name = self::$context_transient_name;
        $context = get_transient($transient_name);
        if (!$context) {
            $context = array();
        }
        $context[$key] = $value;
        set_transient($transient_name, $context, HOUR_IN_SECONDS);
    }

    /** Get Context Values */
    public static function get_context($key = null)
    {
        // Get the context from the transient
        $transient_name = self::$context_transient_name;
        $context = get_transient($transient_name);

        // If no key is specified, return the entire context array
        if ($key === null) {
            global $ctx;
            $ctx = $context;
            return $context;
        }

        // If the context array exists and contains the specified key, return its value
        if (isset($context[$key])) {
            global $ctx;
            $ctx = $context[$key];
            return $context[$key];
        }

        // Otherwise, return an error message
        return "Error: Value does not exist inside the global context.";
    }

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

        if (is_page()) {
            if (is_page()) {
                $pagename = get_query_var('pagename');
                $page_template_slug = get_page_template_slug();
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




    /** Add All Post Types to Context */
    public static function add_all_posts_to_context()
    {
        // Check if the retrieved posts are stored in the cache.
        $cache_key = self::$context_transient_name;
        $posts = get_transient($cache_key);

        if (!$posts) {
            // If the posts are not stored in the cache, retrieve them and store them in the cache.
            $post_types = get_post_types(array('public' => true), 'names');
            $posts = array();
            foreach ($post_types as $post_type) {
                $query = new WP_Query(array(
                    'post_type' => $post_type,
                    'posts_per_page' => -1,
                ));
                if ($query->have_posts()) {
                    $posts[$post_type] = $query->posts;
                    // Update the context with the new post data
                    self::set_context($post_type, $query->posts);
                }
                wp_reset_postdata();
            }
            set_transient($cache_key, $posts, HOUR_IN_SECONDS);
        } else {
            // If the posts are stored in the cache, update the context with the cached data
            foreach ($posts as $post_type => $post_list) {
                self::set_context($post_type, $post_list);
            }
        }
    }


    /** Register Custom Post Types. */
    public function register_post_types()
    {
        require_once("custom-post-types.php");
    }
    /** Register Custom Taxonomies. */
    public function register_taxonomies()
    {
        require_once("custom-taxonomies.php");
    }

    public function theme_supports()
    {
        require_once("theme-support.php");
    }

    /**
     * Outputs an HTML img tag with the specified image source, CSS classes, and HTML attributes.
     *
     * @param string $name The name of the image file to use as the source attribute.
     * @param array $classes An array of CSS classes to apply to the img tag (optional).
     * @param array $attributes An array of HTML attributes to apply to the img tag (optional).
     * 
     * @return void This function does not return a value; it simply outputs HTML to the page.
     */
    public static function img($name, $classes = array(), $attributes = array())
    {
        $template_dir = get_template_directory_uri() . "/static/img/";
        $class_string = "";
        $attribute_string = "";

        if (sizeof($classes) > 0) :
            foreach ($classes as $class) :
                $class_string .= $class;
            endforeach;
        endif;

        if (sizeof($attributes) > 0) : foreach ($attributes as $attribute) :
                $attribute_string .= $attribute;
            endforeach;
        endif;

        echo sprintf('<img src="%s" %s %s/>', $template_dir . $name, 'class="' . $class_string . '"', $attribute_string);
    }

    /**
     * Outputs the SVG code for the specified SVG file in the theme's static/img directory.
     *
     * @param string $svg The filename of the SVG file to output, including the file extension.
     * @return void
     */
    public static function svg($svg)
    {
        $template_dir = get_template_directory_uri() . "/static/img/";


        $svg_file = file_get_contents($template_dir . $svg);

        $find_string = '<svg';
        $position = strpos($svg_file, $find_string);

        $svg_code = substr($svg_file, $position);

        echo $svg_code;
    }

    /**
     * Includes a template part file from a specific folder in the theme directory.
     *
     * @param string $slug The slug name for the generic template.
     */
    public static function load_component($slug)
    {

        $templates = array();

        // Add the custom folder path to the file path.
        $templates[] = sprintf('%s/components/%s/%s.php', get_template_directory(), $slug, $slug);

        // Allow 3rd party plugins or themes to override the templates with their own.
        $templates = apply_filters('load', $templates, $slug);

        // Get the template file and include it.
        foreach ($templates as $template) {
            if (file_exists($template)) {
                include $template;

                // Load the JavaScript file if it exists
                $js_file = sprintf('%s/components/%s/%s.js', get_template_directory(), $slug, $slug);
                $js_file_to_enqueue = sprintf('%s/static/js/components/%s/%s.js', get_template_directory_uri(), $slug, $slug);
                if (file_exists($js_file)) {
                    wp_enqueue_script($slug . "-min-component", $js_file_to_enqueue, array('jquery'), _S_VERSION, true);
                }
                return;
            }
        }
    }

    /**
     * Creates a new custom post type with the specified name and optional arguments.
     *
     * @param string $name The name of the custom post type.
     * @param array $args Optional. An array of arguments to customize the post type. See https://developer.wordpress.org/reference/functions/register_post_type/ for a list of available arguments.
     * @param array $labels Optional. An array of labels to customize the post type labels. See https://developer.wordpress.org/reference/functions/get_post_type_labels/ for a list of available labels.
     * @return void
     */
    public static function create_cpt($name, $args = array(), $labels = array())
    {
        $name_lc = strtolower($name);
        $name_uc = ucfirst($name);

        $default_labels = array(
            'name'               => $name_uc . 's',
            'singular_name'      => $name_uc,
            'menu_name'          => $name_uc . 's',
            'name_admin_bar'     => $name_uc,
            'add_new'            => 'Add New ' . $name_uc,
            'add_new_item'       => 'Add New ' . $name_uc,
            'new_item'           => 'New ' . $name_uc,
            'edit_item'          => 'Edit ' . $name_uc,
            'view_item'          => 'View ' . $name_uc,
            'all_items'          => 'All ' . $name_uc . 's',
            'search_items'       => 'Search ' . $name_uc . 's',
            'parent_item_colon'  => 'Parent ' . $name_uc . 's:',
            'not_found'          => 'No ' . $name_lc . 's found.',
            'not_found_in_trash' => 'No ' . $name_lc . 's found in Trash.',
        );
        $labels = wp_parse_args($labels, $default_labels);

        $default_args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => null,
            'query_var'          => true,
            'rewrite'            => array('slug' => $name_lc),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
        );
        $args = wp_parse_args($args, $default_args);

        register_post_type($name_lc, $args);
    }
}
