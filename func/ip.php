<?php
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 26.08.13
 * Time: 17:18
 * Description: 
 */
function getip() {	// This will get real IPv4 and  return as $ip
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
        $ip = getenv("HTTP_CLIENT_IP");

    elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");

    elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");

    elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];

    else
        die('Не удалось получить IP-адрес');
    $ip = '212.193.33.248';
    return($ip);
}

function checkip($ip) {	// ---- This'll check if IP's from our pool
    $ssu = preg_match('/^212\.193\.\d{1,3}\.\d{1,3}\z/', $ip);
    //$live = preg_match('/^192\.168\.\d{1,3}\.\d{1,3}\z/', $ip);
    if ($ssu) {
        $valid = "1";
    } else {
        $valid = "0";
    }

    return($valid);
}
?>