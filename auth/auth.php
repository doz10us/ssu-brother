<?php
//Начинаем сессию
session_start();
//Подключаем конфигурационный файл
include_once ("ldap.php");
include_once ("/var/www/html/dev/func/log.php");
//echo "Auth here <br>"; 
// Logout
if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
        setcookie('login', '', 0, "/");
        setcookie('password', '', 0, "/");
        header('Location: index.php');
        exit;
    }
}
 
//Если пользователь уже аутентифицирован, то перебросить его на страницу main.php
if (isset($_SESSION['user_id']) && ((time() - $_SESSION['user_id']) < 300 )) {
    header('Location: data.php');
    exit;
}
 
//Если пользователь не аутентифицирован, то проверить его используя LDAP
if (isset($_POST['login']) && isset($_POST['password'])) {
    $username = trim(htmlspecialchars($_POST['login']));
    $login = trim(htmlspecialchars($_POST['login'])).$domain;
    $password = $_POST['password'];
    //подсоединяемся к LDAP серверу
    $ldap = ldap_connect('212.193.33.11') or die("Cant connect to LDAP Server");
    //Включаем LDAP протокол версии 3
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ($ldap) {
          // Пытаемся войти в LDAP при помощи введенных логина и пароля
          $bind = ldap_bind($ldap,$login,$password);
 
          if ($bind) {
        	    // echo "member";
                // Проверим, является ли пользователь членом указанной группы.
                $result = ldap_search($ldap,$base,"(&(memberOf=".$memberof.")(".$filter.$username."))");
                // Получаем количество результатов предыдущей проверки
                $result_ent = ldap_get_entries($ldap,$result);
                //print_r ($result_ent);
                if ($result_ent['count'] == 0) {
				    $result = ldap_search($ldap,$base2,"(&(memberOf=".$memberof.")(".$filter.$username."))");
					$result_ent = ldap_get_entries($ldap,$result);
				}
				//echo "<br><br>";
				//print_r ($result_ent);
          } else {
              $log = getip()." Access denied for user ".$username.": invalid login or password\n";
              getlog($log);
              die('Вы ввели неправильный логин или пароль. попробуйте еще раз<br /> <a href="index.php">Вернуться назад</a>');
          }
    }
 
    // Если пользователь найден, то пропускаем его дальше и перебрасываем на main.php
    if (!($_POST['password']=="") && $result_ent['count'] != 0) {
        $log = getip()." ".$username." logged in\n";
        getlog($log);
        $_SESSION['user_id'] = time();
        header('Location: data.php');
        exit;
    } else {
        $log = getip()." Access denied for user ".$username.": low permission\n";
        getlog($log);
        die('К сожалению, вам доступ закрыт<br /> <a href="index.php">Вернуться назад</a>');
    }
}	
?>
