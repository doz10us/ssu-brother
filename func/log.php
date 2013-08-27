<?php
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 24.08.13
 * Time: 16:45
 * Description: This is logging function
 */

// $time $ip Logged in: $user
// $time $ip Logged out: $user
// $time $ip String $id updated with %changed data% by $user

function getlog($query){
    $string = microtime()." ".$query;
    //$srvtime = $_SERVER['REQUEST_TIME'];
    echo $string;
    file_put_contents('/tmp/log_bro',$string);
    die();
    return(true);
}
?>