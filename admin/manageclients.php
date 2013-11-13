<?

include '../functions.php';
include_once('../mysql.php');

$rpi_mac = $_GET['rpi_mac'];

$oMySQL = new MySQL('WifiAccess', 'root', 'dork2001');

$where_stmt['rpi_mac'] = $rpi_mac;
$clients = $oMySQL->Select('client', $where_stmt);

if($oMySQL->records == 0){

	$clients_json = JsonHandler::encode(array());

}elseif($oMySQL->records == 1){

	$clients_json = clientToJson($clients);

}else{

	$clients_json = clientsArrayToJson($clients);
}

$client_array = JsonHandler::decode($clients_json, true);

?>

<html>
<head>
<title>Wifi Manager</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script
	src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>

<link
	href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css"
	rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">

<script type="text/javascript">

	function onSuccess()
	{
		// show success icon to user.
	}

	function onFailure()
	{
		// show faliure icon to user.
	}
		
	function postPermissions()
	{
		
		$allow_access = false;

		if($("#toggle").val() == 'on'){
			$allow_access = true;
		} 

		$client_mac = $('#mac').text();
		$client_name = $('#name').text();

		alert($allow_access+ $client_mac+ $client_name);
		
		$.ajax({
			
		    type: "POST",
		    url: "/postclient.php",
		    // The key needs to match your method's input parameter (case-sensitive).
		    data: JSON.stringify(     {
		        "client": {
		            "permissions": {
		                "access": {
		                    "allow": $allow_access
		                }
		            },
		            "client_info": {
		                "mac": $client_mac,
		                "name": $client_mac
		            }
		        }
		    }),
		    contentType: "application/json; charset=utf-8",
		    dataType: "json",
		    success: function(data)		  
		    ifcon
		    
		    	{onSuccess();
		    	},
		    failure: function(errMsg) {
                  onFailure();
			   }
		}); 
	}

	</script>

</head>
<body>
	<div class="container">
		<div class="masthead">
			<h3 class="muted">
				<a><img src="../assets/img/ic_logo.png"> </a>Wifi Manager
			</h3>
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<ul class="nav">
							<li class="active"><a href="#">Manage Clients</a></li>
							<li><a href="#">Account</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Bad to  -->
		<?
		foreach ($client_array as $client){	?>

		<div data-role="collapsible" ; style="width: 400px">
			<h3 id="name">
				<?echo $client['client']['client_info']['name'];

				if(! array_key_exists('permissions' , $client['client'])){
					echo ("   (New Request)");
				}
				?>
			</h3>
			<div id="mac" style="display: none;">
				<? echo $client['client']['client_info']['mac'] ?>
			</div>

			<div>
				<label id="flip-6-label" class="ui-slider" for="flip-6"> Access
					Permission </label> <select name="toggleswitch1" id="toggle"
					data-theme="" data-role="slider">
					<option value="off">Block</option>
					<option value="on">Allow</option>
				</select>

			</div>
		</div>

		<? } ?>
	</div>


	<script src="../assets/js/jquery.js"></script>
	<script src="../assets/js/bootstrap-transition.js"></script>
	<script src="../assets/js/bootstrap-alert.js"></script>
	<script src="../assets/js/bootstrap-modal.js"></script>
	<script src="../assets/js/bootstrap-dropdown.js"></script>
	<script src="../assets/js/bootstrap-scrollspy.js"></script>
	<script src="../assets/js/bootstrap-tab.js"></script>
	<script src="../assets/js/bootstrap-tooltip.js"></script>
	<script src="../assets/js/bootstrap-popover.js"></script>
	<script src="../assets/js/bootstrap-button.js"></script>
	<script src="../assets/js/bootstrap-collapse.js"></script>
	<script src="../assets/js/bootstrap-carousel.js"></script>
	<script src="../assets/js/bootstrap-typeahead.js"></script>

</body>
</html>
