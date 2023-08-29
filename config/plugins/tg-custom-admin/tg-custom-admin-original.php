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
        /**
         * Options array to store the values of our fields.
         */
        private $options;

        /**
         * Sections array to store the values of our sections.
         */
        private $sections;

        /**
         * Array to store TG Starter Theme Color Defaults
         */
        private $default_colors;

        /**
         * Fields array to define the fields we want to add.
         * Each field is an array with an 'id', 'name', and 'section'.
         * The 'section' parameter shall be snake case and the plugin will transform that to normal text
         */
        private $fields;

        /**
         * Constructor to hook our methods to WordPress.
         */
        public function __construct($default_colors, $fields)
        {

            $this->fields = $fields;
            $this->default_colors = $default_colors;
            $this->actions_and_filters_registration();
        }

        /**
         * All actions and filters to be executed
         */
        public function actions_and_filters_registration()
        {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'initialize_page'));
            add_action('admin_head', array($this, 'insert_custom_colors_into_admin_head'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_color_picker_scripts'));
            add_action('admin_init', array($this, 'reset_colors'));
            add_filter('admin_footer_text', array($this, 'change_footer_admin_text'));
            add_action('admin_menu', array($this, 'tg_custom_admin_menu'), 9999);
            add_action('admin_bar_menu', array($this, 'add_custom_admin_logo'), 0);
        }

        /**
         * Replace Custom Logo
         */
        public function add_custom_admin_logo()
        {

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

        /**
         * Method that resets all colors to default
         */
        public function reset_colors()
        {
            if (isset($_POST['reset_colors'])) {
                update_option('color_option_name', $this->default_colors);
                update_option('custom-logo', get_template_directory_uri() . '/config/sources/assets/img/logo.svg');
            }
        }

        /**
         * Method to add the plugin options page.
         */
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

        /**
         * Callback for the options page to display the form.
         */

        public function display_admin_page()
        {
            $this->options = get_option('color_option_name');
            $this->print_admin_page_content();
        }



        /**
         * Method to print the content of the options page.
         */
        private function print_admin_page_content()
        {
?>
            <div class="wrap">
                <h1 class="tg-custom-admin-title">TG Custom Admin Settings</h1>

                <!-- Navigation Buttons -->
                <div class="nav-buttons-wrapper">
                    <?php
                    foreach ($this->sections as $section_id => $fields) {
                        echo '<button class="tg-custom-admin-nav-btn" onclick="var elem = document.getElementById(\'' . $section_id . '\'); window.scrollTo({ top: elem.offsetTop - 150, behavior: \'smooth\' });">Go to ' . ucwords(str_replace('_', ' ', $section_id)) . '</button>';
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

            </div>
        <?php
        }

        /**
         * Method to initialize the options page by registering the settings and sections.
         */
        public function initialize_page()
        {
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
        }




        /**
         * Method to add a settings section.
         *
         * @param string $section_id The ID of the section.
         */
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

        /**
         * Method to add a settings field.
         *
         * @param array $field The field array.
         * @param string $section_id The ID of the section.
         */
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


        /**
         * Method to enqueue the color picker scripts.
         */
        public function enqueue_color_picker_scripts($hook_suffix)
        {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_add_inline_script('wp-color-picker', $this->get_color_picker_inline_script());
            wp_enqueue_media();
        }

        /**
         * Method to get the inline script for the color picker.
         *
         * @return string The inline script.
         */
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

        /**
         * Callback to sanitize the input values.
         *
         * @param array $input The input array.
         *
         * @return array The sanitized input array.
         */
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


        /**
         * Callback to sanitize the custom logo URL.
         */
        public function sanitize_custom_logo($input)
        {
            return esc_url_raw($input);
        }


        /**
         * Callback to print the section info.
         */
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


        /**
         * Callback to print the logo upload field.
         */
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

        /**
         * Callback to print the input fields.
         *
         * @param array $args The arguments array. The 'label_for' key contains our field ID.
         */
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


        /**
         * Method to insert the custom colors CSS into the admin head.
         */
        public function insert_custom_colors_into_admin_head()
        {
            $this->options = get_option('color_option_name', $this->default_colors);
            $this->print_custom_colors_css();
        }

        /**
         * Method to print the custom colors CSS.
         */
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

        /**
         * Change the footer text in the admin dashboard.
         */
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
}

if (is_admin()) :
    $tgAdminCustomiser = new TGAdminCustomiser($default_colors, $fields);
endif;
