<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0
// * This file contains all Helper Functions           

//****************************************

namespace TG;

trait Helpers
{

    //************************************ */
    // Dependency Management
    //************************************ */
    public static $dependencies = array();

    /**
     * Registers a dependency with the given handle, style source, and script source.
     * 
     * @param string $handle The unique handle for the dependency.
     * @param string $style_src The URL of the stylesheet to register for the dependency.
     * @param string $script_src The URL of the script to register for the dependency.
     * @param array $script_args Optional. An array of arguments to pass to the wp_register_script() dependencies array.
     * @param bool $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>. Default 'true'.
     */
    public static function add_dependency($handle, $style_src = "", $script_src = "", $scripts_args = array(), $inFooter = true)
    {
        if (!empty($style_src)) {
            self::$dependencies[$handle]['style'] = $style_src;
        }

        if (!empty($script_src)) {
            self::$dependencies[$handle]['script'] = array(
                'src' => $script_src,
                'args' => $scripts_args,
                'in_footer' => $inFooter,
            );
        }
    }

    /**
     * Enqueues the styles and scripts for the given dependency.
     * 
     * @param string $handle The unique handle for the dependency.
     */
    public static function use_dependency($handle)
    {
        $dep = isset(self::$dependencies[$handle]) ? self::$dependencies[$handle] : false;
        if ($dep) {
            if (!empty($dep['style'])) {
                if (!empty($dep['style'])) {
                    wp_enqueue_style($handle, $dep['style'], array(), _S_VERSION);
                }
            }
            if (!empty($dep['script'])) {
                $script = $dep['script'];
                wp_enqueue_script($handle, $script['src'], $script['args'], _S_VERSION, $script['in_footer']);
            }
        }
    }

    //************************************ */
    // Images
    //************************************ */


