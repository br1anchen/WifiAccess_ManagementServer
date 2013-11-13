<?php

include_once('../mysql.php');

$mac = $_POST['macaddress'];
$ip = $_POST['ipaddress'];

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');
$oMySQL->ExecuteSQL("UPDATE user SET rpi_ip='$ip' WHERE rpi_mac='$mac'");

?>