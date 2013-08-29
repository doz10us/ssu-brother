<?php
include ("func/version.php");
function form($data, $mydata) {	// This will print html form for data update
    global $version;
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";
    echo '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
    echo '<head>'."\n";
	echo '  <meta http-equiv="content-type" content="text/html; charset=utf-8" />'."\n";
	echo '  <title></title>'."\n";
	echo '  <meta name="keywords" content="" />'."\n";
	echo '  <meta name="description" content="" />'."\n";
	echo '  <link rel="stylesheet" href="func/style.css" type="text/css" media="screen, projection" />'."\n";
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
	echo '                  <form method="post">'."\n";
	echo '                      <table>'."\n";
	echo '                          <tr>'."\n";
	echo '                              <td>IP:</td>'."\n";
	if ($data['INET_NTOA(ip)'] == $mydata['INET_NTOA(ip)']) {
        echo '                      <td>'.$data['INET_NTOA(ip)'].'</td>'."\n";
    } else {
        echo '                      <td>'.$data['INET_NTOA(ip)'].' (<font color="red">'.$mydata['INET_NTOA(ip)'].'</font>)</td>'."\n";
    }
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '                              <td>MAC:</td>'."\n";
	if ($mydata['mac']) { 
		echo '                      <td>'.$mydata['mac'].'</td>'."\n";
		echo '                  </tr>'."\n";
		echo '                  <p><input type="hidden" name="mac" value="'.$mydata['mac'].'"/></p>'."\n";
		echo '                  <tr>'."\n";
		echo '                      <td>Switch:</td>'."\n";
		if (empty($data['switch_id']) && empty($mydata['switch_id']) && empty($data['port']) && empty($mydata['port'])) {
            echo '              <td><font color="red">Unknown</font></td>'."\n";
        } else {
            if ($data['switch_id'] == $mydata['switch_id'] && $data['port'] == $mydata['port']) {
                echo '      <td>'.$data['switch_id'].':'.$data['port'].'</td>'."\n";
            } else {
                echo '<td>'.$data['switch_id'].':'.$data['port'].' (<font color="red">'.$mydata['switch_id'].':'.$mydata['port'].'</font>)</td>'."\n";
            }
        }
	} else { 
		echo '	<td><input type="text" name="mac" value="'.$mydata['mac'].'"/></td>'."\n"; 
	}
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Корпус:</td>'."\n";
	echo '	                            <td><input type="text" name="building" value="'.$mydata['building'].'"/></td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Этаж:</td>'."\n";
	echo '	                            <td><input type="text" name="floor" value="'.$mydata['floor'].'"/></td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Комната:</td>'."\n";
	echo '	                            <td><input type="text" name="room" value="'.$mydata['room'].'"/></td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Имя машины:</td>'."\n";
	echo '	                            <td><input type="text" name="name" value="'.$mydata['workstation'].'"/></td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Комментарий:</td>'."\n";
	echo '	                            <td><input type="text" name="description" value="'.$mydata['description'].'"/></td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <p><input type="hidden" name="swname" value="'.$data['switch_id'].'"/></p>'."\n";
	echo '                          <p><input type="hidden" name="port" value="'.$data['port'].'"/></p>'."\n";
	echo '                          <p><input type="hidden" name="history" value="'.$mydata['history'].'"/></p>'."\n";
	echo '                          <p><input type="hidden" name="update" value="'.$mydata['DATE(`update`)']." ".$mydata['TIME(`update`)'].'"/></p>'."\n";
	echo '                          <tr>'."\n";
	echo '	                            <td>Обновлено:</td>'."\n";
	echo '	                            <td>'.$mydata['DATE(`update`)']." ".$mydata['TIME(`update`)'].'</td>'."\n";
	echo '                          </tr>'."\n";
	echo '                          <tr><td><input type="submit" name="submit" value="Подтвердить"/></td>'."\n";
	#echo '                              <td><input type="submit" name="logout" value="Выйти"/></td></tr>'."\n";
	echo '                      </table>'."\n";
	echo '                  </form>'."\n";
    echo '              </div>'."\n";    //<!-- #content-->
	echo '          </div>'."\n";    //<!-- #container-->
	echo '      <div class="sidebar" id="sideLeft">'."\n";
    echo '          <a href="data.php">Главная</a><br>'."\n";
    echo '          <a href="func/access.php">Логи</a><br>'."\n";
    echo '          <a href="func/baseout.php">База</a><br>'."\n";
    echo '          <br>'."\n";
    echo '          <a href="index.php?logout">Выход</a>'."\n";
    echo '          <br><br><br><br><br><br>'."\n";
    echo '          ('.$version.')<br>'."\n";
	echo '      </div>'."\n";    //<!-- .sidebar#sideLeft -->
    echo '  </div>'."\n";    //<!-- #middle-->
    echo '</div>'."\n";    //<!-- #wrapper -->
    echo '</body>'."\n";
    echo '</html>'."\n";
}
?>
