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

if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
    $file = file('/tmp/log_bro',  FILE_IGNORE_NEW_LINES);
    echo '<form method="post">';
	echo '<table border>';
	echo '<tr>';
    echo '<td>Время</td>';
    echo '<td>IP</td>';
    echo '<td>Действие</td>';
    echo '<tr>';
    foreach ($file as $num => $content) {
        $split = split(" ", $content, 3);
        echo '<tr>';
        echo '<td>'.date(U, $split[0]).'</td>';
        echo '<td>'.$split[1].'</td>';
        echo '<td>'.$split[2].'</td>';
        echo '<tr>';
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
