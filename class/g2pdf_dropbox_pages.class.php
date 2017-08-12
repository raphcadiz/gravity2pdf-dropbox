<?php
class G2PDF_Signature_Pages {

    public function __construct() {
        add_action('admin_init', array( $this, 'settings_options_init' ));
        add_action('admin_menu', array( $this, 'admin_menus'), 12 );
    }

    public function settings_options_init() {
        register_setting( 'gmergedropbox_settings_options', 'gmergedropbox_settings_options', '' );
    }

    public function admin_menus() {
        add_submenu_page ( 'gravitymerge' , 'Dropbox' , 'Dropbox' , 'manage_options' , 'gravitymergedropbox' , array( $this , 'gravity2pdf_dropbox' ));
    }

    public function gravity2pdf_dropbox() {
        include_once(GMD_PATH_INCLUDES.'/gravity_merge_dropbox.php');
    }
}

new G2PDF_Signature_Pages();