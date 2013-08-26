<?php
include ("login.php");
function ns_connect(&$data) {
    global $nshost;
    global $nslogin;
    global $nspass;
   // $hostname = $nshost;
   // $username = $nslogin;
   // $password = $nspass;

    $db = mysql_connect($nshost, $nslogin, $nspass) or die('connect to database failed');
    mysql_set_charset('utf8');
    mysql_select_db('netmap') or die('db not found');

    if (empty($data['mac'])) {
        // Get MAC from IP
        $query = "select mac from unetmap_host where INET_NTOA(ip) = '".$data['ip']."'";
        $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
		        $data['mac']=$row['mac'];
            }
        }
    }
    if (isset($data['mac'])) {
        // Get Switch ID and port where MAC was seen last time
        $query = "Select switch_id, port from unetmap_host where mac = '".$data['mac']."'";
        $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
		        $data['port']=$row['port'];
		        $data['swname']=$row['switch_id'];
            }
        }

        // And get switch name by its id
        $query = "SELECT name FROM unetmap_host WHERE id = '".$data['swname']."'";
        $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' . mysql_error() . ' query: ' . $query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
		        $data['swname']=$row['name'];
            }
        }
    }

    mysql_close($db);
    return("OK");
}

function fhosting(&$data, &$mydata, &$trigger) {
    global $fhhost;
    global $fhlogin;
    global $fhpass;
    $hostname = $fhhost;
    $username = $fhlogin;
    $password = $fhpass;
    $temp = $data;

    $db = mysql_connect($hostname, $username, $password) or die('connect to database failed');
    mysql_select_db('portmap') or die('db not found');

    if (!(empty($data['mac']))) { $mydata['mac'] = $data['mac']; } else { $data['mac'] = $mydata['mac']; }
    if (empty($data['mac']) && empty($mydata['mac'])) {
        $query="SELECT INET_NTOA(ip),mac,port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE ip = INET_ATON('".$mydata['ip']."')";
        $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
    } else {
        $query="SELECT INET_NTOA(ip),mac,port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE mac = '".$data['mac']."'";
        $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
    }
    unset($query);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $temp['ip']	= $row['INET_NTOA(ip)'];
            $temp['mac']	= $row['mac'];
            $temp['name']	= $row['workstation'];
            $temp['building'] = $row['building'];
            $temp['floor'] = $row['floor'];
            $temp['room'] = $row['room'];
            $temp['description'] = $row['description'];
            $temp['swname'] = $row['switch_id'];
            $temp['port'] = $row['port'];
            $temp['update'] = $row['DATE(`update`)']." ".$row['TIME(`update`)'];
            $temp['history'] = $row['history'];
        }
        if (empty($data['mac'])) {
            $data['mac'] = $temp['mac'];
            ns_connect($data);
            $mydata['swname'] = $data['swname'];
            $mydata['port'] = $data['port'];
        }
        //echo "Trigger is".$trigger."<br>";
        if (!(empty($trigger))) {
            if (array_diff_assoc($mydata, $temp)){
                $query="";
                //print_r($mydata);
                //print_r($temp);
                if ($mydata['ip'] != $temp['ip'] ) $query .= "`ip`=INET_ATON('".$mydata['ip']."')";
                if ($mydata['mac'] != $temp['mac']) $query .= ",`mac`='".$mydata['mac']."'";
                if ($mydata['port'] != $temp['port']) $query .= ",`port`='".$mydata['port']."'";
                if ($mydata['swname'] != $temp['swname']) $query .= ",`switch_id`='".$mydata['swname']."'";
                if ($mydata['name'] != $temp['name']) $query .= ",`workstation`='".$mydata['name']."'";
                if ($mydata['building'] != $temp['building']) $query .= ",`building`='".$mydata['building']."'";
                if ($mydata['floor'] != $temp['floor']) $query .= ",`floor`='".$mydata['floor']."'";
                if ($mydata['room'] != $temp['room']) $query .= ",`room`='".$mydata['room']."'";
                if ($mydata['description'] != $temp['description']) $query .= ",`description`='".$mydata['description']."'";
                if (!(empty($query))) {
                    //$query = "UPDATE `netmap` SET ".$query;
                    //$query .= ",`history`='".$mydata['mac']." ".$mydata['ip']." ".$mydata['port']." ".$mydata['swname']." ".$mydata['name']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$temp['update']." \n".$temp['history']."' WHERE `mac`='".$temp['mac']."' OR `ip`=INET_ATON('".$temp['ip']."');";
                    $query="UPDATE `netmap` SET `ip`=INET_ATON('".$mydata['ip']."'),`mac`='".$mydata['mac']."',`port`='".$mydata['port']."',`switch_id`='".$mydata['swname']."',`workstation`='".$mydata['name']."',`building`='".$mydata['building']."',`floor`='".$mydata['floor']."',`room`='".$mydata['room']."',`description`='".$mydata['description']."',`history`='".$mydata['mac']." ".$mydata['ip']." ".$mydata['port']." ".$mydata['swname']." ".$mydata['name']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$temp['update']." \n".$temp['history']."' WHERE `mac`='".$temp['mac']."' OR `ip`=INET_ATON('".$temp['ip']."');";
                    //echo $query."<br>";
                    $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
                    //die();
                }
                //$query="UPDATE `netmap` SET `ip`=INET_ATON('".$mydata['ip']."'),`mac`='".$mydata['mac']."',`port`='".$mydata['port']."',`switch_id`='".$mydata['swname']."',`workstation`='".$mydata['name']."',`building`='".$mydata['building']."',`floor`='".$mydata['floor']."',`room`='".$mydata['room']."',`description`='".$mydata['description']."',`history`='".$mydata['mac']." ".$mydata['ip']." ".$mydata['port']." ".$mydata['swname']." ".$mydata['name']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$temp['update']." \n".$temp['history']."' WHERE `mac`='".$temp['mac']."' OR `ip`=INET_ATON('".$temp['ip']."');";
                unset($trigger);
                fhosting($data, $mydata, $trigger);
                return("OK");
            }
        }
        echo "I know you!";
    } else {
        if (!(empty($trigger))) {
            $query="INSERT INTO netmap (mac,ip,port,switch_id,workstation,building,floor,room,description) VALUES ('".$mydata['mac']."',INET_ATON('".$mydata['ip']."'),'".$mydata['port']."','".$mydata['swname']."','".$mydata['name']."','".$mydata['building']."','".$mydata['floor']."','".$mydata['room']."','".$mydata['description']."')";
            $result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
            unset($trigger);
            fhosting($data, $mydata, $trigger);
        }
        echo "What's your name?";
    }
    $mydata = $temp;

    mysql_close($db);
    return("OK");
}
?>
