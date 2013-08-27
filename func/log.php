<?php
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 24.08.13
 * Time: 16:45
 * Description: This is logging function
 */

// $time $ip Logged in: $query
// $time $ip Logged out: $query
// $time $ip String $id updated with %changed data% by $query

function getlog($query, $code){
    $string = time()." ".getip()." ";
    switch ($code) {
        case "0":
            $string .= $query." logged in\n";
            break;
        case "1":
            $string .= "Failed attempt. Invalid login ".$query." or pass\n";
            break;
        case "2":
            $string .= "Failed attempt. Need more permission for user ".$query."\n";
            break;
        case "3":
            $string .= "Data acquired: ".$query."\n";
            break;
        case "4":
            $string .= "No data found \n";
            break;
        default:
            $string .= " Unknown event \n";
            break;
    }
    //$srvtime = $_SERVER['REQUEST_TIME'];
    //echo $string;
    file_put_contents('/tmp/log_bro',$string, FILE_APPEND);
    return(true);
}
?>