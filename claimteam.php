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
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
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
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		mysqli_query($conn,"INSERT INTO leagueactivity (league,team,member,action,timestamp) VALUES  ($leagueid,$teamid,$userID,'claimed','$timestamp')");
		header('Location: team.php?teamid='.$teamid);
		mysqli_query($conn,"UPDATE tutorial SET profile='1',teamselect='1',league='1',team='1' WHERE member=$userID");
	}
?>