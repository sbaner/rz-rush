<?php
	session_start();
	date_default_timezone_set('America/New_York');
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	if (!empty($_GET['teamid'])) {
		$teamid = $_GET['teamid'];
	} else {
		header('Location: 404.php');
	}
	
	$teamunowned = false;
	$canclaim = false;
	$teamlimit = false;
	$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
	$team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE id=$teamid");
	if(mysqli_num_rows($team_result) == 0) {
		//no such team
		header('Location: 404.php');
	}
	$teamData = mysqli_fetch_array($team_result, MYSQL_ASSOC);
	$ownerID = $teamData['owner'];
	$leagueid = $teamData['league'];
	if ($ownerID ==0) {
		$teamunowned = true;
	} else {
		$teamunowned = false;
		echo "Team is already owned. <a href=\"javascript:history.back()\">Back</a>";
		die();
	}
	
	$leaguecheck_result = mysqli_query($conn,"SELECT * FROM `team` WHERE league=$leagueid AND owner=$userID");
	if(mysqli_num_rows($leaguecheck_result) == 0) {
			//No other teams in the same league owned
			$canclaim = true;
		} else {
			echo "You already own a team in this league! <a href=\"javascript:history.back()\">Back</a>";
			die();
		}
	$allteam_result = mysqli_query($conn,"SELECT * FROM `team` WHERE owner=$userID");
	if(mysqli_num_rows($allteam_result) < 5) {
		$teamlimit = true;
	} else {
		echo "You already own the maximum number of teams! <a href=\"javascript:history.back()\">Back</a>";
		die();
	}
	
	if ($teamunowned && $canclaim && $teamlimit) {
		$date = date("m")."/".date("d")."/".date("y");
		$claim_result = mysqli_query($conn,"UPDATE team SET owner='$userID',owndate='$date' WHERE id=$teamid");
		header('Location: team.php?teamid='.$teamid);
	}
?>