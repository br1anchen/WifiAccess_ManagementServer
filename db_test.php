<?php 

include_once('mysql.php');
include 'functions.php';

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');
$client = $oMySQL->ExecuteSQL("Select * from client where name='Alf'"); 

$client_json = clientToJson($client);

print_r($client_json);

?>