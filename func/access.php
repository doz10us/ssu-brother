<?php
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 27.08.13
 * Time: 18:51
 * Description: 
 */
session_start();
include ("ip.php");
include ("log.php");

date_default_timezone_set('Europe/Moscow');
if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
    $file = file('/tmp/log_bro',  FILE_IGNORE_NEW_LINES);
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";
    echo '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
    echo '<head>'."\n";
    echo '  <meta http-equiv="content-type" content="text/html; charset=utf-8" />'."\n";
    echo '  <title></title>'."\n";
    echo '  <meta name="keywords" content="" />'."\n";
    echo '  <meta name="description" content="" />'."\n";
    echo '  <link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />'."\n";
    echo '  <!--[if lte IE 6]><link rel="stylesheet" href="func/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->'."\n";
    echo '</head>'."\n";
    echo '<body>'."\n";
    echo '  <div id="wrapper">'."\n";
    echo '      <div id="header">'."\n";
    echo '          <strong>SSU Brother</strong>'."\n";
    echo '      </div>'."\n";    //<!-- #header-->
    echo '      <div id="middle">'."\n";
    echo '          <div id="container">'."\n";
    echo '              <div id="content">'."\n";
    echo '<form method="post">';
	echo '<table border>';
	echo '<tr>';
    echo '<td>Время</td>';
    echo '<td>IP</td>';
    echo '<td>Действие</td>';
    echo '<tr>';
    $i=count($file);
    if ($i > 20) $file = array_slice($file, $i-20);
    foreach ($file as $num => $content) {
        $split = split(" ", $content, 3);
        echo '<tr>';
        echo '<td>'.date("H:i:s", $split[0]).'</td>';
        echo '<td>'.$split[1].'</td>';
        echo '<td>'.$split[2].'</td>';
        echo '<tr>';
    }
    echo '</table>';
    echo '</form>';
    echo '              </div>'."\n";    //<!-- #content-->
    echo '          </div>'."\n";    //<!-- #container-->
    echo '      <div class="sidebar" id="sideLeft">'."\n";
    echo '          <a href="data.php">Главная</a><br>'."\n";
    echo '          <a href="func/access.php">Логи</a><br>'."\n";
    echo '          <a href="func/baseout.php">База</a>'."\n";
    echo '      </div>'."\n";    //<!-- .sidebar#sideLeft -->
    echo '  </div>'."\n";    //<!-- #middle-->
    echo '</div>'."\n";    //<!-- #wrapper -->
    echo '</body>'."\n";
    echo '</html>'."\n";
} else {
    unset($_SESSION['user_id']);
    setcookie('login', '', 0, "/");
    setcookie('password', '', 0, "/");
    header('Location: ../index.php');
    exit;
}
?>
