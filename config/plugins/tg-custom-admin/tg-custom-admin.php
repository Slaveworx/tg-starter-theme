<?php

/**
 * Custom TG Start Theme WordPress Admin Customiser.
 *
 * Allows to customise the admin panel colors straight from the backend.
 * Author: Tiago M. Galvão
 * 2023
 */

//**************************************************************/
// IMPORTS
//***************************************************************/
require_once 'fields.php';
require_once 'default-colors.php';

if (!class_exists('TGAdminCustomiser')) {
    class TGAdminCustomiser
    {

        private $options;
        private $sections;
        private $default_colors;
        private $fields;


        public function __construct($default_colors, $fields)
        {
            $this->fields = $fields;
            $this->default_colors = $default_colors;
            $this->actions_and_filters_registration();
        }


        public function actions_and_filters_registration()
        {
            add_action('admin_init', array($this, 'initialize_page'));
            add_action('admin_init', array($this, 'reset_colors'));
            add_action('admin_head', array($this, 'insert_custom_colors_into_admin_head'));
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_menu', array($this, 'tg_custom_admin_menu'), 9999);
            add_action('admin_enqueue_scripts', array($this, 'enqueue_color_picker_scripts'));
            add_filter('admin_footer_text', array($this, 'change_footer_admin_text'));
            add_action('admin_bar_menu', array($this, 'add_custom_admin_logo'), 0);
        }


        public function add_custom_admin_logo()
        {
            $enable_customizer = get_option('enable_customizer');
            if ($enable_customizer) {
                global $wp_admin_bar;
                $wp_admin_bar->add_menu(array(
                    'id'    => 'custom-logo',
                    'meta'  => array(
                        'class' => 'custom-admin-logo',
                        'title' => __('Admin Panel'),
                        'html'  => '<img src="' . get_option('custom-logo') . '">'
                    ),
                ));
            }
        }

        public function reset_colors()
        {
            if (isset($_POST['reset_colors'])) {
                update_option('color_option_name', $this->default_colors);
                update_option('custom-logo', get_template_directory_uri() . '/config/sources/assets/img/logo.svg');
            }
        }


        public function add_plugin_page()
        {
            add_options_page(
                'TG Custom Admin',
                'TG Custom Admin',
                'manage_options',
                'tg-custom-admin',
                array($this, 'display_admin_page')
            );
        }


        public function display_admin_page()
        {
            $this->options = get_option('color_option_name');
            $this->print_admin_page_content();
        }


        private function print_admin_page_content()
        {
?>
            <div class="wrap">
                <h1 class="tg-custom-admin-title">TG Custom Admin Settings</h1>

                <!-- Navigation Buttons -->
                <div class="nav-buttons-wrapper">
                    <?php
                    foreach ($this->sections as $section_id => $fields) {
                        echo '<button class="tg-custom-admin-nav-btn" onclick="var elem = document.getElementById(\'' . $section_id . '\'); window.scrollTo({ top: elem.offsetTop + 150, behavior: \'smooth\' });">Go to ' . ucwords(str_replace('_', ' ', $section_id)) . '</button>';
                    }
                    ?>
                </div>

                <!-- End of Navigation Buttons -->

                <form method="post">
                    <input type="hidden" name="reset_colors" value="1">
                    <?php submit_button('Reset to Defaults'); ?>
                </form>
                <form id="tg--custom--admin" method="post" action="options.php">
                    <?php
                    submit_button();
                    settings_fields('color_option_group');
                    do_settings_sections('color-setting-admin');
                    submit_button();
                    ?>
                </form>
                <form method="post">
                    <input type="hidden" name="reset_colors" value="1">
                    <?php submit_button('Reset to Defaults'); ?>
                </form>

                <button class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">Back to top</button>
                <button class="back-to-top-bottom" onclick="window.scrollTo({top: document.documentElement.scrollHeight, behavior: 'smooth'});">Go To Bottom</button>
            </div>
        <?php
        }


        public function initialize_page()
        {

            //Enable or Disable Options
            add_settings_section(
                'general-section',
                'General Settings',
                null,
                'color-setting-admin'
            );

            add_settings_field(
                'enable_customizer',
                'Enable Customizer',
                array($this, 'enable_customizer_callback'),
                'color-setting-admin',
                'general-section'
            );

            register_setting('color_option_group', 'enable_customizer', 'intval');

            //Logo Options

            add_settings_section(
                'custom-logo-section',
                'Custom Logo',
                null,
                'color-setting-admin'
            );

            add_settings_field(
                'custom-logo',
                'Upload Custom Logo',
                array($this, 'custom_logo_callback'),
                'color-setting-admin',
                'custom-logo-section'
            );

            register_setting('color_option_group', 'custom-logo', array($this, 'sanitize_custom_logo'));



            //Color Options

            register_setting(
                'color_option_group',
                'color_option_name',
                array($this, 'sanitize')
            );

            // Create an array of sections from the fields
            foreach ($this->fields as $field) {
                $this->sections[$field['section']][] = $field;
            }

            // Create sections and fields
            foreach ($this->sections as $section_id => $fields) {
                $this->add_color_settings_section($section_id);
                foreach ($fields as $field) {
                    $this->add_color_settings_field($field, $section_id);
                }
            }


            // Custom Menu Settings
            add_settings_section(
                'custom-menu-section',
                'Menu Items',
                array($this, 'tg_custom_menu_section_callback'),
                'color-setting-admin' // use 'color-setting-admin' instead of 'custom-menu-settings'
            );

            // Get all admin menu items
            global $menu;
            foreach ($menu as $index => $item) {
                if ($item[0] !== '') {
                    add_settings_field(
                        'menu-item-' . $index,
                        $item[0],
                        array($this, 'tg_custom_menu_item_callback'),
                        'color-setting-admin', // use 'color-setting-admin' instead of 'custom-menu-settings'
                        'custom-menu-section',
                        array('index' => $index, 'item' => $item)
                    );
                    register_setting('color_option_group', 'menu-item-' . $index, 'intval'); // use 'color_option_group' instead of 'custom-menu-settings'
                }
            }
        }


        private function add_color_settings_section($section_id)
        {
            add_settings_section(
                $section_id,
                ucwords(str_replace('_', ' ', $section_id)),
                function () use ($section_id) {
                    $this->print_section_info($section_id);
                },
                'color-setting-admin'
            );
        }


        private function add_color_settings_field($field, $section_id)
        {
            add_settings_field(
                $field['id'],
                $field['name'],
                array($this, 'color_callback'),
                'color-setting-admin',
                $section_id,
                array('label_for' => $field['id'])
            );
        }


        public function enqueue_color_picker_scripts($hook_suffix)
        {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_add_inline_script('wp-color-picker', $this->get_color_picker_inline_script());
            wp_enqueue_media();
        }

        private function get_color_picker_inline_script()
        {
            ob_start();
        ?>
            jQuery(document).ready(function($) {
            $('.color-picker').wpColorPicker();
            });
        <?php
            return ob_get_clean();
        }


        public function sanitize($input)
        {
            $new_input = array();
            foreach ($this->fields as $field) {
                if (isset($input[$field['id']])) {
                    $new_input[$field['id']] = sanitize_text_field($input[$field['id']]);
                }
            }
            return $new_input;
        }

        public function sanitize_custom_logo($input)
        {
            return esc_url_raw($input);
        }


        public function print_section_info($section_id)
        {
            echo '<div id="' . $section_id . '">';
            if ($section_id === 'other_settings') {
                print 'Change other settings here:';
            } else {
                print 'Choose your colors:';
            }
            echo '</div>';
        }

        public function custom_logo_callback()
        {
            $custom_logo = get_option('custom-logo');
            echo '<input type="text" id="custom-logo" name="custom-logo" value="' . esc_url($custom_logo) . '" />';
            echo '<input id="upload_logo_button" type="button" class="button" value="Upload Logo" />';
            echo '<style>#custom-logo { margin-right: 10px; }</style>';
            echo '<script>
        jQuery(document).ready(function($){
            $("#upload_logo_button").click(function(e) {
                e.preventDefault();
                var image = wp.media({ 
                    title: "Upload Image",
                    multiple: false
                }).open()
                .on("select", function(e){
                    var uploaded_image = image.state().get("selection").first();
                    var image_url = uploaded_image.toJSON().url;
                    $("#custom-logo").val(image_url);
                });
            });
        });
    </script>';
        }

        public function color_callback($args)
        {
            $field_id = $args['label_for'];
            // If the field is 'footer_text', render a text field instead of a color picker
            if ($field_id === 'footer_text') {
                printf(
                    '<input type="text" id="%s" name="color_option_name[%s]" value="%s" />',
                    $field_id,
                    $field_id,
                    isset($this->options[$field_id]) ? esc_attr($this->options[$field_id]) : ''
                );
            } else {
                printf(
                    '<input type="text" id="%s" name="color_option_name[%s]" value="%s" class="color-picker" />',
                    $field_id,
                    $field_id,
                    isset($this->options[$field_id]) ? esc_attr($this->options[$field_id]) : ''
                );
            }
        }

        public function insert_custom_colors_into_admin_head()
        {
            $this->options = get_option('color_option_name', $this->default_colors);
            $this->print_custom_colors_css();
        }

        private function print_custom_colors_css()
        {
            echo '<style>';
            echo ':root {';
            foreach ($this->fields as $field) {
                if (isset($this->options[$field['id']])) {
                    echo '--tg--' . $field['id'] . ': ' . $this->options[$field['id']] . ';';
                }
            }
            echo '}';
            echo '</style>';
        }

        public function change_footer_admin_text()
        {
            $this->options = get_option('color_option_name', $this->default_colors);
            if (!empty($this->options['footer_text'])) {
                echo $this->options['footer_text'];
            } else {
                echo 'Thank you for creating with WordPress.';
            }
        }


        function tg_custom_menu_section_callback()
        {
            echo '<p>Select the menu items you want to hide:</p>';
        }


        function tg_custom_menu_item_callback($args)
        {
            $index = $args['index'];
            $option = get_option('menu-item-' . $index);
            $item = $args['item'];
        ?>
            <label for="menu-item-<?php echo $index; ?>">
                <input type="checkbox" id="menu-item-<?php echo $index; ?>" name="menu-item-<?php echo $index; ?>" value="1" <?php checked($option, 1); ?>>
                Hide
            </label>
<?php
        }

        function tg_custom_admin_menu()
        {
            $enable_customizer = get_option('enable_customizer');
            if ($enable_customizer) {
                // Get all admin menu items
                global $menu;
                foreach ($menu as $index => $item) {
                    if ($item[0] !== '') {
                        $option = get_option('menu-item-' . $index);
                        $hidden = ($option == 1) ? true : false;
                        if ($hidden) {
                            $menu[$index][4] .= ' hidden';
                            add_action('admin_print_styles', function () use ($index) {
                                echo '<style type="text/css">#adminmenu li.menu-top.menu-icon-' . $index . ' { display: none !important; }</style>';
                            });
                        }
                    }
                }
            }
        }

        public function enable_customizer_callback()
        {
            $enable_customizer = get_option('enable_customizer');
            echo '<label for="enable_customizer">';
            echo '<input type="checkbox" id="enable_customizer" name="enable_customizer" value="1" ' . checked($enable_customizer, 1, false) . ' />';
            echo 'Enable Customizer';
            echo '</label>';
        }
    }
}

if (is_admin()) :
    $tgAdminCustomiser = new TGAdminCustomiser($default_colors, $fields);
endif;
