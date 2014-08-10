<?php
	date_default_timezone_set('America/New_York');
	if (isset($_POST['offered_players']) && isset($_POST['requested_players'])) {
		$teamone = $_POST['teamone'];
		$teamtwo = $_POST['teamtwo'];
		$offered_players = $_POST['offered_players'];
		$requested_players = $_POST['requested_players'];
		$message = $_POST['message'];
		
		$offeredplayers_unique = serialize(array_unique($offered_players));
		$requestedplayers_unique = serialize(array_unique($requested_players));
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
		$trade_query = "INSERT INTO `trades` (team_one,team_two,offer_player,request_player,message,status,timestamp) VALUES ($teamone,$teamtwo,'".mysqli_real_escape_string($conn,$offeredplayers_unique)."','".mysqli_real_escape_string($conn,$requestedplayers_unique)."','".mysqli_real_escape_string($conn,$message)."','0','$timestamp')";
		$trade_result = mysqli_query($conn,$trade_query);
		if (mysqli_affected_rows($conn)==1) {
			echo "success";
		} else {
			echo "fail";
		}
		exit();
	} else {
		echo "fail";
	}
?>