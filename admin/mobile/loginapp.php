<?php
include_once('../../mysql.php');

$user_email = $_POST['email'];
$user_pwd = $_POST['password'];

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');

$where_stmt['email'] = base64_decode($user_email);
$where_stmt['password'] = base64_decode($user_pwd);

$user = $oMySQL->Select('user', $where_stmt);

if(is_null($user['email']) == true){
	HttpResponse::status(401);
}else{
	HttpResponse::status(200);
}

HttpResponse::setContentType('text/html');
HttpResponse::setData('');
HttpResponse::send();

?>
