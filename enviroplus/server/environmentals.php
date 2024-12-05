<?php
$servername = "MYSQL_HOST";
$database = "MYSQL_DATABASE";
$username = "MYSQL_USERNAME";
$password = "MYSQL_PASSWORD";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
else {
	$conn -> select_db($database);
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (isset($_REQUEST["query"])) {
	if ($_REQUEST["query"] == "lastRow") {
		$lastRowSQL = mysqli_query($conn, "SELECT * FROM environmentals ORDER BY id DESC LIMIT 1");
	}	
	if ($_REQUEST["query"] == "allRows") {
		$lastRowSQL = mysqli_query($conn, "SELECT * FROM environmentals ORDER BY id DESC");
	}
	$rows = array();
	while($r = mysqli_fetch_assoc($lastRowSQL)) {
    		$rows[] = $r;
	}
	print json_encode($rows);
	exit;
}
else {	
	$postValues = file_get_contents('php://input');
	
	$json = json_decode($postValues, true);	
	$sql = "INSERT INTO `environmentals` (`id`, `ip`, `gas_oxidising`, `gas_reducing`, `gas_nh3`, `gas_lux`, `gas_proximity`,`temperature`,`humidity`,`pressure`)" . 
	" VALUES (
		CURRENT_TIMESTAMP,
		'" . $_SERVER['REMOTE_ADDR'] . "',
		'" . $json['gas_oxidising'] . "',
		'" . $json['gas_reducing'] . "',
		'" . $json['gas_nh3'] . "',
		'" . $json['gas_lux'] . "',
		'" . $json['gas_proximity'] . "',
		'" . $json['temperature'] . "',
		'" . $json['humidity'] . "',
		'" . $json['pressure'] . "'
	)";
	if ($conn->query($sql) === TRUE) {
		echo $postValues;
	} 
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

$conn->close();
exit();
?>