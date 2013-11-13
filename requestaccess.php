<?php

include 'functions.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$client_mac = $_POST['client_mac'];
$rpi_mac = $_POST['rpi_mac'];

require_once('classes/class.phpmailer.php');

$phpmailer = new PHPMailer();

if(!empty($client_mac) && !empty($rpi_mac)){
        
        $oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');
        $query = "INSERT INTO client (mac, name, rpi_mac, email) VALUES ('$client_mac','$name','$rpi_mac','$email') ON DUPLICATE KEY UPDATE mac = VALUES(mac), name = VALUES(name), rpi_mac = VALUES(rpi_mac), email = VALUES(email)";
        $oMySQL->ExecuteSQL($query);
        
        $response = sendPushNotification($rpi_mac, $client_mac, $name);

	$mailSQL = new MySQL('WifiAccess','root','dork2001');
        $where['rpi_mac'] = $rpi_mac;
        $user = $mailSQL->Select('user', $where);
	if(!is_null($user['email'])){
                $phpmailer->IsSMTP();
                $phpmailer->Host       = "ssl://smtp.gmail.com";
                $phpmailer->SMTPAuth   = true;
                $phpmailer->Port       = 465;
                $phpmailer->Username   = "wifi.access.manager@gmail.com";
                $phpmailer->Password   = "ntnu2013";

                $phpmailer->SetFrom('wifi.access.manager@gmail.com', 'Wifi Manager');

                $phpmailer->Subject    = "New Request User";
                $phpmailer->Body = "There is a new user($name) request for wifi access!";

                $phpmailer->AddAddress($user['email']);

                if(!$phpmailer->Send()) {
                        echo "Mailer Error: " . $phpmailer->ErrorInfo;
                } else {
                        echo "Message sent!";
                }
        }

}
?>

<html>
<head>
<meta charset="utf-8">
<title>Request Access</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<link href="./assets/css/bootstrap.css" rel="stylesheet">
<link href="./assets/css/bootstrap.custom.css" rel="stylesheet">

</head>
<body>
        <?
        if(!empty($client_mac)){ ?>

        <div class="content">
                <h2 class="form-signin-heading">
                        <a><img src="./assets/img/ic_logo.png"> </a>The network manager is
                        now deciding your access permissions
                </h2>
        </div>

        <?        }else {
                echo 'Unauth';
        }
        ?>
</body>
</html>
