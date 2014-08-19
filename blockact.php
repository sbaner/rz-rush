<?php
	ob_start();
	if (isset($_POST['addplayer'])) {
		$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
		$league = $_POST['league'];
		$team = $_POST['team'];
		$player = $_POST['addtb'];
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		if ($_POST['msgbox']!="") {
			$message = $_POST['msgbox'];
			$tb_query = "INSERT INTO tradeblock (league,team,player,message,timestamp) VALUES ($league,$team,$player,'".mysqli_real_escape_string($conn,$message)."','$timestamp')";
		} else {
			$tb_query = "INSERT INTO tradeblock (league,team,player,timestamp) VALUES ($league,$team,$player,'$timestamp')";
		}
		
		$tb_result = mysqli_query($conn,$tb_query);
		header('Location: trades.php?teamid='.$team.'&tab=block');
	} else if (isset($_POST['removeplayer'])) {
		$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
		$player = $_POST['removeplayer'];
		$team = $_POST['team'];
		mysqli_query($conn,"DELETE FROM tradeblock WHERE player=$player");
		header('Location: trades.php?teamid='.$team.'&tab=block');
	} else {
		header('Location: 404.php');
		exit();
	}
	ob_flush();
?>