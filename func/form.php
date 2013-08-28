<?php
function form($data, $mydata) {	// This will print html form for data update
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml">';
    echo '<head>';
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
	echo '<title></title>';
	echo '<meta name="keywords" content="" />';
	echo '<meta name="description" content="" />';
	echo '<link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />';
	echo '<!--[if lte IE 6]><link rel="stylesheet" href="style_ie.css" type="text/css" media="screen, projection" /><![endif]-->';
    echo '</head>'."\n";
    echo '<body>';
    echo '  <div id="wrapper">';
	echo '      <div id="header">';
	echo '          <strong>SSU Brother</strong>';
	echo '      </div>';    //<!-- #header-->
	echo '      <div id="middle">';
	echo '          <div id="container">';
	echo '              <div id="content">';
	echo '                  <form method="post">';
	echo '                      <table>';
	echo '                          <tr>';
	echo '                              <td>IP:</td>';
	if ($data['ip'] == $mydata['ip']) {
        echo '<td>'.$data['ip'].'</td>';
    } else {
        echo '<td>'.$data['ip'].' (<font color="red">'.$mydata['ip'].'</font>)</td>';
    }
	echo '</tr>';
	echo '<tr>';
	echo '<td>MAC:</td>';
	if ($mydata['mac']) { 
		echo '<td>'.$mydata['mac'].'</td>';
		echo '</tr>';
		echo '<p><input type="hidden" name="mac" value="'.$mydata['mac'].'"/></p>';
		echo '<tr>';
		echo '<td>Switch:</td>';
		if (empty($data['swname']) && empty($mydata['swname']) && empty($data['port']) && empty($mydata['port'])) {
            echo '<td><font color="red">Unknown</font></td>';
        } else {
            if ($data['swname'] == $mydata['swname'] && $data['port'] == $mydata['port']) {
                echo '<td>'.$data['swname'].':'.$data['port'].'</td>';
            } else {
                echo '<td>'.$data['swname'].':'.$data['port'].' (<font color="red">'.$mydata['swname'].':'.$mydata['port'].'</font>)</td>';
            }
        }
	} else { 
		echo '	<td><input type="text" name="mac" value="'.$mydata['mac'].'"/></td>'; 
	}
	echo '</tr>';
	echo '<tr>';
	echo '	<td>Корпус:</td>';
	echo '	<td><input type="text" name="building" value="'.$mydata['building'].'"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '	<td>Этаж:</td>';
	echo '	<td><input type="text" name="floor" value="'.$mydata['floor'].'"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '	<td>Комната:</td>';
	echo '	<td><input type="text" name="room" value="'.$mydata['room'].'"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '	<td>Имя машины:</td>';
	echo '	<td><input type="text" name="name" value="'.$mydata['name'].'"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '	<td>Комментарий:</td>';
	echo '	<td><input type="text" name="description" value="'.$mydata['description'].'"/></td>';
	echo '</tr>';
	echo '<p><input type="hidden" name="swname" value="'.$data['swname'].'"/></p>';
	echo '<p><input type="hidden" name="port" value="'.$data['port'].'"/></p>';
	echo '<p><input type="hidden" name="history" value="'.$mydata['history'].'"/></p>';
	echo '<p><input type="hidden" name="update" value="'.$mydata['update'].'"/></p>';
	echo '<tr>';
	echo '	<td>Обновлено:</td>';
	echo '	<td>'.$mydata['update'].'</td>';
	echo '</tr>';
	echo '<tr><td><input type="submit" name="submit" value="Подтвердить"/></td>';
	echo '<td><input type="submit" name="logout" value="Выйти"/></td></tr>';
	echo '</table>';
	echo '</form>';
    echo '</div>';    //<!-- #content-->
		echo '</div>';    //<!-- #container-->
		echo '<div class="sidebar" id="sideLeft">';
    echo '<a href="func/access.php">Log</a>';
    echo '<a href="func/baseout.php">Base</a>';
		echo '</div>';    //<!-- .sidebar#sideLeft -->
    echo '</div>';    //<!-- #middle-->
echo '</div>';    //<!-- #wrapper -->
    echo '</body>';
    echo '</html>';
}
?>
