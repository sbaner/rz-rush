<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	if (isset($_POST['submit'])){
		$leaguename = $_POST['league-name'];
		$frequency = $_POST['frequency'];
		$salarycap = $_POST['salarycap'];
		$injuries = $_POST['injuries'];
		
		if ($frequency == "Every day") {
			$freq = "ed";
		} else if ($frequency == "Every other day") {
			$freq = "eod";
		} else if ($frequency == "Saturday/Monday/Wednesday") {
			$freq = "smf";
		} else {
			echo "No frequency selected?";
			die();
		}
		
		if ($salarycap == "On") {
			$salary = "y";
		} else if ($salarycap == "Off") {
			$salary = "n";
		} else {
			echo "No salary cap selected?";
			die();
		}
		
		if ($injuries == "On") {
			$injury = "y";
		} else if ($injuries == "Off") {
			$injury = "n";
		} else {
			echo "No injury selected?";
			die();
		}
		
		$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
		mysqli_query($conn,"INSERT INTO `league`(`leaguename`, `frequency`, `salarycap`, `injuries`) 
				VALUES ('{$leaguename}', '{$freq}', '{$salary}', '{$injury}')");
		if (mysqli_affected_rows($conn) == 1) {
			echo "League create success!";
			$leagueid = mysqli_insert_id($conn);
		} else {
			echo "League create fail...";
		}
		
		//Populate team table
		$division = array("afc_east","afc_east","afc_east","afc_east","afc_north","afc_north","afc_north","afc_north",
			"afc_south","afc_south","afc_south","afc_south","afc_west","afc_west","afc_west","afc_west",
			"nfc_east","nfc_east","nfc_east","nfc_east","nfc_north","nfc_north","nfc_north","nfc_north",
			"nfc_south","nfc_south","nfc_south","nfc_south","nfc_west","nfc_west","nfc_west","nfc_west");
		$location = array("New England","New York","Miami","Buffalo","Cincinnati","Baltimore","Pittsburgh","Cleveland",
			"Indianapolis","Jacksonville","Houston","Tennessee","Denver","Kansas City","San Diego","Oakland",
			"Philadelphia","Dallas","New York","Washington","Green Bay","Chicago","Minnesota","Detroit",
			"Atlanta","Carolina","New Orleans","Tampa Bay","Seattle","San Francisco","St. Louis","Arizona");
		$teamname = array("Bulls","Ducks","Mutants","Flyers","Assassins","Bandits","Cyborgs","Bombers",
			"Heroes","Reapers","Miners","Pirates","Fireballs","Busters","Scorpions","Stingers",
			"Bullets","Blazers","Champions","Kings","Chasers","Hurricanes","Dragons","Tanks",
			"Tornadoes","Hawks","Slammers","Legends","Wild","Lightning","Sharpshooters","Commandos");
		echo "League ".$leagueid." created.<br>";
		for ($i = 0; $i < 32; $i++) {
			mysqli_query($conn,"INSERT INTO `team`(`league`, `division`, `location`, `teamname`) VALUES ('{$leagueid}', '{$division[$i]}', '{$location[$i]}', '{$teamname[$i]}')");
			echo "Created ".$location[$i]." ".$teamname[$i].".<br>";
		}
		
	} else {
		header('Location: profile.php');

	}
?>