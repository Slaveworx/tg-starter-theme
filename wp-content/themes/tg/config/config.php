<?php
namespace TG;
use TG\Templates;
use TG\Context;
use TG\Helpers;

require_once __DIR__.'/traits/Templates.php';
require_once __DIR__.'/traits/Context.php';
require_once __DIR__.'/traits/Helpers.php';

class TG
{
    use Templates;
    use Context;
    use Helpers;

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

        // Add Button to Clean Context Transient
        add_action('admin_bar_menu', array($this, 'add_cleanup_btn_to_admin_bar'), 999);

        // Add Cleanup Function to Clean Transient
        add_action('wp_ajax_clean_context_transient', array($this, 'clean_context_transient'));
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
}
