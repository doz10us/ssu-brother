<?php
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
            $trigger = "DO";//htmlspecialchars($_POST['trigger'], ENT_QUOTES);
            //echo "Trigger is ".$trigger."<br>";
            //die();
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
