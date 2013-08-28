<?php
session_start();
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 27.08.13
 * Time: 19:51
 * Description:
 */
include ("login.php");
include ("log.php");
include ("ip.php");

if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
    global $fhhost;
    global $fhlogin;
    global $fhpass;

    $db = mysql_connect($fhhost, $fhlogin, $fhpass) or die('connect to database failed');
    mysql_set_charset('utf8');
    mysql_select_db('portmap') or die('db not found');

    getlog("", "8");
    //echo '<form method="post">';
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
    echo '<table border>';
    echo '<tr>';
    echo '<td>IP</td>';
    echo '<td>MAC</td>';
    echo '<td>Switch</td>';
    echo '<td>Port</td>';
    echo '<td>Имя</td>';
    echo '<td>Корпус</td>';
    echo '<td>Этаж</td>';
    echo '<td>Комната</td>';
    echo '<td>Описание</td>';
    echo '<td>Добавлена</td>';
    echo '<tr>';
    $query="SELECT INET_NTOA(ip),mac,port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description FROM `netmap`";
    $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
    unset($query);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['INET_NTOA(ip)']."</td>";
            echo "<td>".$row['mac']."</td>";
            echo "<td>".$row['switch_id']."</td>";
            echo "<td>".$row['port']."</td>";
            echo "<td>".$row['workstation']."</td>";
            echo "<td>".$row['building']."</td>";
            echo "<td>".$row['floor']."</td>";
            echo "<td>".$row['room']."</td>";
            echo "<td>".$row['description']."</td>";
            echo "<td>".$row['DATE(`update`)']." ".$row['TIME(`update`)']."</td>";
            echo "</tr>";
        }
    }
    echo '</table>';
    echo '              </div>'."\n";    //<!-- #content-->
    echo '          </div>'."\n";    //<!-- #container-->
    echo '      <div class="sidebar" id="sideLeft">'."\n";
    echo '          <a href="../data.php">Главная</a><br>'."\n";
    echo '          <a href="access.php">Логи</a><br>'."\n";
    echo '          <a href="baseout.php">База</a>'."\n";
    echo '          <br>'."\n";
    echo '          <a href="../index.php?logout">Выход</a>'."\n";
    echo '      </div>'."\n";    //<!-- .sidebar#sideLeft -->
    echo '  </div>'."\n";    //<!-- #middle-->
    echo '</div>'."\n";    //<!-- #wrapper -->
    echo '</body>'."\n";
    echo '</html>'."\n";
    //echo '</form>';
} else {
    unset($_SESSION['user_id']);
    setcookie('login', '', 0, "/");
    setcookie('password', '', 0, "/");
    header('Location: ../index.php');
    exit;
}
?>