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

    //echo '<form method="post">';
    echo '<table border>';
    echo '<tr>';
    echo '<td>IP</td>';
    echo '<td>MAC</td>';
    echo '<td>switch</td>';
    echo '<td>port</td>';
    echo '<td>name</td>';
    echo '<td>corp</td>';
    echo '<td>floor</td>';
    echo '<td>room</td>';
    echo '<td>update</td>';
    echo '<td>descr</td>';
    echo '<tr>';
    $query="SELECT * FROM `netmap`";
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
    //echo '</form>';
} else {
    unset($_SESSION['user_id']);
    setcookie('login', '', 0, "/");
    setcookie('password', '', 0, "/");
    header('Location: ../index.php');
    exit;
}
?>