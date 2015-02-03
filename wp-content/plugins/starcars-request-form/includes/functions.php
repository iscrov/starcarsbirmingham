<?php


function scrfGetFormActionUrl($type)
{
    return site_url('?scrf=' . $type);
}

function clearPostData($data)
{
    $result = array();
    foreach($data as $key => $value) {
        $clear = trim(strip_tags($value));
        if(is_numeric($clear)) {
            $result[$key] = $clear*1;
        } else {
            $result[$key] = $clear;
        }
    }
    return $result;
}

?>
