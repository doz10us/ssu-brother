<?php
session_start();

error_reporting(0);

include_once("func/var.php");
include_once("func/form.php");
include_once("func/get.php");
include_once("func/base.php");


if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
	getdata($data, $mydata, $trigger);
    ns_connect($data);
	fhosting($data, $mydata, $trigger);
	form($data, $mydata, $trigger);
} else {
	unset($_SESSION['user_id']);
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");
	header('Location: index.php');
	exit;
}
?>
