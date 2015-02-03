<?php
/*
Plugin Name: Star Cars Request form
Plugin URI: 
Description: 
Version: 0.0.1
Author: RonisBT Team
*/

defined( 'ABSPATH' ) || exit;

define( 'SCRF_DIR', plugin_dir_path( __FILE__ ) );
define( 'SCRF_INC_DIR', trailingslashit( SCRF_DIR . 'includes' ) );
define( 'SCRF_INSTALLATION_DIR', trailingslashit( SCRF_DIR . 'installation' ) );
define( 'SCRF_URL', plugin_dir_url( __FILE__ ) );
define( 'SCRF_CSS_URL', trailingslashit( SCRF_URL . 'css' ) );
define( 'SCRF_JS_URL', trailingslashit( SCRF_URL . 'js' ) );


require_once SCRF_INC_DIR . 'globals.php';
require_once SCRF_INSTALLATION_DIR . 'installation.php';
require_once SCRF_INC_DIR . 'functions.php';
require_once SCRF_INC_DIR . 'form_submit.php';
require_once SCRF_INC_DIR . 'rf_table_list.php';



add_action( 'plugins_loaded', 'starcars_load_action' );

function starcars_load_action()
{
    //echo '1';
}


add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
    add_menu_page( 'Request form', 'Request form', 'manage_options', 'scrf', 'scrf_menu_page', plugins_url( 'myplugin/images/icon.png' ), 50 ); 
}

function scrf_menu_page(){
    $ccrfListTable = new Rf_List_Table();
    echo '<div class="wrap"><h2>Request form list</h2>'; 
    $ccrfListTable->prepare_items(); 
    $ccrfListTable->display(); 
    echo '</div>'; 
}

?>
