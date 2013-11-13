<?php

include_once('mysql.php');


class JsonHandler {

	protected static $_messages = array(
			JSON_ERROR_NONE => 'No error has occurred',
			JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
			JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX => 'Syntax error',
			JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
	);

	public static function encode($value, $options = 0) {
		$result = json_encode($value, $options);

		if($result) {
			return $result;
		}

		throw new RuntimeException(static::$_messages[json_last_error()]);
	}

	public static function decode($json, $assoc = false) {
		$result = json_decode($json, $assoc);

		if($result) {
			return $result;
		}

		throw new RuntimeException(static::$_messages[json_last_error()]);
	}

}

function array_push_associative(&$arr) {
	$args = func_get_args();
	$ret = 0;
	foreach ($args as $arg) {
		if (is_array($arg)) {
			foreach ($arg as $key => $value) {
				$arr[$key] = $value;
				$ret++;
			}
		}else{
			$arr[$arg] = "";
		}
	}
	return $ret;
}

function client_has_permissions($client_array){
	
	$key_perm = 'json_permission';
	
	if(array_key_exists($key_perm, $client_array)){
		if($client_array[$key_perm] == NULL){
			return false;
		}else{
			return true;
		}

	}

}


function clientsArrayToJson($clients_array){

	$clients = array();

	foreach ($clients_array as $row){

		$client = array( 'client' => array(
				'client_info' => array (
						'name' => $row['name'],
						'mac' => $row['mac']
				)
		)
		);

		//if client has permission, push these to 'client'
		//WHY the fuch doesnt this work, 'as row' is not an assicataed array?
		$client_perms = $row['json_permission'];
		if(!isnull($client_perms)){
			$perms_array = JsonHandler::decode($client_perms, true);
			array_push_associative($client['client'], $perms_array);
		};

		array_push($clients, $client);
	}

	return JsonHandler::encode($clients);
}

function clientToJson($client_array){

	$client = array( 'client' => array(
			'client_info' => array (
					'name' => $client_array['name'],
					'mac' => $client_array['mac']
			)
	)
	);

	//if client has permission, push these to 'client'
	$client_perms = $client_array['json_permission'];
	if(!isnull($client_perms)){
		$perms_array = JsonHandler::decode($client_perms, true);
		array_push_associative($client['client'], $perms_array);
	};

	unset($client_array);
	$client_array = array();
	array_push($client_array, $client);

	return JsonHandler::encode($client_array);
}


function isnull($data)
{
  /** only if you need this
  if (is_string($data)) {
    $data = strtolower($data);
  }
  */
  switch ($data) {

    case 'unknown': // continue
    case 'undefined': // continue
    case 'null': // continue
    case 'NULL': // continue
    case NULL:
      return true;
  }
  return false;
}




/**
 * The following function will send a GCM notification using curl.
 *
 * @param $apiKey [string] The Browser API key string for your GCM account
 * @param $registrationIdsArray [array] An array of registration ids to send this notification to
 * @param $messageData [array] An named array of data to send as the notification payload
 */
function sendNotification( $apiKey, $registrationIdsArray, $messageData )
{
	$headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . $apiKey);
	$data = array(
			'data' => $messageData,
			'registration_ids' => $registrationIdsArray
	);

	$ch = curl_init();

	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}



function sendPushNotification($rpi_mac, $client_mac, $client_name)
{

	$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');
	$where_stmt['rpi_mac'] = $rpi_mac;
	$user = $oMySQL->Select('user', $where_stmt);

	$registrationId = $user['app_id'];
	$message = "client_accessrequest";
	$contentTitle = "client_info";
	$client_name = $client_name;
	$client_mac = $client_mac;
	$apiKey = "AIzaSyDm6hSWtKmX7virnMrbvGZ_0ctkkGM04xI";

	$response = sendNotification(
			$apiKey,
			array($registrationId),
			array('message' => $message, 'contentTitle' => $contentTitle, "client_name" => $client_name, "client_mac" => $client_mac ) );

	return $response;
}

?>