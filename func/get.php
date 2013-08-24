<?php
function getip() {	// This will get real IPv4 and  return as $ip
  if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
    $ip = getenv("HTTP_CLIENT_IP");

  elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");

  elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    $ip = getenv("REMOTE_ADDR");

  elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    $ip = $_SERVER['REMOTE_ADDR'];

  else
    $ip = "unknown";
  $ip = '212.193.33.198';
  return($ip);
}

function checkip($ip) {	// ---- This'll check if IP's from our pool
	$ssu = preg_match('/^212\.193\.\d{1,3}\.\d{1,3}\z/', $ip);
	//$live = preg_match('/^192\.168\.\d{1,3}\.\d{1,3}\z/', $ip);
	if ($ssu) {
		$valid = "1";
	} else {
		$valid = "0";
	}

	return($valid);
}

function getdata(&$data, &$mydata, &$trigger) {
    if (isset($_POST)) {
        if (isset($_POST['logout'])) {
            header('Location: index.php?logout');
            exit;
        } elseif (isset($_POST['submit'])) {
            $mydata['ip'] = getip(); //$_POST['ip'];
            $mydata['mac'] = $_POST['mac'];
            $mydata['swname'] = $_POST['swname'];
            $mydata['port'] = $_POST['port'];
            $mydata['name'] = $_POST['name'];
            $mydata['building'] = $_POST['building'];
            $mydata['floor'] = $_POST['floor'];
            $mydata['description'] = $_POST['description'];
            $mydata['room'] = $_POST['room'];
            $mydata['history'] = $_POST['history'];
            $mydata['update'] = $_POST['update'];
            $trigger = htmlspecialchars($_POST['trigger'], ENT_QUOTES);
            echo "Trigger is ".$trigger."<br>";
            die();
            foreach($mydata as $key => &$val){
                $val = htmlspecialchars($val, ENT_QUOTES);
            }
            unset($val); // Without it, $val will remain alive and containing link to last value of $mydata
            foreach($mydata as $key => &$val){
                $val = trim($val);
            }
            unset($val);
            $data = $mydata;
            $mydata['name'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['name']);
            $mydata['room'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['room']);
        } else {
            $data['ip'] = getip();
        }
    } else {
        $data['ip'] = getip();
    }
}
?>
