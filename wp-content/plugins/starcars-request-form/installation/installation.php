<?php 

register_activation_hook(__FILE__, 'starcars_form_install');
register_uninstall_hook(__FILE__, 'starcars_form_deactivate');

function starcars_form_install()
{
    global $wpdb, $scrf;
    
    if($wpdb->get_var("show tables like '$table_name'") != $scrf['tableName']) {
        $sql = "CREATE TABLE " . $scrf['tableName'] . " (
              id INT NOT NULL AUTO_INCREMENT,
              departing_from VARCHAR(255),
              going_to VARCHAR(255),
              phone VARCHAR(30),
              created TIMESTAMP DEFAULT NOW(),
              UNIQUE KEY id (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function starcars_form_deactivate()
{
	global $wpdb;	
    $table_name = $wpdb->prefix . "sc_request_form";
	$wpdb->query("DROP TABLE IF EXISTS ".$table_name);

}

?>
