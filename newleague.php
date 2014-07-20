<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
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
			$freq = "smw";
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
		$year = date("Y");
		mysqli_query($conn,"INSERT INTO `league`(`leaguename`, `frequency`, `salarycap`, `injuries`,`year`) 
				VALUES ('{$leaguename}', '{$freq}', '{$salary}', '{$injury}','{$year}')");
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
			mysqli_query($conn,"INSERT INTO `team`(`league`, `division`, `location`, `teamname`,`season_win`,`season_loss`,`total_win`,`total_loss`,`championships`,`division_win`,`division_loss`,`conf_win`,`conf_loss`,`points_for`,`points_against`,`season_tie`,`total_tie`,`logofile`) VALUES ('{$leagueid}', '{$division[$i]}', '{$location[$i]}', '{$teamname[$i]}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,'helmet.png')");
			echo "Created ".$location[$i]." ".$teamname[$i].".<br>";
			header('Location: league.php?leagueid='.$leagueid);
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../css/register.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <title>RedZone Rush - Create League</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2" id="top">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <?php
			  $teamidArray = array();
			  $locationArray = array();
			  $teamnameArray = array();
			  $leagueArray = array();
				if(mysqli_num_rows($own_team_result) == 0) {
				} else if (mysqli_num_rows($own_team_result) == 1) {
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					echo "<li><a href=\"league.php?leagueid=".$leagueArray[0]."\">League</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"dropdown\">
							<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">League <span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\" role=\"menu\">";
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
						array_push($teamidArray, $own_teamData['id']);
						array_push($locationArray, $own_teamData['location']);
						array_push($teamnameArray, $own_teamData['teamname']);
						array_push($leagueArray, $own_teamData['league']);
						echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$teamidArray[$k]."\">League ".$leagueArray[$k]."</a></li>
						<li role=\"presentation\" class=\"divider\"></li>";
					}
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					
					echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$leagueArray[count($leagueArray)-1]."\">League ".$leagueArray[count($leagueArray)-1]."</a></li>";
					echo "</ul></li>";
				}
			if(mysqli_num_rows($own_team_result) == 0) { 
					//person doesn't own a team
					echo "<li><a href=\"teamselect.php\">Get a Team</a></li>";
				} else if (mysqli_num_rows($own_team_result) == 1) {
					echo "<li><a href=\"team.php?teamid=".$teamidArray[0]."\">Team</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"dropdown\">
							<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Team <span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\" role=\"menu\">";
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"team.php?teamid=".$teamidArray[$k]."\">".$locationArray[$k]." ".$teamnameArray[$k]."</a></li>
						<li role=\"presentation\" class=\"divider\"></li>";
					}
					echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"team.php?teamid=".$teamidArray[count($teamidArray)-1]."\">".$locationArray[count($locationArray)-1]." ".$teamnameArray[count($teamnameArray)-1]."</a></li>";
					echo "</ul></li>";
				}
			  ?>
              <li>
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-md-offset-3 col-md-6">
          <div class="main">
		  <h3>Create League</h3>
            <form class="form-horizontal" method="POST" id="new-league" action="newleague.php" role="form">
              <div class="form-group">
                <label for="league-name" class="col-sm-2 control-label">League Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="league-name" name="league-name" placeholder="League Name">
                </div>
              </div>
			  <div class="form-group">
                <label for="frequency" class="col-sm-2 control-label">Frequency</label>
                <div class="col-sm-10">
				<select class="form-control" id="frequency" name="frequency">
					<option>Every day</option>
					<option>Every other day</option>
					<option>Saturday/Monday/Wednesday</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <label for="salarycap" class="col-sm-2 control-label">Salary Cap</label>
                <div class="col-sm-10">
				<select class="form-control" id="salarycap" name="salarycap">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
			  <div class="form-group">
                <label for="injuries" class="col-sm-2 control-label">Injuries</label>
                <div class="col-sm-10">
				<select class="form-control" id="injuries" name="injuries">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary" id="submit" name="submit">Create League</button>
                </div>
              </div>
            </form>
			
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
