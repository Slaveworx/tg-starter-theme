<?php

namespace TG;

trait Helpers
{
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
     * Returns an image URL
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

    /** Image Optimization */
    public static function optimize_image($image_url, $quality = 80)
    {
        $image_path = parse_url($image_url, PHP_URL_PATH);
        $image_abs_path = ABSPATH . $image_path;
        $supports_webp = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;

        if (file_exists($image_abs_path)) {
            $image_info = getimagesize($image_abs_path);
            $image_mime = $image_info['mime'];
            $image_type = strtolower(str_replace('image/', '', $image_mime));

            switch ($image_type) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($image_abs_path);
                    imagejpeg($image, $image_abs_path, $quality);
                    break;
                case 'png':
                    $image = imagecreatefrompng($image_abs_path);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, $image_abs_path, 9);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($image_abs_path);
                    imagegif($image, $image_abs_path);
                    break;
                case 'svg':
                    $svg_data = file_get_contents($image_abs_path);
                    $doc = new \DOMDocument();
                    $doc->loadXML($svg_data);
                    $xpath = new \DOMXPath($doc);
                    foreach ($xpath->query('//@*') as $attr) {
                        if ($attr->nodeName == 'class') {
                            $attr->parentNode->removeAttribute('class');
                        }
                    }
                    file_put_contents($image_abs_path, $doc->saveXML());
                    break;
                default:
                    return $image_url;
                    break;
            }

            // Convert to webP if supported
            if ($supports_webp && function_exists('imagewebp')) {
                $webp_path = ABSPATH . preg_replace('/\.(jpg|jpeg|png|gif|svg)$/i', '.webp', $image_path);
                switch ($image_type) {
                    case 'jpg':
                    case 'jpeg':
                        $image = imagecreatefromjpeg($image_abs_path);
                        imagewebp($image, $webp_path, $quality);
                        break;
                    case 'png':
                        $image = imagecreatefrompng($image_abs_path);
                        imagewebp($image, $webp_path, 9);
                        break;
                    case 'gif':
                        $image = imagecreatefromgif($image_abs_path);
                        imagewebp($image, $webp_path);
                        break;
                    case 'svg':
                        $svg_data = file_get_contents($image_abs_path);
                        $doc = new \DOMDocument();
                        $doc->loadXML($svg_data);
                        $xpath = new \DOMXPath($doc);
                        foreach ($xpath->query('//@*') as $attr) {
                            if ($attr->nodeName == 'class') {
                                $attr->parentNode->removeAttribute('class');
                            }
                        }
                        file_put_contents($webp_path, $doc->saveXML());
                        break;
                }
                if (file_exists($webp_path)) {
                    $image_url = preg_replace('/\.(jpg|jpeg|png|gif|svg)$/i', '.webp', $image_url);
                }
            }

            return $image_url;
        } else {
            return $image_url;
        }
    }

    /** Will enqueue the custom admin styles */ 
    public static function custom_login_css() {
        wp_enqueue_style('login-styles', get_template_directory_uri() . '/config/sources/assets/css/login-styles.css');
    }
    
}
