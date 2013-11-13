<?php

include '../functions.php';
include_once('../mysql.php');

$rpi_mac = $_POST['macaddress'];
//boot_flag is 0 on the first client request from the RPI, this is requiered on RPI boot
//boot_flag set to 1 in all subseqent requests
$boot_flag = $_POST['boot_flag'];

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');

$query = "SELECT * FROM client WHERE rpi_mac='$rpi_mac' AND json_permission<>'NULL' ";

if( (int) $boot_flag == 1){
	$query .= " AND update_flag=1";
}

$clients = $oMySQL->ExecuteSQL($query);

if($oMySQL->records == 0){
	
	$clients_json = JsonHandler::encode(array());	

}elseif($oMySQL->records == 1){
	
	$clients_json = clientToJson($clients);

}else{
	
	$clients_json = clientsArrayToJson($clients);
}


$oMySQL->ExecuteSQL("UPDATE client SET update_flag=0");

HttpResponse::status(200);
HttpResponse::setContentType('application/json');
HttpResponse::setData($clients_json);
HttpResponse::send();

?>