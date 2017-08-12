<?php
/**
 * Plugin Name: Gravity 2 PDF - Dropbox
 * Plugin URI:  https://www.gravity2pdf.com
 * Description: Deiliver completed merge to Dropbox
 * Version:     1.0
 * Author:      gravity2pdf
 * Author URI:  https://github.com/raphcadiz
 * Text Domain: g2pdf-dropbox
 */

define( 'GMD_PATH', dirname( __FILE__ ) );
define( 'GMD_PATH_CLASS', dirname( __FILE__ ) . '/class' );
define( 'GMD_PATH_INCLUDES', dirname( __FILE__ ) . '/includes' );
define( 'GMD_FOLDER', basename( GMD_PATH ) );
define( 'GMD_URL', plugins_url() . '/' . GMD_FOLDER );
define( 'GMD_URL_INCLUDES', GMD_URL . '/includes' );

if(!class_exists('G2PDF_Dropbox')):

    register_activation_hook( __FILE__, 'g2pdf_dropbox_activation' );
    function g2pdf_dropbox_activation(){
        if ( ! class_exists('Gravity_Merge') ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die('Sorry, but this plugin requires the Gravity2PDF to be installed and active.');
        }
    }

    register_deactivation_hook( __FILE__, 'g2pdf_dropbox_deactivation' );
    function g2pdf_dropbox_deactivation(){
        // deactivation block
    }

    add_action( 'admin_init', 'g2pdf_dropbox_plugin_activate' );
    function g2pdf_dropbox_plugin_activate(){
        if ( ! class_exists('Gravity_Merge') ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
    }

    require_once(GMD_PATH.'/vendor/autoload.php');
    
    // include classes
    include_once(GMD_PATH_CLASS.'/g2pdf_dropbox_main.class.php');
    include_once(GMD_PATH_CLASS.'/g2pdf_dropbox_pages.class.php');

    add_action( 'plugins_loaded', array( 'G2PDF_Dropbox', 'get_instance' ) );
endif;