    /**
     * Generates an optimised <img> tag for a WordPress image attachment.
     *
     * @param int $thumbnailID The ID of the image attachment.
     * @param bool $lazy Optional. Whether to load the image lazily using the `loading` attribute. Default false.
     * @param array $attrs Optional. An associative array of additional attributes to add to the <img> tag, including 'classes', 'id', and other general attributes.
     */
    public static function optimised_img($thumbnailID, $lazy = false, $attrs = array())
    {
        $src = wp_get_attachment_image_src($thumbnailID, 'full');
        $srcset = wp_get_attachment_image_srcset($thumbnailID, 'full');
        $sizes = wp_get_attachment_image_sizes($thumbnailID, 'full');
        $alt = get_post_meta($thumbnailID, '_wp_attachment_image_alt', true);

        // Get the image dimensions
        $image_data = wp_get_attachment_metadata($thumbnailID);
        $width = $image_data['width'];
        $height = $image_data['height'];

        $loading = $lazy ? 'lazy' : 'auto';

        // Build the attributes string
        $attributes_str = '';
        foreach ($attrs as $key => $value) {
            if ($key !== 'classes' && $key !== 'id') {
                $attributes_str .= $key . '="' . esc_attr($value) . '" ';
            }
        }

        // Build the class attribute string
        $class_str = '';
        if (isset($attrs['classes'])) {
            $class_str = 'class="' . esc_attr($attrs['classes']) . '"';
        }

        // Build the ID attribute string
        $id_str = '';
        if (isset($attrs['id'])) {
            $id_str = 'id="' . esc_attr($attrs['id']) . '"';
        }

        echo '<img src="' . esc_attr($src[0]) . '"
          srcset="' . esc_attr($srcset) . '"
          sizes="' . esc_attr($sizes) . '"
          width="' . esc_attr($width) . '"
          height="' . esc_attr($height) . '"
          alt="' . esc_attr($alt) . '"
          loading="' . esc_attr($loading) . '"
          ' . $class_str . '
          ' . $id_str . ' 
          ' . $attributes_str . '/>';
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
    public static function img($name, $alt = "An image", $classes = array(), $attributes = array())
    {
        $template_dir = get_template_directory_uri() . "/static/img/";
        $class_string = "";
        $attribute_string = "";

        if (sizeof($classes) > 0) :
            foreach ($classes as $class) :
                $class_string .= $class;
            endforeach;
        endif;

        if (strlen($class_string) > 0) :
            $class_string = 'class="' . $class_string . '"';
        endif;

        if (sizeof($attributes) > 0) :
            foreach ($attributes as $attribute) :
                $attribute_string .= $attribute;
            endforeach;
        endif;

        //for google page ranking purposes, automatically add explicit width and height attributes to image
        $image_size = getimagesize($template_dir . $name);

        echo sprintf('<img loading="lazy" src="%s" alt="%s" width="%s" height="%s" %s %s>', $template_dir . $name, $alt, $image_size[0], $image_size[1], $class_string, $attribute_string);
    }

    /**
     * Returns the URL for a static image file in the theme's "static/img" directory.
     * 
     * @param string $img The filename of the image to retrieve.
     * @return string The URL of the image file.
     */
    public static function img_url($img)
    {
        $template_dir = get_template_directory_uri() . "/static/img/";
        $path = $template_dir . $img;
        return $path;
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



    //************************************ */
    // Components
    //************************************ */


    /**
     * Includes a component from the Components folder in the theme directory.
     *
     * @param string $slug The slug name for the generic template.
     * @param array $props Optional. Additional variables to pass to the included component.
     */
    public static function load_component($slug, $props = array())
    {
        static $row_index = 0;

        // Increment the row index for each new component instance.
        $row_index++;

        $templates = array();

        // Add the custom folder path to the file path.
        $templates[] = sprintf('%s/components/%s/%s.php', get_template_directory(), $slug, $slug);

        // Allow 3rd party plugins or themes to override the templates with their own.
        $templates = apply_filters('load', $templates, $slug);

        // Get the template file and include it.
        foreach ($templates as $template) {
            if (file_exists($template)) {

                do_action('tg_before_' . $slug . '_component');

                // Extract the $props array as variables to pass them to the included template.
                if (!empty($props) && is_array($props)) {
                    extract($props, EXTR_SKIP);
                }

                // Pass the row index as a variable to the included template.
                $props['row_index'] = $row_index;

                include $template;

                // Load the JavaScript file if it exists
                $js_file = sprintf('%s/components/%s/%s.js', get_template_directory(), $slug, $slug);
                $js_file_to_enqueue = sprintf('%s/static/js/components/%s/%s.js', get_template_directory_uri(), $slug, $slug);
                if (file_exists($js_file)) {
                    wp_enqueue_script($slug . "-min-component", $js_file_to_enqueue, array(), _S_VERSION, true);
                }

                do_action('tg_after_' . $slug . '_component');

                return;
            }
        }
    }

    //************************************ */
    // Wordpress Related
    //************************************ */

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
            'name'                  => _x($name_uc . 's', 'Post Type General Name', THEME_TEXT_DOMAIN),
            'singular_name'         => _x($name_uc, 'Post Type Singular Name', THEME_TEXT_DOMAIN),
            'menu_name'             => __($name_uc . 's', THEME_TEXT_DOMAIN),
            'name_admin_bar'        => __($name_uc . 's', THEME_TEXT_DOMAIN),
            'archives'              => __('Item Archives', THEME_TEXT_DOMAIN),
            'attributes'            => __('Item Attributes', THEME_TEXT_DOMAIN),
            'parent_item_colon'     => __('Parent Item:', THEME_TEXT_DOMAIN),
            'all_items'             => __('All Items', THEME_TEXT_DOMAIN),
            'add_new_item'          => __('Add New Item', THEME_TEXT_DOMAIN),
            'add_new'               => __('Add New', THEME_TEXT_DOMAIN),
            'new_item'              => __('New Item', THEME_TEXT_DOMAIN),
            'edit_item'             => __('Edit Item', THEME_TEXT_DOMAIN),
            'update_item'           => __('Update Item', THEME_TEXT_DOMAIN),
            'view_item'             => __('View Item', THEME_TEXT_DOMAIN),
            'view_items'            => __('View Items', THEME_TEXT_DOMAIN),
            'search_items'          => __('Search Item', THEME_TEXT_DOMAIN),
            'not_found'             => __('Not found', THEME_TEXT_DOMAIN),
            'not_found_in_trash'    => __('Not found in Trash', THEME_TEXT_DOMAIN),
            'featured_image'        => __('Featured Image', THEME_TEXT_DOMAIN),
            'set_featured_image'    => __('Set featured image', THEME_TEXT_DOMAIN),
            'remove_featured_image' => __('Remove featured image', THEME_TEXT_DOMAIN),
            'use_featured_image'    => __('Use as featured image', THEME_TEXT_DOMAIN),
            'insert_into_item'      => __('Insert into item', THEME_TEXT_DOMAIN),
            'uploaded_to_this_item' => __('Uploaded to this item', THEME_TEXT_DOMAIN),
            'items_list'            => __('Items list', THEME_TEXT_DOMAIN),
            'items_list_navigation' => __('Items list navigation', THEME_TEXT_DOMAIN),
            'filter_items_list'     => __('Filter items list', THEME_TEXT_DOMAIN),
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


    //************************************ */
    // Misc
    //************************************ */


    /**
     * Array Dissector: Displays nested arrays with color-coded depth levels.
     * 
     * - Renders each depth with a unique color.
     * - Emphasizes main indices and adjusts text color for deep levels.
     * - Uses gradient for header.
     *
     * @param array $array The array to display.
     * @param int $depth (optional) The current nesting level, defaulting to 0.
     * @return void Outputs the styled array content.
     */

    public static function dissect($array, $depth = 0)
    {
        $colors = array('#09203f', '#537895', '#8da9c4', '#c5d4e2', '#e1e9f0', '#f3f7fa');
        $keyColors = array('#81D4FA', '#4FC3F7', '#01579B', '#0288D1', '#0277BD', '#01579B');
        $color = $colors[$depth % count($colors)];
        $keyColor = $keyColors[$depth % count($colors)];
        $textColor = ($depth >= count($colors) - 3) ? '#000' : '#fff';

        if ($depth == 0) {
            echo "<div style='margin-block: 30px;background: linear-gradient(to top, #bdc3c7, #2c3e50); color: #fff; padding: 30px; border:2px solid #000'>";
            echo "🆃🅶<br>";
            echo "Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ<br>";
            echo "𝑣𝑒𝑟𝑠𝑖𝑜𝑛 2.0.0<br>";
            echo "𝐴𝑟𝑟𝑎𝑦 𝐷𝑖𝑠𝑠𝑒𝑐𝑡𝑜𝑟 - 𝑇𝑖𝑎𝑔𝑜 𝑀. 𝐺𝑎𝑙𝑣𝑎̃𝑜 - © 2023<br><br>";
            echo "Items in Array: " . count($array) . " | ";

            if (empty($array)) {
                echo "The array is empty.<br>";
            } else {
                echo "The array is not empty.<br>";
            }

            echo "<hr style='border-color: #fff;'>";
        }

        echo '<pre style="padding: 30px;color: ' . $textColor . ';background-color: ' . $color . '; margin-left: ' . (20 * $depth) . 'px; white-space: pre-wrap;">';
        foreach ($array as $key => $value) {
            echo '<span style="display: inline-block;font-weight: bold; padding: 1px; margin-block: 4px; margin-right: 5px; background-color: rgba(0,0,0,0.04); font-style: italic; color: ' . $keyColor . '">' . $key . ' => </span>';
            if (is_array($value)) {
                echo '<br>';
                self::dissect($value, $depth + 1);
            } else {
                echo $value . '<br>';
            }
        }
        echo '</pre>';

        if ($depth == 0) {
            echo "</div>";
        }
    }


    /**
     * Enqueues custom CSS styles for the WordPress login page.
     *
     * This function enqueues a custom CSS stylesheet for the login page, allowing
     * for customization of the appearance of the login form.
     */
    public static function custom_login_css()
    {
        wp_enqueue_style('tg-login-styles', get_template_directory_uri() . '/config/sources/assets/css/login_styles.css');
    }

    /**
     * Enqueues custom CSS styles for the WordPress ADMIN page.
     *
     * This function enqueues a custom CSS stylesheet for the wp admin page, allowing
     * for customization of the appearance of the admin dashboard
     */
    public static function custom_admin_css()
    {
        $enable_customizer = get_option('enable_customizer');
        if ($enable_customizer) {
            wp_enqueue_style('tg-admin-styles', get_template_directory_uri() . '/config/sources/assets/css/admin_styles.css', array('acf-pro-input', 'acf-input'));
        }
    }
}
