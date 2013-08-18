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
	if ($_POST['logout']) {
		header('Location: index.php?logout');
		exit;
	} elseif ($_POST['submit']) {
		$mydata['ip'] = getip(); //$_REQUEST['ip'];
		$mydata['mac'] = $_REQUEST['mac'];
		$mydata['swname'] = $_REQUEST['swname'];
		$mydata['port'] = $_REQUEST['port'];
		$mydata['name'] = $_REQUEST['name'];
		$mydata['building'] = $_REQUEST['building'];
		$mydata['floor'] = $_REQUEST['floor'];
		$mydata['description'] = $_REQUEST['description'];
		$mydata['room'] = $_REQUEST['room'];
		$mydata['history'] = $_REQUEST['history'];
		$mydata['update'] = $_REQUEST['update'];
		$data = $mydata;
		$trigger = htmlspecialchars($_REQUEST['trigger']);
		foreach($mydata as $key => &$val){
			$val = htmlspecialchars($val);
		}
		unset($val); // Without it, $val will remain alive and containing link to last value of $mydata
	} else {
		$data['ip'] = getip();
		$trigger = "SELECT";
	}
}
?>
