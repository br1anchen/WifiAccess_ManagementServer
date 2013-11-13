<?php

include_once('../../mysql.php');

$user_id = $_POST['userId'];
$app_id = $_POST['regId'];

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');

$set_stmt['app_id'] = $app_id;
$where_stmt['email'] = $user_id;
$oMySQL->Update('user', $set_stmt, $where_stmt);

?>