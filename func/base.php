<?php
function ns_connect(&$data) {
$hostname = getauth('nshost');
$username = getauth('nsuser');
$password = getauth('nspass');

$db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
mysql_set_charset('utf8');
mysql_select_db('netmap') or die('db not found');

// Get MAC from IP
$query = "select mac from unetmap_host where INET_NTOA(ip) = '".$data['ip']."'";
$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
		$data['mac']=$row['mac'];
    }
}

// Get Switch ID and port where MAC was seen last time
$query = "Select switch_id, port from unetmap_host where mac = '".$data['mac']."'";
$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
		$data['port']=$row['port'];
		$data['swname']=$row['switch_id'];
    }
}

// And get switch name by its id
$query = "SELECT name FROM unetmap_host WHERE id = '".$data['swname']."'";
$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' . mysql_error() . ' query: ' . $sql);

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
		$data['swname']=$row['name'];
    }
}

mysql_close($db);
return("OK");
}

function fhosting($data, &$mydata, $trigger) {
$hostname = getauth('fhhost');
$username = getauth('fhuser');
$password = getauth('fhpass');

$db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
mysql_select_db('portmap') or die('db not found');

switch ($trigger) {
	case "SELECT" :
		if (!($data['mac'])) { $data['mac'] = $mydata['mac']; } else { $mydata['mac'] = $data['mac']; }
		$query="SELECT INET_NTOA(ip),port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE mac = '".$data['mac']."'";
		$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);

		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$mydata['ip']	= $row['INET_NTOA(ip)'];
				$mydata['mac']	= $data['mac'];
				$mydata['name']	= $row['workstation'];
				$mydata['building'] = $row['building'];
				$mydata['floor'] = $row['floor'];
				$mydata['room'] = $row['room'];
				$mydata['description'] = $row['description'];
				$mydata['swname'] = $row['switch_id'];
				$mydata['port'] = $row['port'];
				$mydata['update'] = $row['DATE(`update`)']." ".$row['TIME(`update`)'];
				$mydata['history'] = $row['history'];
			}
			$trigger = "UPDATE";
			echo "I know you!";
		} else {
			$trigger = "INSERT";
			echo "What's your name?";
		}
		break;
	case "INSERT" :
		$query="INSERT INTO netmap (mac,ip,port,switch_id,workstation,building,floor,room,description) VALUES ('".$mydata['mac']."',INET_ATON('".$mydata['ip']."'),'".$mydata['port']."','".$mydata['swname']."','".$mydata['name']."','".$mydata['building']."','".$mydata['floor']."','".$mydata['room']."','".$mydata['description']."')";
		$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);
		fhosting($data, $mydata, "SELECT", $auto);
		break;
	case "UPDATE" :
		$query="UPDATE `netmap` SET `ip`=INET_ATON('".$mydata['ip']."'),`port`='".$mydata['port']."',`switch_id`='".$mydata['swname']."',`workstation`='".$mydata['name']."',`building`='".$mydata['building']."',`floor`='".$mydata['floor']."',`room`='".$mydata['room']."',`description`='".$mydata['description']."',`history`='".$mydata['mac']." ".$mydata['ip']." ".$mydata['port']." ".$mydata['swname']." ".$mydata['name']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$mydata['update']." \n".$mydata['history']."' WHERE `mac`='".$mydata['mac']."';";
		$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);
		fhosting($data, $mydata, "SELECT", $auto);
		break;
	case "AUTO" :
		if (!($data['mac'])) { $data['mac'] = $mydata['mac']; } else { $mydata['mac'] = $data['mac']; }
		$query="SELECT INET_NTOA(ip),port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE mac = '".$data['mac']."'";
		$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);

		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$mydata['ip']	= $row['INET_NTOA(ip)'];
				$mydata['mac']	= $data['mac'];
				$mydata['name']	= $row['workstation'];
				$mydata['building'] = $row['building'];
				$mydata['floor'] = $row['floor'];
				$mydata['room'] = $row['room'];
				$mydata['description'] = $row['description'];
				$mydata['swname'] = $row['switch_id'];
				$mydata['port'] = $row['port'];
				$mydata['update'] = $row['DATE(`update`)']." ".$row['TIME(`update`)'];
				$mydata['history'] = $row['history'];
			}
			$query="UPDATE `netmap` SET `ip`=INET_ATON('".$mydata['ip']."'),`port`='".$mydata['port']."',`switch_id`='".$mydata['swname']."',`workstation`='".$mydata['name']."',`building`='".$mydata['building']."',`floor`='".$mydata['floor']."',`room`='".$mydata['room']."',`description`='".$mydata['description']."',`history`='".$mydata['mac']." ".$mydata['ip']." ".$mydata['port']." ".$mydata['swname']." ".$mydata['name']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$mydata['update']." \n".$mydata['history']."' WHERE `mac`='".$mydata['mac']."';";
			$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);
		} else {
			$query="INSERT INTO netmap (mac,ip,port,switch_id,workstation,building,floor,room,description) VALUES ('".$mydata['mac']."',INET_ATON('".$mydata['ip']."'),'".$mydata['port']."','".$mydata['swname']."','".$mydata['name']."','".$mydata['building']."','".$mydata['floor']."','".$mydata['room']."','".$mydata['description']."')";
			$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $sql);
		}
		break;
	default:
		die();
}

mysql_close($db);
return("OK");
}
?>
