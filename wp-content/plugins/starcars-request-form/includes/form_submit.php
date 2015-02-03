<?php
if($_REQUEST['scrf']){
    $data = clearPostData($_POST['data']);

    if(!$data['departingFrom']) {
        $errors[] = 'Incorrect Departing from address.';
    }
    
    if(!$data['goingTo']) {
        $errors[] = 'Incorrect Going to address.';
    }
    
    if(!$data['phone']) {
        $errors[] = 'IncorrectPhone number.';
    }
    
    if(!$errors) {
         global $wpdb, $scrf;
         $query = "
            INSERT INTO `{$scrf['tableName']}` (`departing_from`,`going_to`, `phone`)
            VALUES ('{$data['departingFrom']}', '{$data['goingTo']}', {$data['phone']});";
        if($wpdb->query($query)) {
            
        } else {
            $errors[] = "Sorry, we can't process your request now.";
        }
    }
}
?>
