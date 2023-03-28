<?php

/**
 * Plugin Name:       GoWish plugin
 * Plugin URI:        https://gowish.com/
 * Description:       GoWish plugin for WordPress
 * Version:           1.0.3
 * Requires at least: 5.4
 * Requires PHP:      7.4
 * Author:            GoWish
 * Text Domain:       gowish
 * Domain Path:       /languages
 */

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (!class_exists('gowish')) {

    class gowish
    {


        function __construct()
        {
            // Get settings
            // Retrieve the saved settings from the 'gowish_option_name' option
            $gowish_options = get_option('gowish_option_name');
            $gowish_select_default_hook = $gowish_options["select_default_hook"] ?? "";
            $gowish_custom_hook = $gowish_options["custom_hook"] ?? "";

            // Languages
            // Load the plugin's text domain for localization
            add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));

            // Load admin menu page
            // Add the plugin's menu and settings page in the WordPress admin area
            add_action('admin_menu', array($this, 'gowish_plugin_setup_menu'));
            add_action('admin_init', array($this, 'gowish_page_init'));

            // Hook function
            // If a custom hook is set, use it; otherwise, use the selected default hook
            if ($gowish_custom_hook != "") {
                // Use custom hook
                add_action($gowish_custom_hook, array($this, 'hook_function'));
            } else {
                // Use select hooks
                add_action($gowish_select_default_hook, array($this, 'hook_function'));
            }

            // Updates
            // Include the plugin-update-checker library for handling plugin updates
            require dirname(__FILE__) . '/plugin-update-checker/plugin-update-checker.php';

            // Create an update checker instance and configure it to check for updates on GitHub
            $myUpdateChecker = PucFactory::buildUpdateChecker(
                'https://github.com/websitecareio/wcio-gowish-button',
                __FILE__,
                'wcio-gowish-button'
            );

            // Set the branch that contains the stable release.
            $myUpdateChecker->setBranch('main');
        }



        function hook_function()
        {
            // Get settings
            // Retrieve the saved settings from the 'gowish_option_name' option
            $gowish_options = get_option('gowish_option_name');
            $buttonText = $gowish_options['buttontext'] ?? 'ADD TO GOWISH';

            // Include external CSS and JavaScript files
            echo '<link rel="stylesheet" href="https://oenskeinspiration.dk/knap/css/onskeskyen_wish_button.min.css">
            <script type="application/javascript" src="https://xn--nskeskyen-k8a.dk/onskeskyen-wish-button/external-wish-button.js" id="ov-onskeskyen-generate-wish-button-script" defer=""></script>';

            // Add custom CSS styling from the plugin settings
            echo "<style>" . $gowish_options['css_styling'] . "</style>";

            // Display the wish button according to the selected design
            if ($gowish_options["select_design"] == "design1") {
                // Design 1: Simple button
                echo '<button id="ov-onskeskyen-generated-wish-button" class="pill blue" type="button">' . $buttonText . '</button>';
            }

            if ($gowish_options["select_design"] == "design2") {
                // Design 2: Floating button with label
?>
                <div class="floating-lists fl-ready">
                    <ul class="fl-bar fl-right fl-connected fl-white-icon fl-white-icon-over fl-white-label-text fl-side-space fl-button-space fl-css-anim">
                        <li class="fl-item-1-1">
                            <div class="fl-icon">
                                <button id="ov-onskeskyen-generated-wish-button" class="pill blue" type="button" style="width: 40px;height: 40px; padding: 0;  text-align: center;background-position: 5px center;"></button>
                            </div>
                            <div class="fl-label" style=""><?php echo $buttonText; ?></div>
                        </li>
                    </ul>
                </div>
                <link rel="stylesheet" href="<?php echo plugins_url(basename(__DIR__)); ?>/frontend-styling.css" />
            <?php
            }

            if ($gowish_options["select_design"] == "design4") {
                // Design 4: Button without text
                echo '<button id="ov-onskeskyen-generated-wish-button" class="pill blue" type="button"></button>';
            }
        }


        // This function is responsible for loading the plugin's translation files
        function load_plugin_textdomain()
        {
            // Load the translation files from the specified languages directory
            load_plugin_textdomain('gowish', false, basename(__DIR__) . '/languages/');
        }

        // This function sets up the plugin's menu in the WordPress admin dashboard
        function gowish_plugin_setup_menu()
        {
            // Add a new menu page for the GoWish plugin
            add_menu_page(
                __('GoWish', 'gowish'), // The page title
                __('GoWish', 'gowish'), // The menu title
                'manage_options', // The capability required for the user to access the menu
                'gowish', // The unique menu slug
                array($this, 'gowish_init'), // The callback function to display the plugin's settings page
                plugins_url(basename(__DIR__) . '/images/menuicon.png') // The URL to the menu icon
            );
        }

        // This function initializes and displays the plugin's settings page
        function gowish_init()
        {
            // Retrieve the plugin's settings
            $this->gowish_options = get_option('gowish_option_name');

            // Start outputting the settings page HTML
            ?>

            <div class="wrap">
                <h2><?php echo __('GoWish settings', 'gowish'); ?></h2>
                <p></p>
                <?php settings_errors(); ?>

                <form method="post" action="options.php">
                    <?php
                    // Generate the necessary fields for the settings form
                    settings_fields('gowish_option_group');
                    do_settings_sections('gowish-admin-page');
                    wp_nonce_field('gowish_nonce_action', 'gowish_nonce_field');
                    submit_button();
                    ?>
                </form>
            </div>
        <?php
        }


        // This function initializes the plugin settings page by registering settings, sections, and fields
        public function gowish_page_init()
        {
            // Register a setting with a callback for sanitization
            register_setting(
                'gowish_option_group', // option_group
                'gowish_option_name', // option_name
                array($this, 'gowish_sanitize') // sanitize_callback
            );

            // Add a settings section to the plugin settings page
            add_settings_section(
                'gowish_setting_section', // id
                'Settings', // title
                array($this, 'gowish_section_info'), // callback
                'gowish-admin-page' // page
            );

            // Add a field to select the default hook
            add_settings_field(
                'select_default_hook', // id
                'Select hook', // title
                array($this, 'select_default_hook_callback'), // callback
                'gowish-admin-page', // page
                'gowish_setting_section' // section
            );

            // Add a field for a custom hook
            add_settings_field(
                'custom_hook', // id
                'Custom hook', // title
                array($this, 'custom_hook_callback'), // callback
                'gowish-admin-page', // page
                'gowish_setting_section' // section
            );

            // Add a field for custom CSS/Styling
            add_settings_field(
                'css_styling', // id
                'CSS / Styling', // title
                array($this, 'css_styling_callback'), // callback
                'gowish-admin-page', // page
                'gowish_setting_section' // section
            );

            // Add a field for button text
            add_settings_field(
                'buttontext', // id
                'Button text', // title
                array($this, 'buttontext_callback'), // callback
                'gowish-admin-page', // page
                'gowish_setting_section' // section
            );

            // Add a field for selecting a template
            add_settings_field(
                'select_design', // id
                'Select template', // title
                array($this, 'select_design_callback'), // callback
                'gowish-admin-page', // page
                'gowish_setting_section' // section
            );
        }

        // This function sanitizes the user input from the plugin's settings form before saving it to the database
        // In gowish_sanitize(), verify the nonce field
        public function gowish_sanitize($input)
        {

            // Check if nonce is set and verify
            if (!isset($_POST['gowish_nonce_field']) || !wp_verify_nonce($_POST['gowish_nonce_field'], 'gowish_nonce_action')) {
                return $this->gowish_options;
            }


            $sanitary_values = array();

            // Check if 'custom_hook' is set, and store its value in the $sanitary_values array
            if (isset($input['custom_hook'])) {
                $sanitary_values['custom_hook'] = $input['custom_hook'];
            }

            // Check if 'css_styling' is set, sanitize its value using esc_textarea(), and store it in the $sanitary_values array
            if (isset($input['css_styling'])) {
                $sanitary_values['css_styling'] = esc_textarea($input['css_styling']);
            }

            // Check if 'buttontext' is set, sanitize its value using esc_html(), and store it in the $sanitary_values array
            if (isset($input['buttontext'])) {
                $sanitary_values['buttontext'] = esc_html($input['buttontext']);
            }

            // Check if 'select_design' is set, and store its value in the $sanitary_values array
            if (isset($input['select_design'])) {
                $sanitary_values['select_design'] = $input['select_design'];
            }

            // Check if 'select_default_hook' is set, and store its value in the $sanitary_values array
            if (isset($input['select_default_hook'])) {
                $sanitary_values['select_default_hook'] = $input['select_default_hook'];
            }

            // Return the sanitized values
            return $sanitary_values;
        }


        // This is an empty function that can be used as a placeholder for a settings section description or additional information
        public function gowish_section_info()
        {
        }




        // This function displays the input field for entering a custom hook in the plugin's settings page
        public function custom_hook_callback()
        {
            // Display a description paragraph for the custom hook input field
        ?>
            <p>If you want to use a custom hook, simply enter the hook name here and save. The plugin will force the use of this instead of any value selected above.</p><br>
        <?php
            // Use printf to generate the input field with the current value of the 'custom_hook' option or an empty value if it's not set
            printf(
                '<input class="regular-text" type="text" name="gowish_option_name[custom_hook]" id="custom_hook" value="%s">',
                isset($this->gowish_options['custom_hook']) ? esc_attr($this->gowish_options['custom_hook']) : ''
            );
        }


        // This function displays the textarea for customizing the CSS styling in the plugin's settings page
        public function css_styling_callback()
        {
            // Use printf to generate the textarea with the current value of the 'css_styling' option or an empty value if it's not set
            printf(
                '<textarea class="large-text" rows="5" name="gowish_option_name[css_styling]" id="css_styling">%s</textarea>',
                isset($this->gowish_options['css_styling']) ? esc_attr($this->gowish_options['css_styling']) : ''
            );
        }


        // This function displays the input field for customizing the button text in the plugin's settings page
        public function buttontext_callback()
        {
            // Use printf to generate the input field with the current value of the 'buttontext' option or a default value if it's not set
            printf(
                '<input type="text" name="gowish_option_name[buttontext]" id="buttontext" value="%s">',
                isset($this->gowish_options['buttontext']) ? esc_attr($this->gowish_options['buttontext']) : 'ADD TO GOWISH'
            );
        }


        // This function displays the select design dropdown and button design previews in the plugin's settings page
        public function select_design_callback()
        {
        ?>
            <!-- Display a brief description about selecting a button design -->
            <p>Select the design of the button you want to use.</p><br>
            <!-- Display the select design dropdown -->
            <select name="gowish_option_name[select_design]" id="select_design">
                <?php
                // Check if the 'select_design' option is set to 'design1', and if so, mark it as selected
                $selected = (isset($this->gowish_options['select_design']) && $this->gowish_options['select_design'] === 'design1') ? 'selected' : '';
                ?>
                <option value="design1" <?php echo $selected; ?>>Design 1</option>
                <?php
                // Check if the 'select_design' option is set to 'design2', and if so, mark it as selected
                $selected = (isset($this->gowish_options['select_design']) && $this->gowish_options['select_design'] === 'design2') ? 'selected' : '';
                ?>
                <option value="design2" <?php echo $selected; ?>>Design 2</option>
                <?php
                // Check if the 'select_design' option is set to 'design3', and if so, mark it as selected
                $selected = (isset($this->gowish_options['select_design']) && $this->gowish_options['select_design'] === 'design3') ? 'selected' : '';
                ?>

            </select>

            <br><br>
            <!-- Load the external JavaScript and CSS files for displaying the button design previews -->
            <script type="application/javascript" src="https://storage.googleapis.com/gowish-button-prod/js/gowish-iframe.js" id="gowish-iframescript" defer></script>
            <link rel="stylesheet" href="https://storage.googleapis.com/gowish-button-prod/css/gowish-iframe.css">
            <!-- Display the button design previews -->
            <div class="button-installation__ButtonContainer-sc-i055tj-6 glEeqj">
                <b>Design 1:</b><br><br>
                <button id="gowishBlueButton" class="gowish-btn blue" type="button"><?php echo $this->gowish_options['buttontext'] ?? 'ADD TO GOWISH' ?></button>
                <br><br>
                <b>Design 2:</b><br><br>
                - Look at right side of window -
                <div class="floating-lists fl-ready">
                    <ul class="fl-bar fl-right fl-connected fl-white-icon fl-white-icon-over fl-white-label-text fl-side-space fl-button-space fl-css-anim">
                        <li class="fl-item-1-1">
                            <div class="fl-icon">
                                <button id="gowishRoundedButton" class="gowish-btn-rounded" type="button" style="display:block;"></button>
                            </div>
                            <div class="fl-label" style=""><?php echo $this->gowish_options['buttontext'] ?? 'ADD TO GOWISH' ?></div>
                        </li>
                    </ul>
                </div>

                <!-- Load the plugin's admin CSS file -->
                <link rel="stylesheet" href="<?php echo plugins_url(basename(__DIR__)); ?>/admin-styling.css" />
            </div>

        <?php
        }


        /**
         * Renders the dropdown menu for selecting the default hook for the button placement.
         * The function iterates through an array of possible hook values and generates the 
         * corresponding <option> elements with the appropriate 'selected' attribute.
         */
        public function select_default_hook_callback()
        {
            $options = [
                'woocommerce_before_single_product',
                'woocommerce_before_single_product_summary',
                'woocommerce_single_product_summary',
                'woocommerce_before_add_to_cart_form',
                'woocommerce_product_thumbnails',
                'woocommerce_before_variations_form',
                'woocommerce_before_add_to_cart_button',
                'woocommerce_before_single_variation',
                'woocommerce_single_variation',
                'woocommerce_before_add_to_cart_quantity',
                'woocommerce_after_add_to_cart_quantity',
                'woocommerce_after_single_variation',
                'woocommerce_after_add_to_cart_button',
                'woocommerce_after_variations_form',
                'woocommerce_after_add_to_cart_form',
                'woocommerce_product_meta_start',
                'woocommerce_product_meta_end',
                'woocommerce_share',
                'woocommerce_after_single_product_summary',
                'woocommerce_after_single_product'
            ];
        ?>
            <p>Select the placement of the button.</p><br>
            <select name="gowish_option_name[select_default_hook]" id="select_default_hook">
                <?php
                foreach ($options as $option) {
                    $selected = (isset($this->gowish_options['select_default_hook']) && $this->gowish_options['select_default_hook'] === $option) ? 'selected' : '';
                    echo "<option value=\"$option\" $selected>$option</option>";
                }
                ?>
            </select>
<?php
        }
    }
}

$gowish = new gowish;
