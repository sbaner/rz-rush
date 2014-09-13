<?php
	ob_start();
	if (isset($_POST['playerid'])) {
		$playerid =  $_POST['playerid'];
	} else {
		header('Location: 404.php');
		exit();
	}
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	if (isset($_POST['team'])) {
		$team = $_POST['team'];
		$query = "SELECT year,bonus,base,caphit,deadcap FROM contract WHERE team=$team AND player=$playerid ORDER BY year";
	} else {
		$query = "SELECT year,bonus,base,caphit,deadcap FROM contract WHERE player=$playerid ORDER BY year";
	}
	$total_result = mysqli_query($conn,$query);
	$contract_array = [];
	while ($contractData = mysqli_fetch_array($total_result,MYSQLI_ASSOC)) {
		$contract_array[] = $contractData;
	}
	
	echo json_encode($contract_array);
	exit();
	ob_flush();
?>