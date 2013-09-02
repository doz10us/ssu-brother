<?php
include ("login.php");
include ("log.php");

function connect($name){
    global $nshost;
    global $nslogin;
    global $nspass;
    global $fhhost;
    global $fhlogin;
    global $fhpass;
    $fhbase = "portmap";
    $nsbase = "netmap";

    switch ($name) {
        case "fh":
            $db = mysql_connect($fhhost, $fhlogin, $fhpass) or die('Database unreachable');
            $base = $fhbase;
            break;
        case "ns":
            $db = mysql_connect($nshost, $nslogin, $nspass) or die('Database unreachable');
            $base = $nsbase;
            break;
        default:
            die();
    }

    mysql_set_charset('utf8');
    mysql_select_db($base) or die('Database not exist');
    return($db);
}

function execute($query){
    $result = mysql_query($query);
    echo "gettype($result)";
    if (is_array($result))
        return(mysql_fetch_assoc($result));
    elseif (is_bool($result))
        return($result);
    else
        die('WAT?');
}

function ns_connect(&$data) {
    $db = connect("ns");
    if (empty($data['mac'])) {
        $data['mac'] = array_pop(array_values(execute("SELECT mac FROM unetmap_host WHERE INET_NTOA(ip) = '".$data['INET_NTOA(ip)']."'")));
    }
    if (isset($data['mac'])) {
        $data['switch_id'] = array_pop(array_values(execute("SELECT name FROM unetmap_host WHERE id IN (SELECT switch_id FROM unetmap_host WHERE mac = '".$data['mac']."')")));
        $data['port'] = array_pop(array_values(execute("SELECT port FROM unetmap_host WHERE mac = '".$data['mac']."'")));
    }
    mysql_close($db);
    return("OK");
}

