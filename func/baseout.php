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
    mysql_select_db('netmap') or die('db not found');

    echo '<form method="post">';
    echo '<table border>';
    echo '<tr>';
    echo '<td>Время</td>';
    echo '<td>IP</td>';
    echo '<td>Действие</td>';
    echo '<tr>';
    $query="SELECT * FROM netmap";
    $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
    unset($query);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
        $split = split(" ", $row, 12);
            echo '<tr>';
            for ($i = 1; $i<12; $i++){
                echo '<td>'.$split[2].'</td>';
            }
            echo '<tr>';
        }
    }
    echo '</table>';
    echo '</form>';
} else {
    unset($_SESSION['user_id']);
    setcookie('login', '', 0, "/");
    setcookie('password', '', 0, "/");
    header('Location: ../index.php');
    exit;
}
?>