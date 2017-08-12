<?php
use Dropbox\Client;
use Dropbox\WriteMode;

class G2PDF_Dropbox {
    private static $instance;

    // plugin version
    private $version = '1.0';

    public static function get_instance()
    {
        if( null == self::$instance ) {
            self::$instance = new G2PDF_Dropbox();
            self::$instance->licensing();
        }

        return self::$instance;
    }

    private function licensing() {
        if ( class_exists( 'Gravity_Merge_License' ) ) {
            $license = new Gravity_Merge_License( __FILE__, 'Gravity 2 PDF - Dropbox', $this->version, 'Raph Cadiz' );
        }
    }

    public function __construct() {
        add_filter('gmerge_integrations_ajax_paths', array( $this, 'register_integrations_path' ), 1);
        add_action('g2pdf_after_merge', array($this, 'process_integration'), 10, 4);
        add_action('wp_ajax_dropboxIntegrationTemplate' , array( $this , 'ajax_integration_template' ));
        add_action('dropbox_integration_template' , array( $this , 'integration_template' ), 10, 2);
    }

    public function register_integrations_path($paths) {
        $dropbox_path = array(
            'dropbox' => 'dropboxIntegrationTemplate'
        );

        return array_merge( $paths, $dropbox_path );
    }

    public function ajax_integration_template() {
        ob_start();
        ?>
        <div class="integration-wrapper">
            <a href="javascript:;" class="integration-remove"><span class="dashicons dashicons-minus"></span></a>
            <label><strong>Dropbox</strong></label><br /><br />
            <label>Dropbox Delivery</label><br />
            <input type="text" name="integrations[%key%][dropbox][integration_dropbox]" />
            <span><i>Specified Folder name where the pdf must be uploaded. You can use the following user variables also %date% , %user_email% , %user_firstname% , %user_lastname% for logged in user. You can use gravity form , form entry ids also %form_id% , %form_entry_id%</i></span>
        </div>
        <?php
        $template = ob_get_contents();
        ob_end_clean();

        echo $template;
        die();
    } 

    public function integration_template($index = 0, $value = array()) {
        ob_start();
        ?>
        <div class="integration-wrapper">
            <a href="javascript:;" class="integration-remove"><span class="dashicons dashicons-minus"></span></a>
            <label><strong>Dropbox</strong></label><br /><br />
            <label>Dropbox Delivery</label><br />
            <input type="text" name="integrations[<?= $index ?>][dropbox][integration_dropbox]" value="<?= $value->integration_dropbox ?>" />
            <span><i>Specified Folder name where the pdf must be uploaded. You can use the following user variables also %date% , %user_email% , %user_firstname% , %user_lastname% for logged in user. You can use gravity form , form entry ids also %form_id% , %form_entry_id%</i></span>
        </div>
        <?php
        $template = ob_get_contents();
        ob_end_clean();

        echo $template;
    }

    public function process_integration($final_file, $file_name, $entry, $integrations){
        if(!property_exists($integrations, 'dropbox'))
            return;

        $integration = $integrations->dropbox;
        try{
            $gmergedropbox_settings_options = get_option('gmergedropbox_settings_options');
            $accessToken = isset($gmergedropbox_settings_options['dropbox_app_key']) ? $gmergedropbox_settings_options['dropbox_app_key'] : '';
            $dropboxPath = '/'.ltrim($integration->integration_dropbox, '/');

            $dropboxPath = str_replace( "%form_id%" , rgar( $entry, 'form_id' ) , $dropboxPath );
            $dropboxPath = str_replace( "%form_entry_id%" , rgar( $entry, 'id' ) , $dropboxPath );
            $dropboxPath = str_replace( "%date%" , time() , $dropboxPath );

            if( is_user_logged_in() ) {
                $current_user = wp_get_current_user();
                $dropboxPath = str_replace( "%user_email%" , $current_user->user_email , $dropboxPath );
                $dropboxPath = !empty($current_user->user_firstname) ? str_replace( "%user_firstname%" , $current_user->user_firstname , $dropboxPath ) : str_replace( "%user_firstname%" , 'userfirstname' , $dropboxPath );
                $dropboxPath = !empty($current_user->user_lastname) ? str_replace( "%user_lastname%" , $current_user->user_lastname , $dropboxPath ) : str_replace( "%user_lastname%" , 'userlastname' , $dropboxPath );
            }
            

            $client = new Client( $accessToken, "GravityMerge" );
            $metadata = $client->getMetadata( $dropboxPath );

            if( $metadata['is_dir'] ){
                $fp = fopen($final_file, "rb");
                $filemetadata = $client->uploadFile( $dropboxPath."/$file_name.pdf" , WriteMode::add() , $fp );
            }else{
                $fp = fopen($final_file, "rb");
                $filemetadata = $client->uploadFile( $dropboxPath."/$file_name.pdf" , WriteMode::add() , $fp );
            }
        }catch( Exception $e ){
            // error_log($e);
        }
    }
}