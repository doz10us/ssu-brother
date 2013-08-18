<?php
session_start();

include_once("func/var.php");
include_once("func/form.php");
include_once("func/get.php");
include_once("func/base.php");


//if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
        getdata($data, $mydata, $trigger);
        switch ($trigger) {
                case "SELECT":
                        ns_connect($data);
                        $mydata = $data;
                        fhosting($data, $mydata, &$trigger);
                        form($data, &$mydata, &$trigger);
                        break;
                case "INSERT":
                        fhosting($data, $mydata, &$trigger);
                        form($data, &$mydata, &$trigger);
                        break;
                case "UPDATE":
                        fhosting($data, $mydata, &$trigger);
                        form($data, &$mydata, &$trigger);
                        break;
                case "AUTO":
                        ns_connect($data);
                        $mydata['mac'] = $data['mac'];
                        $mydata['swname'] = $data['swname'];
                        $mydata['port'] = $data['port'];
                        $mydata['name'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['name']);
                        $mydata['room'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['room']);
                        fhosting($data, &$mydata, $trigger);
                        die();
                default:
                        header('HTTP/1.1 404 Not Found');
                        header('Status: 404 Not Found');
                        die();
        }
} else {
        unset($_SESSION['user_id']);
        setcookie('login', '', 0, "/");
        setcookie('password', '', 0, "/");
        header('Location: index.php');
        exit;
}
?>
