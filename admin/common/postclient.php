<?php

include '../../functions.php';
include_once('../../mysql.php');

$data = file_get_contents('php://input');

$client_array = JsonHandler::decode($data, true);
$client_perms_array = array('permissions' => $client_array['client']['permissions']);
$client_mac = $client_array['client']['client_info']['mac'];
$client_perms_json = json_encode($client_perms_array);

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');
$set_stmt = array();
$set_stmt['json_permission'] = $client_perms_json;
$set_stmt['update_flag'] = 1;

$where_stmt['mac'] = $client_mac; 

$oMySQL->Update('client', $set_stmt, $where_stmt);

?>
