<?php
function getdata(&$data, &$mydata, &$trigger) {
    if (isset($_POST)) {
        if (isset($_POST['logout'])) {
            header('Location: index.php?logout');
            exit;
        } elseif (isset($_POST['submit'])) {
            $repl = array("-", ":");
            $mydata['INET_NTOA(ip)'] = getip();
            $mydata['mac'] = strtolower(str_replace($repl, "", $_POST['mac']));
            $mydata['switch_id'] = $_POST['swname'];
            $mydata['port'] = $_POST['port'];
            $mydata['workstation'] = $_POST['name'];
            $mydata['building'] = $_POST['building'];
            $mydata['floor'] = $_POST['floor'];
            $mydata['description'] = $_POST['description'];
            $mydata['room'] = $_POST['room'];
            $mydata['history'] = $_POST['history'];
            $mydata['update'] = $_POST['update'];
            $trigger = "DO";
            foreach($mydata as $key => &$val){
                $val = htmlspecialchars($val, ENT_QUOTES);
            }
            unset($val); // Without it, $val will remain alive and containing link to last value of $mydata
            foreach($mydata as $key => &$val){
                $val = trim($val);
            }
            unset($val);
            $data = $mydata;
            $mydata['workstation'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['workstation']);
            $mydata['room'] = iconv('WINDOWS-1251', 'UTF-8',$mydata['room']);
        } else {
            $data['INET_NTOA(ip)'] = getip();
        }
    } else {
        $data['INET_NTOA(ip)'] = getip();
    }
}
?>