function fhosting(&$data, &$mydata, &$trigger) {
    $db = connect("fh");
    //$temp = $data;

    if (!(empty($data['mac']))) { $mydata['mac'] = $data['mac']; } else { $data['mac'] = $mydata['mac']; }
    if (empty($data['mac']) && empty($mydata['mac'])) {
        $temp = execute("SELECT INET_NTOA(ip),mac,port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE ip = INET_ATON('".$data['INET_NTOA(ip)']."')");
    } else {
        $temp = execute("SELECT INET_NTOA(ip),mac,port,switch_id,workstation,building,floor,room,DATE(`update`),TIME(`update`),description,history FROM netmap WHERE mac = '".$data['mac']."'");
    }
    $result = NULL;
    foreach($temp as $key => $val){
        if (!(empty($val))) { $result = "full"; break;}
    }
    if (isset($result)) {
        /*while ($row = mysql_fetch_assoc($result)) {
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
        }*/
        if (empty($data['mac'])) {
            $data['mac'] = $temp['mac'];
            ns_connect($data);
            $mydata['switch_id'] = $data['switch_id'];
            $mydata['port'] = $data['port'];
        }
        //echo "Trigger is".$trigger."<br>";
        if (!(empty($trigger))) {
            if (array_diff_assoc($mydata, $temp)){
                $query="";
                //print_r($mydata);
                //print_r($temp);
                if ($mydata['INET_NTOA(ip)'] != $temp['INET_NTOA(ip)'] ) $query .= "`ip`=INET_ATON('".$mydata['INET_NTOA(ip)']."')";
                if ($mydata['mac'] != $temp['mac']) $query .= ",`mac`='".$mydata['mac']."'";
                if ($mydata['port'] != $temp['port']) $query .= ",`port`='".$mydata['port']."'";
                if ($mydata['switch_id'] != $temp['switch_id']) $query .= ",`switch_id`='".$mydata['switch_id']."'";
                if ($mydata['workstation'] != $temp['workstation']) $query .= ",`workstation`='".$mydata['workstation']."'";
                if ($mydata['building'] != $temp['building']) $query .= ",`building`='".$mydata['building']."'";
                if ($mydata['floor'] != $temp['floor']) $query .= ",`floor`='".$mydata['floor']."'";
                if ($mydata['room'] != $temp['room']) $query .= ",`room`='".$mydata['room']."'";
                if ($mydata['description'] != $temp['description']) $query .= ",`description`='".$mydata['description']."'";
                if (!(empty($query))) {
                    execute("UPDATE `netmap` SET `ip`=INET_ATON('".$mydata['INET_NTOA(ip)']."'),`mac`='".$mydata['mac']."',`port`='".$mydata['port']."',`switch_id`='".$mydata['switch_id']."',`workstation`='".$mydata['workstation']."',`building`='".$mydata['building']."',`floor`='".$mydata['floor']."',`room`='".$mydata['room']."',`description`='".$mydata['description']."',`history`='".$mydata['mac']." ".$mydata['INET_NTOA(ip)']." ".$mydata['port']." ".$mydata['switch_id']." ".$mydata['workstation']." ".$mydata['building']." ".$mydata['floor']." ".$mydata['room']." ".$mydata['description']." ".$temp['DATE(`update`)']." ".$temp['TIME(`update`)']." \n".$temp['history']."' WHERE `mac`='".$temp['mac']."' OR `ip`='INET_ATON('".$temp['INET_NTOA(ip)']."')';");
                }
                unset($trigger);
                //$string = $mydata['INET_NTOA(ip)']." ".$mydata['mac']." ".$mydata['switch_id'].":".$mydata['port']." ".$mydata['workstation']." ".$mydata['building']."-".$mydata['floor']."-".$mydata['room']." ".$mydata['description'];
                getlog($mydata['INET_NTOA(ip)']." ".$mydata['mac']." ".$mydata['switch_id'].":".$mydata['port']." ".$mydata['workstation']." ".$mydata['building']."-".$mydata['floor']."-".$mydata['room']." ".$mydata['description'], "5");
                fhosting($data, $mydata, $trigger);
                return("OK");
            }
        }
        //$string = $temp['INET_NTOA(ip)']." ".$temp['mac']." ".$temp['switch_id'].":".$temp['port']." ".$temp['workstation']." ".$temp['building']."-".$temp['floor']."-".$temp['room']." ".$temp['description'];
        getlog($temp['INET_NTOA(ip)']." ".$temp['mac']." ".$temp['switch_id'].":".$temp['port']." ".$temp['workstation']." ".$temp['building']."-".$temp['floor']."-".$temp['room']." ".$temp['description'], "3");
        //echo "I know you!";
    } else {
        if (!(empty($trigger))) {
            //$query="INSERT INTO netmap (mac,ip,port,switch_id,workstation,building,floor,room,description) VALUES ('".$mydata['mac']."',INET_ATON('".$mydata['INET_NTOA(ip)']."'),'".$mydata['port']."','".$mydata['switch_id']."','".$mydata['workstation']."','".$mydata['building']."','".$mydata['floor']."','".$mydata['room']."','".$mydata['description']."')";
            //$result = mysql_query($query) or trigger_error(mysql_errno() . ' ' .mysql_error() . ' query: ' . $query);
            execute("INSERT INTO netmap (mac,ip,port,switch_id,workstation,building,floor,room,description) VALUES ('".$mydata['mac']."',INET_ATON('".$mydata['INET_NTOA(ip)']."'),'".$mydata['port']."','".$mydata['switch_id']."','".$mydata['workstation']."','".$mydata['building']."','".$mydata['floor']."','".$mydata['room']."','".$mydata['description']."')");
            unset($trigger);
            //$string = $mydata['INET_NTOA(ip)']." ".$mydata['mac']." ".$mydata['switch_id'].":".$mydata['port']." ".$mydata['workstation']." ".$mydata['building']."-".$mydata['floor']."-".$mydata['room']." ".$mydata['description'];
            getlog($mydata['INET_NTOA(ip)']." ".$mydata['mac']." ".$mydata['switch_id'].":".$mydata['port']." ".$mydata['workstation']." ".$mydata['building']."-".$mydata['floor']."-".$mydata['room']." ".$mydata['description'], "5");
            fhosting($data, $mydata, $trigger);
            return("OK");
        }
        getlog("", "4");
        //echo "What's your name?";
    }
    $mydata = $temp;

    mysql_close($db);
    return("OK");
}
?>
