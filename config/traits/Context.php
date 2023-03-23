<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
// * This file contains all things related to Context                  

//****************************************

namespace TG;

trait Context
{

    private static $context_transient_name = "tg_transient_all_posts_cache_context";
    public static $dependencies = array();


    //************************************ */
    // Context Setup
    //************************************ */

    /**
     * Sets the context for a given key and value.
     *
     * This function saves the updated context in the transient. If there's no context,
     * an empty array is created. Then, it sets the key and value in the context
     * and updates the transient with the new context.
     *
     * @param string $key   The key to store the value under.
     * @param mixed  $value The value to store in the context.
     *
     * @return void
     */

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

    /**
     * Retrieves the context for a specified key or the entire context array.
     *
     * This function gets the context from the transient. If no key is specified,
     * it returns the entire context array. If the context array exists and contains
     * the specified key, it returns the value associated with that key. If the key
     * is not found in the context array, an error message is returned.
     *
     * @param string|null $key Optional. The key to retrieve the value for. If not
     *                          provided, the entire context array is returned.
     *
     * @return mixed The value associated with the specified key or the entire
     *               context array, or an error message if the key is not found.
     */
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

    /**
     * Adds all posts to the context.
     *
     * This function checks if the retrieved posts are stored in the cache. If not, it retrieves
     * them and stores them in the cache. Then, it updates the context with the post data.
     * It handles all public post types and caches the results for an hour.
     *
     * @return void
     */
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


    //************************************ */
    // Cleanup Transients
    //************************************ */

    /**
     * Adds a cleanup button to the WordPress admin bar.
     *
     * This function creates a "Purge Context" button in the WordPress admin bar, allowing users to easily
     * clear context transients. The button is displayed on both desktop and mobile devices.
     *
     * @return void
     */
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

    /**
     * Cleans the context transient.
     *
     * This function deletes the context transient and redirects the user to the home page.
     * It checks the AJAX nonce before performing the operation for security purposes.
     *
     * @return void
     */
    public static function clean_context_transient()
    {
        check_ajax_referer('clean_context_transient');
        delete_transient(self::$context_transient_name);
        wp_redirect(home_url());
        exit();
    }
}
