<?php

include '../../functions.php';
include_once('../../mysql.php');

$user_id = base64_decode($_POST['user_id']);

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');

$user = $oMySQL->ExecuteSQL("SELECT * FROM user WHERE email='$user_id'");
$rpi_mac = $user['rpi_mac'];
$clients = $oMySQL->ExecuteSQL("SELECT * FROM client WHERE rpi_mac='$rpi_mac'");

if($oMySQL->records == 0){

	$clients_json = JsonHandler::encode(array());

}elseif($oMySQL->records == 1){

	$clients_json = clientToJson($clients);

}else{

	$clients_json = clientsArrayToJson($clients);
}

HttpResponse::status(200);
HttpResponse::setContentType('application/json');
HttpResponse::setData($clients_json);
HttpResponse::send();

?>




