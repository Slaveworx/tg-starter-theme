<?php

namespace TG;

trait Context
{

    private static $context_transient_name = "tg_transient_all_posts_cache_context";

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
                $query = new \WP_Query(array(
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


    /**
     * TRANSIENTS CLEAN UP
     */

    /** Add Button to admin bar to Purge Context Transient */
    public static function add_cleanup_btn_to_admin_bar()
    {
        global $wp_admin_bar;
        $args = array(
            'id' => 'transient-purge-button',
            'title' => '<div style="display:flex;align-items:center;color:tomato; font-weight:700;"><img src="' . get_template_directory_uri() . '/config/sources/assets/img/trash.svg" style="width:20px; height:20px; display:inline-block;">- PURGE CONTEXT -</div>',
            'href' => wp_nonce_url(admin_url('admin-ajax.php?action=clean_context_transient'), 'clean_context_transient'),
            'meta' => array(
                'class' => 'transient-purge-button',
                'title' => 'Purge Context',
            ),
        );
        // Add the button to the admin bar on desktop devices
        if (!wp_is_mobile()) {
            $wp_admin_bar->add_node($args);
        }
        // Add the button to the admin bar on mobile devices
        else {
            $wp_admin_bar->add_menu($args);
        }
    }

    /** The actual cleanup */
    public static function clean_context_transient()
    {
        check_ajax_referer('clean_context_transient');
        delete_transient(self::$context_transient_name);
        wp_redirect(home_url());
        exit();
    }
}
