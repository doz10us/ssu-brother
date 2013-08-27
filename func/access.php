<?php
/**
 * Created by JetBrains PhpStorm.
 * User: buch
 * Date: 27.08.13
 * Time: 18:51
 * Description: 
 */

if (!(checkip(getip()))) die('Access Forbidden');


if (((isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 ))) || (($_REQUEST['trigger']) == "AUTO")) {
    echo readfile("/tmp/log_bro");
} else {
    unset($_SESSION['user_id']);
    setcookie('login', '', 0, "/");
    setcookie('password', '', 0, "/");
    header('Location: index.php');
    exit;
}
?>


?>