<?php
/**
 * Plugin Name:       GoWish plugin
 * Plugin URI:        https://gowish.com/
 * Description:       GoWish plugin for WordPress
 * Version:           1.0.1
 * Requires at least: 5.4
 * Requires PHP:      8.0
 * Author:            GoWish
 * Text Domain:       gowish
 * Domain Path:       /languages
 */
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if ( ! class_exists( 'gowish' ) ) {
    
    class gowish {


        function __construct() {

            // Get settings
            $gowish_options = get_option( 'gowish_option_name' ); 
            $gowish_select_default_hook = $gowish_options["select_default_hook"] ?? "";
            $gowish_custom_hook = $gowish_options["custom_hook"] ?? "";

            // Languages
            add_action( 'plugins_loaded', array($this, 'load_plugin_textdomain') );

            // Load admin menu page
            add_action('admin_menu', array($this, 'gowish_plugin_setup_menu'));
            add_action( 'admin_init', array( $this, 'gowish_page_init' ) );


             // Hook function
            if($gowish_custom_hook != "") {

                // Use custom hook
                add_action( $gowish_custom_hook, array($this, 'hook_function') );

            } else {

                // Use select hooks 
                add_action( $gowish_select_default_hook, array($this, 'hook_function') );


            }


             // Updates
            require dirname(__FILE__).'/plugin-update-checker/plugin-update-checker.php';

            $myUpdateChecker = PucFactory::buildUpdateChecker(
                'https://github.com/websitecareio/wcio-gowish-button',
                __FILE__,
                'wcio-gowish-button'
            );

            //Set the branch that contains the stable release.
            $myUpdateChecker->setBranch('main');
    

        }


        function hook_function() {

            $gowish_options = get_option( 'gowish_option_name' );
            $buttonText = $gowish_options['buttontext'] ?? 'ADD TO GOWISH';

            
            echo '<link rel="stylesheet" href="https://oenskeinspiration.dk/knap/css/onskeskyen_wish_button.min.css">
            <script type="application/javascript" src="https://xn--nskeskyen-k8a.dk/onskeskyen-wish-button/external-wish-button.js" id="ov-onskeskyen-generate-wish-button-script" defer=""></script>';
            
            echo "<style>".$gowish_options['css_styling']."</style>";

            if($gowish_options["select_design"] == "design1") {

                
                echo '<button id="ov-onskeskyen-generated-wish-button" class="pill blue" type="button">'.$buttonText.'</button>';
            
            }

            if($gowish_options["select_design"] == "design2") {
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
            <style>
        .floating-lists.fl-ready {
            visibility: visible;
            z-index: 9999;
        }
        .floating-lists {
            font-family: Arial,Helvetica,sans-serif;
            visibility: hidden;
            z-index: 9999;
        }
        
        .floating-lists.fl-ready ul {
            top: 40vh;
            right: 0;
            position: fixed;
            z-index: 10100;
        }
        
        .floating-lists ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .fl-bar {
            position: fixed;
            z-index: 10100;
        }
        .fl-bar.fl-button-space li:not(:last-child) {
            margin-bottom: 2px;
        }
        .fl-bar.fl-right li {
            height: 40px;
        }
        .fl-bar li {
            list-style: none;
        }
        .fl-bar.fl-right a {
            position: absolute;
            right: 0;
        }
        .fl-bar a {
            width: 40px;
            display: block;
            position: relative;
        }
        .floating-lists a {
            cursor: pointer;
            text-decoration: none;
        }
        
        .fl-item-1-1 .fl-icon, .fl-item-1-1 a:hover .fl-icon, .fl-item-1-1 .fl-label {
            color: #009bbf;
        
            height: 40px;
            line-height: 40px;
        }
        .fl-right .fl-icon {
            position: absolute;
            right: 0;
        }
        .fl-icon, .fl-label {
            line-height: 40px;
        }
        
        .fl-icon {
            font-size: 24px;
            -webkit-transition: background-color 100ms linear;
            transition: background-color 100ms linear;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            z-index: 11;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            background-repeat: no-repeat;
            font-size: 24px;
        }
        .fl-right.fl-connected .fl-label {
            max-width: 0;
            display: block;
            transition: all 0.5s;
            padding: 0 11px 0 11px;
            right: 2px;
            left: auto;
        }
        .fl-item-1-3 .fl-icon, .fl-item-1-3 a:hover .fl-icon, .fl-item-1-3 .fl-label {
            color: #128be0;
            background-color: #fff;
        }
        .fl-label, .fl-label-space .fl-hit, .fl-sub.fl-side>ul {
            left: 48px;
        }
        .fl-label {
            font-size: 15px;
        }
        .fl-icon, .fl-label {
            line-height: 40px;
        }
        .fl-label {
            font-size: 15px;
            line-height: 42px;
            padding: 0 11px 0 8px;
            position: absolute;
            left: 42px;
            top: 0;
            z-index: 10;
            display: none;
            box-sizing: border-box;
            white-space: nowrap;
        }
        .floating-lists li:hover .fl-label {
            opacity: 1;
            max-width: 250px;
            margin-right: 16px;
            background: #fff;
            border-radius: 12px;
        }
        .floating-lists li:hover .fl-label {
            opacity: 1;
            max-width: 250px;
            padding-right:40px;
            margin-right: 16px;
        }
        .fl-right.fl-connected .fl-label {
            max-width: 0;
            display: block;
            transition: all 0.5s;
        }
        .fl-right.fl-connected .fl-label {
            padding: 0 11px 0 11px;
        }
        .fl-right.fl-connected .fl-label {
            right: 2px;
            left: auto;
        }
                </style>
            <?php

            }

            if($gowish_options["select_design"] == "design3") {

            }


            if($gowish_options["select_design"] == "design4") {

                echo '<button id="ov-onskeskyen-generated-wish-button" class="pill blue" type="button"></button>';

            }


        }

        function load_plugin_textdomain() {
            load_plugin_textdomain( 'gowish', false, basename( __DIR__ ) . '/languages/' );
        }

        function gowish_plugin_setup_menu(){
            add_menu_page( 
                __('GoWish','gowish'), 
                __('GoWish','gowish'), 
                'manage_options', 
                'gowish',
                array($this, 'gowish_init'),
                plugins_url( 'gowish/images/menuicon.png' ) );
        }
         
        function gowish_init(){

            $this->gowish_options = get_option( 'gowish_option_name' ); 
            
            ?>

            <div class="wrap">
                <h2><?php echo __('GoWish settings','gowish'); ?></h2>
                <p></p>
                <?php settings_errors(); ?>
    
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'gowish_option_group' );
                        do_settings_sections( 'gowish-admin-page' );
                        submit_button();
                    ?>
                </form>
            </div>
            <?php

        }

        
	public function gowish_page_init() {
		register_setting(
			'gowish_option_group', // option_group
			'gowish_option_name', // option_name
			array( $this, 'gowish_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'gowish_setting_section', // id
			'Settings', // title
			array( $this, 'gowish_section_info' ), // callback
			'gowish-admin-page' // page
		);

        add_settings_field(
			'select_default_hook', // id
			'Select hook', // title
			array( $this, 'select_default_hook_callback' ), // callback
			'gowish-admin-page', // page
			'gowish_setting_section' // section
		);

		add_settings_field(
			'custom_hook', // id
			'Custom hook', // title
			array( $this, 'custom_hook_callback' ), // callback
			'gowish-admin-page', // page
			'gowish_setting_section' // section
		);

		add_settings_field(
			'css_styling', // id
			'CSS / Styling', // title
			array( $this, 'css_styling_callback' ), // callback
			'gowish-admin-page', // page
			'gowish_setting_section' // section
		);

		add_settings_field(
			'buttontext', // id
			'Button text', // title
			array( $this, 'buttontext_callback' ), // callback
			'gowish-admin-page', // page
			'gowish_setting_section' // section
		);

		add_settings_field(
			'select_design', // id
			'Select template', // title
			array( $this, 'select_design_callback' ), // callback
			'gowish-admin-page', // page
			'gowish_setting_section' // section
		);		

	}

	public function gowish_sanitize($input) {
		$sanitary_values = array();
       
		$sanitary_values = array();
		if ( isset( $input['custom_hook'] ) ) {
			$sanitary_values['custom_hook'] = $input['custom_hook'];
		}

		if ( isset( $input['css_styling'] ) ) {
			$sanitary_values['css_styling'] = esc_textarea( $input['css_styling'] );
		}

		if ( isset( $input['buttontext'] ) ) {
			$sanitary_values['buttontext'] = esc_html( $input['buttontext'] );
		}

		if ( isset( $input['select_design'] ) ) {
			$sanitary_values['select_design'] = $input['select_design'];
		}
		if ( isset( $input['select_default_hook'] ) ) {
			$sanitary_values['select_default_hook'] = $input['select_default_hook'];
		}

		return $sanitary_values;
	}

	public function gowish_section_info() {
		
	}

	

	public function custom_hook_callback() {
        ?>
        <p>If you want to use a custom hook, simply enter the hook name here and save. The plugin will force the use of this instead of any value selected above.</p><br>
        <?php
        printf(
			'<input class="regular-text" type="text" name="gowish_option_name[custom_hook]" id="custom_hook" value="%s">',
			isset( $this->gowish_options['custom_hook'] ) ? esc_attr( $this->gowish_options['custom_hook']) : ''
		);
	}

	public function css_styling_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="gowish_option_name[css_styling]" id="css_styling">%s</textarea>',
			isset( $this->gowish_options['css_styling'] ) ? esc_attr( $this->gowish_options['css_styling']) : ''
		);
	}

	public function buttontext_callback() {
		printf(
			'<input type="text" name="gowish_option_name[buttontext]" id="buttontext" value="%s">',
			isset( $this->gowish_options['buttontext'] ) ? esc_attr( $this->gowish_options['buttontext']) : 'ADD TO GOWISH'
		);
	}

	public function select_design_callback() {
		?>
        <p>Select the design of the button you want to use.</p><br>
        <select name="gowish_option_name[select_design]" id="select_design">
			<?php $selected = (isset( $this->gowish_options['select_design'] ) && $this->gowish_options['select_design'] === 'design1') ? 'selected' : '' ; ?>
			<option value="design1" <?php echo $selected; ?>>Design 1</option>
			<?php $selected = (isset( $this->gowish_options['select_design'] ) && $this->gowish_options['select_design'] === 'design2') ? 'selected' : '' ; ?>
			<option value="design2" <?php echo $selected; ?>>Design 2</option>
			<?php $selected = (isset( $this->gowish_options['select_design'] ) && $this->gowish_options['select_design'] === 'design3') ? 'selected' : '' ; ?>

		</select>

        <!-- 

        			<option value="design3" <?php echo $selected; ?>>Design 3</option>
			<?php $selected = (isset( $this->gowish_options['select_design'] ) && $this->gowish_options['select_design'] === 'design4') ? 'selected' : '' ; ?>
			<option value="design4" <?php echo $selected; ?>>Design 4</option>

        -->
        <br><br><script type="application/javascript" src="https://storage.googleapis.com/gowish-button-prod/js/gowish-iframe.js" id="gowish-iframescript" defer></script><link rel="stylesheet" href="https://storage.googleapis.com/gowish-button-prod/css/gowish-iframe.css">
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
    <style>
.floating-lists.fl-ready {
    visibility: visible;
    z-index: 9999;
}
.floating-lists {
    font-family: Arial,Helvetica,sans-serif;
    visibility: hidden;
    z-index: 9999;
}

.floating-lists.fl-ready ul {
    top: 41vh;
    right: 0;
    position: fixed;
    z-index: 10100;
}

.floating-lists ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.fl-bar {
    position: fixed;
    z-index: 10100;
}
.fl-bar.fl-button-space li:not(:last-child) {
    margin-bottom: 2px;
}
.fl-bar.fl-right li {
    height: 40px;
}
.fl-bar li {
    list-style: none;
}
.fl-bar.fl-right a {
    position: absolute;
    right: 0;
}
.fl-bar a {
    width: 40px;
    display: block;
    position: relative;
}
.floating-lists a {
    cursor: pointer;
    text-decoration: none;
}

.fl-item-1-1 .fl-icon, .fl-item-1-1 a:hover .fl-icon, .fl-item-1-1 .fl-label {
    color: #009bbf;
    font-weight:bold
    height: 40px;
    line-height: 40px;
}
.fl-right .fl-icon {
    position: absolute;
    right: 0;
}
.fl-icon, .fl-label {
    line-height: 40px;
}

.fl-icon {
    font-size: 24px;
    -webkit-transition: background-color 100ms linear;
    transition: background-color 100ms linear;
    width: 40px;
    height: 40px;
    line-height: 40px;
    position: relative;
    z-index: 11;
    display: inline-block;
    vertical-align: middle;
    text-align: center;
    background-repeat: no-repeat;
    font-size: 24px;
}
.fl-right.fl-connected .fl-label {
    max-width: 0;
    display: block;
    transition: all 0.5s;
    padding: 0 11px 0 11px;
    right: 2px;
    left: auto;
}
.fl-item-1-3 .fl-icon, .fl-item-1-3 a:hover .fl-icon, .fl-item-1-3 .fl-label {
    color: #128be0;
    background-color: #fff;
}
.fl-label, .fl-label-space .fl-hit, .fl-sub.fl-side>ul {
    left: 48px;
}
.fl-label {
    font-size: 15px;
}
.fl-icon, .fl-label {
    line-height: 40px;
}
.fl-label {
    font-size: 15px;
    line-height: 42px;
    padding: 0 11px 0 8px;
    position: absolute;
    left: 42px;
    top: 0;
    z-index: 10;
    display: none;
    box-sizing: border-box;
    white-space: nowrap;
}
.floating-lists li:hover .fl-label {
    opacity: 1;
    max-width: 250px;
    margin-right: 16px;
    background: #fff;
    border-radius: 12px;
}
.floating-lists li:hover .fl-label {
    opacity: 1;
    max-width: 250px;
    padding-right:40px;
    margin-right: 16px;
}
.fl-right.fl-connected .fl-label {
    max-width: 0;
    display: block;
    transition: all 0.5s;
}
.fl-right.fl-connected .fl-label {
    padding: 0 11px 0 11px;
}
.fl-right.fl-connected .fl-label {
    right: 2px;
    left: auto;
}
        </style>
<!--
        <br><br>
        <b>Design 3:</b><br><br>
        <button id="gowishWhiteButton" class="gowish-btn" type="button">ADD TO ØNSKESKYEN</button>

        <br><br>
        <b>Design 4:</b><br><br>
        <button id="gowishRoundedButton" class="gowish-btn-rounded" type="button"></button>
        -->
    </div>
        
 <?php
	}

    public function select_default_hook_callback() {
		?>
        <p>Select the placement of the of the button.</p><br>
        <select name="gowish_option_name[select_default_hook]" id="select_default_hook">
        <?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_single_product') ? 'selected' : '' ; ?>
<option value="woocommerce_before_single_product" <?php echo $selected; ?>>woocommerce_before_single_product</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_single_product_summary') ? 'selected' : '' ; ?>
<option value="woocommerce_before_single_product_summary" <?php echo $selected; ?>>woocommerce_before_single_product_summary</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_single_product_summary') ? 'selected' : '' ; ?>
<option value="woocommerce_single_product_summary" <?php echo $selected; ?>>woocommerce_single_product_summary</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_add_to_cart_form') ? 'selected' : '' ; ?>
<option value="woocommerce_before_add_to_cart_form" <?php echo $selected; ?>>woocommerce_before_add_to_cart_form</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_product_thumbnails') ? 'selected' : '' ; ?>
<option value="woocommerce_product_thumbnails " <?php echo $selected; ?>>woocommerce_product_thumbnails</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_variations_form') ? 'selected' : '' ; ?>
<option value="woocommerce_before_variations_form" <?php echo $selected; ?>>woocommerce_before_variations_form</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_add_to_cart_button') ? 'selected' : '' ; ?>
<option value="woocommerce_before_add_to_cart_button" <?php echo $selected; ?>>woocommerce_before_add_to_cart_button</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_single_variation') ? 'selected' : '' ; ?>
<option value="woocommerce_before_single_variation" <?php echo $selected; ?>>woocommerce_before_single_variation</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_single_variation') ? 'selected' : '' ; ?>
<option value="woocommerce_single_variation" <?php echo $selected; ?>>woocommerce_single_variation</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_before_add_to_cart_quantity') ? 'selected' : '' ; ?>
<option value="woocommerce_before_add_to_cart_quantity" <?php echo $selected; ?>>woocommerce_before_add_to_cart_quantity</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_add_to_cart_quantity') ? 'selected' : '' ; ?>
<option value="woocommerce_after_add_to_cart_quantity" <?php echo $selected; ?>>woocommerce_after_add_to_cart_quantity</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_single_variation') ? 'selected' : '' ; ?>
<option value="woocommerce_after_single_variation" <?php echo $selected; ?>>woocommerce_after_single_variation</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_add_to_cart_button') ? 'selected' : '' ; ?>
<option value="woocommerce_after_add_to_cart_button" <?php echo $selected; ?>>woocommerce_after_add_to_cart_button</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_variations_form') ? 'selected' : '' ; ?>
<option value="woocommerce_after_variations_form" <?php echo $selected; ?>>woocommerce_after_variations_form</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_add_to_cart_form') ? 'selected' : '' ; ?>
<option value="woocommerce_after_add_to_cart_form" <?php echo $selected; ?>>woocommerce_after_add_to_cart_form</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_product_meta_start') ? 'selected' : '' ; ?>
<option value="woocommerce_product_meta_start" <?php echo $selected; ?>>woocommerce_product_meta_start</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_product_meta_end') ? 'selected' : '' ; ?>
<option value="woocommerce_product_meta_end" <?php echo $selected; ?>>woocommerce_product_meta_end</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_share') ? 'selected' : '' ; ?>
<option value="woocommerce_share" <?php echo $selected; ?>>woocommerce_share</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_single_product_summary') ? 'selected' : '' ; ?>
<option value="woocommerce_after_single_product_summary" <?php echo $selected; ?>>woocommerce_after_single_product_summary</option>
<?php $selected = (isset( $this->gowish_options['select_default_hook'] ) && $this->gowish_options['select_default_hook'] === 'woocommerce_after_single_product') ? 'selected' : '' ; ?>
<option value="woocommerce_after_single_product" <?php echo $selected; ?>>woocommerce_after_single_product</option>
		</select>

 <?php
	}


}
}

$gowish = new gowish;
