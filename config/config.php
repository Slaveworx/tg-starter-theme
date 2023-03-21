<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0
// * This file contais all theme config                        

//****************************************

namespace TG;

use TG\Templates;
use TG\Context;
use TG\Helpers;
use TG\Optimization;

require_once __DIR__ . '/traits/Templates.php';
require_once __DIR__ . '/traits/Context.php';
require_once __DIR__ . '/traits/Helpers.php';
require_once __DIR__ . '/traits/Optimization.php';

class TG
{
    use Templates;
    use Context;
    use Helpers;
    use Optimization;

    public function __construct()
    {
        //### STYLES ######################################################
        require_once(get_template_directory() . "/config/styles.php"); //##
        //#################################################################

        //### SCRIPTS #####################################################
        require_once(get_template_directory() . "/config/scripts.php"); //#
        //#################################################################

        //### CONDITIONAL STYLES & SCRIPTS ################################
        require_once(get_template_directory() . "/config/dependencies.php");
        //#################################################################

        //### HELPERS #####################################################
        require_once(get_template_directory() . "/config/helpers.php"); //#
        //#################################################################

        // Enqueue the optimized fonts.css file
        add_action('wp_enqueue_scripts', array($this, 'enqueue_font_optimization_file'));

        //Changes Status of optimized font imports after loading
        add_action('wp_footer', array($this, 'change_optimized_font_imports_status'));

        // Dequeue Jquery
        add_action('init', array($this, 'jquery_remove'));

        //Remove type attribute and trailing slash from enqueued styles
        add_filter('style_loader_tag', array($this, 'remove_type_attribute_and_trailing_slash'), 10, 2);

        // Remove type attribute and trailing slash from enqueued scripts
        add_filter('script_loader_tag', array($this, 'remove_type_attribute_and_trailing_slash'), 10, 2);

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

        //Change default single templates directory
        add_filter('single_template', array($this, 'tg_single_templates_dir'));

        // Add Buttons to Clean Context Transient
        add_action('admin_bar_menu', array($this, 'add_cleanup_btn_to_admin_bar'), 999);

        // Add Cleanup Function to Clean Transient
        add_action('wp_ajax_clean_context_transient', array($this, 'clean_context_transient'));

        //Enqueue theme's custom admin login styles (to customize, change ./config/sources/assets/css/)login-styles.css)
        add_action('login_enqueue_scripts', array($this, 'custom_login_css'));
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

    /** Adds all theme supports */
    public function theme_supports()
    {
        require_once("theme-support.php");
    }


    /** Removes Jquery from wordpress Core */
    public function jquery_remove()
    {
        if (!is_admin()) {
            wp_deregister_script('jquery');
        }
    }
}
