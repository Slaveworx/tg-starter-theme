<?php
//****************************************

// 🆃🅶                                     
// Wᴏʀᴅᴘʀᴇss Sᴛᴀʀᴛᴇʀ Tʜᴇᴍᴇ                  
// @𝑣𝑒𝑟𝑠𝑖𝑜𝑛 1.0.0
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
        $this->load_config_files();

        $this->register_actions_and_filters();
    }

    private function load_config_files()
    {
        $config_files = [
            'styles',
            'scripts',
            'dependencies',
            'template-functions'
        ];

        foreach ($config_files as $file) {
            require_once get_template_directory() . "/config/{$file}.php";
        }
    }

    private function register_actions_and_filters()
    {
        $actions = [
            'init' => [
                'register_post_types',
                'register_taxonomies',
                'add_all_posts_to_context',
                'tg_custom_cache_mechanism',
                'jquery_remove'
            ],

            'admin_bar_menu' => ['add_cleanup_btn_to_admin_bar'],
            'wp_ajax_clean_context_transient' => ['clean_context_transient'],
            'login_enqueue_scripts' => ['custom_login_css'],
            'after_setup_theme' => ['add_theme_supports'],
            'wp_print_scripts' => ['dequeue_blocking_scripts'],
            'wp_print_styles' => ['dequeue_blocking_styles']
        ];

        $filters = [
            'style_loader_tag' => ['remove_type_attribute_and_trailing_slash', 'add_style_onload_attribute'],
            'script_loader_tag' => ['remove_type_attribute_and_trailing_slash', 'add_defer_attribute'],
            'theme_page_templates' => ['modify_wp_default_page_templates_dir'],
            'archive_template' => ['tg_archive_templates_dir'],
            'page_template' => ['tg_page_templates_dir'],
            'single_template' => ['tg_single_templates_dir']
        ];

        foreach ($actions as $hook => $methods) {
            foreach ($methods as $method) {
                if ($hook === 'dequeue_blocking_scripts' || $hook === 'dequeue_blocking_styles') :
                    add_filter($hook, array($this, $method), 100);
                else :
                    add_action($hook, array($this, $method));
                endif;
            }
        }

        foreach ($filters as $hook => $methods) {
            foreach ($methods as $method) {
                if ($hook === 'style_loader_tag') :
                    $method === 'add_style_onload_attribute' ? add_filter($hook, array($this, $method), 10, 4) : add_filter($hook, array($this, $method), 10, 2);
                elseif ($hook === 'script_loader_tag') :
                    $method === 'add_defer_attribute' ? add_filter($hook, array($this, $method), 10, 3) : add_filter($hook, array($this, $method), 10, 2);
                else :
                    add_filter($hook, array($this, $method));

                endif;
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

    /** Adds all theme supports */
    public function add_theme_supports()
    {
        require_once("theme-support.php");
    }

    /** Removes Jquery from WordPress Core */
    public function jquery_remove()
    {
        if (!is_admin()) {
            wp_deregister_script('jquery');
        }
    }
}
