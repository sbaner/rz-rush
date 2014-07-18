<?php
	session_start();
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
	$own_team = false;
	$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	$team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE id=$teamid");
	
	if(mysqli_num_rows($team_result) == 0) {
		//no such team
		header('Location: 404.php');
	} else {
		//get team info
		$teamData = mysqli_fetch_array($team_result, MYSQL_ASSOC);
		$leagueid = $teamData['league'];
		$location = $teamData['location'];
		$teamname = $teamData['teamname'];
		$owner = $teamData['owner'];
		$season_win = $teamData['season_win'];
		$season_loss = $teamData['season_loss'];
		$season_tie = $teamData['season_tie'];
		$championships = $teamData['championships'];
		$logopath = "uploads/logos/".$teamData['logofile'];
		$owndate = $teamData['owndate'];
		
		$member_result = mysqli_query($conn,"SELECT * FROM `member` WHERE id=$owner");
		$memberData = mysqli_fetch_array($member_result, MYSQL_ASSOC);
		$ownername = $memberData['username'];
		
		if ($owner == $userID) {
			$own_team = true;
		}
		
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <link href="css/team.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Team</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2">
          <a href="./index.php">
            <img class="logo" src="./logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li>
                <a href="profile.php">Profile</a>
              </li>
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
					echo "<li class=\"active\"><a href=\"team.php?teamid=".$teamidArray[0]."\">Team</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"active dropdown\">
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
      <div class="row">
        <div class="col-md-2">
          <div class="side-bar">
            <div class="team-card">
            <h3>My team</h3>
            <a href="#">
              <img src="nfl-logos/19.png" />
            </a> 
            <a href="#">
              <p>New York Giants</p>
            </a>
			<p>Week 1</p>
            <p>Next game: @<a href="#">DAL</a></p>
			<p><a href="league.php?leagueid=<?php echo $leagueid."\">League ".$leagueid;?></a></p>
            <h3>Team Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <li class="active">
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
                <li>
                  <a href="scores.php?leagueid=<?php echo $leagueid;?>">Scores &amp; Schedule</a>
                </li><li>
                  <a href="#">Depth Chart</a>
                </li>
                <li>
                  <a href="#">Playbooks</a>
                </li>
                <li>
                  <a href="#">Stats</a>
                </li>
                <li>
                  <a href="#">Injuries</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="main">
			<div class="team-header">
				<div class="container">
					<div class="row">
						<div class="col-md-3">
								<h3><?php echo $location." ".$teamname;?></h3>
								<img src="<?php echo $logopath;?>">
						</div>
						<div class="col-md-3 col-md-offset-1">
							<div class="middle-col">
							<?php
							if ($owner!=0) {
								echo "<p>Owned by <a href=\"profile.php".$owner."\">".$ownername."</a> since: ".$owndate."</p>";
							} else {
								echo "<p>CPU Team</p>"; 
							}							
							?>
								<p>Championships: <?php echo $championships;?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
            <h3>Team Details</h3>
			<p>Total Salary: $125,345,345</p>
			<p>Player count: 1/46 Active, 0 Inactive, 1/53 Total</p>
			 <div class="container">
			 <div class="row">
			 <div class="col-md-9">
			 <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">Roster</div>
            <!-- Table -->
			<div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th width="10%">Pos</th>
                    <th width="20%">Name</th>
					<th width="20%">Status</th>
                    <th width="15%">Exp</th>
                    <th width="20%">Salary</th>
					<th width="15%"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr class="injured">
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Torn ACL</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr>
                  
                </tbody>
              </table></div></div></div></div></div>
			  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
