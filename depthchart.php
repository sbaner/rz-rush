<?php
	session_start();
	require_once('includes/getweek.php');
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
	//Verify user owns team
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE id=$teamid");
	
	$teamData = mysqli_fetch_array($team_result, MYSQL_ASSOC);
	$leagueid = $teamData['league'];
	$owner = $teamData['owner'];
	if ($owner == $userID) {
			$own_team = true;
	} else {
		header('Location: profile.php');
		die();
	}
	$logopath = "uploads/logos/".$teamData['logofile'];
	$location = $teamData['location'];
	$teamname = $teamData['teamname'];
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	//Retrieve POST data and update
	
	
	
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
    <link href="../css/depthchart.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
	
    <title>RedZone Rush - Depth Chart</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row" id="top">
        <div class="col-md-2">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
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
			  
			  //Still gets league data, though league link was removed
			  
				if(mysqli_num_rows($own_team_result) == 0) {
				} else if (mysqli_num_rows($own_team_result) == 1) {
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
				} else if (mysqli_num_rows($own_team_result) > 1) {
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
						array_push($teamidArray, $own_teamData['id']);
						array_push($locationArray, $own_teamData['location']);
						array_push($teamnameArray, $own_teamData['teamname']);
						array_push($leagueArray, $own_teamData['league']);
					}
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
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
				<a href="allusers.php">Users</a>
			  </li>
              <li>
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-md-2">
          <div class="side-bar">
            <div class="team-card">
            <?php 
			$myteam_result = mysqli_query($conn,"SELECT id,division,location,teamname,season_win,season_loss,season_tie,logofile FROM `team` WHERE league=$leagueid AND owner=$userID");
			if (mysqli_num_rows($myteam_result) != 0) {
			$myteamData = mysqli_fetch_array($myteam_result, MYSQL_ASSOC);
			$myteamid = $myteamData['id'];
			$mydivision = $myteamData['division'];
			$myteamname = $myteamData['location']." ".$myteamData['teamname'];
			if ($myteamData['season_tie']==0) {
				$myteamrecord = $myteamData['season_win']."-".$myteamData['season_loss'];
			} else {
				$myteamrecord = $myteamData['season_win']."-".$myteamData['season_loss']."-".$myteamData['season_tie'];
			}
			$myteam_logopath = "uploads/logos/".$myteamData['logofile'];
			echo "<h3>My team</h3><a href=\"team.php?teamid=".$myteamid."\">
              <img src=\"".$myteam_logopath."\" width=\"150\"/>
            </a> 
            <b><a href=\"team.php?teamid=".$myteamid."\">
              <p>".$myteamname."</p>
            </a><p>".$myteamrecord."</p></b>";
			echo "<p>".getWeek($leagueid)."</p>";	
			}
			?>
            <h3>Team Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
				<li>
					<a href="teamedit.php?teamid=<?php echo $teamid;?>">Edit Team</a>
				</li>
                <li>
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
				<li>
                  <a href="league.php?leagueid=<?php echo $leagueid;?>">Standings</a>
                </li>
                <li>
                  <a href="scores.php?leagueid=<?php echo $leagueid;?>">Scores &amp; Schedule</a>
                </li><li class="active">
                  <a href="depthchart.php">Depth Chart</a>
                </li>
                <li>
                  <a href="#">Playbooks</a>
                </li>
                <li>
                  <a href="#">Stats</a>
                </li>
              </ul>
            </div>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-md-8">
          <div class="main">
		  <div class="row">
			<div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                      <li class="active dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Formation <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li role="presentation">
								<a role="menuitem" href="#offense" data-toggle="tab">General Offense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#22" data-toggle="tab">22 Personnel - 1 RB, 1 FB, 2 TE, 1 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#21" data-toggle="tab">21 Personnel - 1 RB, 1 FB, 1 TE, 2 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#20" data-toggle="tab">20 Personnel - 1 RB, 1 FB, 3 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#12" data-toggle="tab">12 Personnel - 1 RB/FB, 2 TE, 2 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#11" data-toggle="tab">11 Personnel - 1 RB/FB, 1 TE, 3 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#10" data-toggle="tab">10 Personnel - 1 RB/FB, 4 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#00" data-toggle="tab">00 Personnel - 5 WR</a>
							</li>
							<li role="presentation" class="divider"></li>
							<li role="presentation">
								<a role="menuitem" href="#defense" data-toggle="tab">General Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#43" data-toggle="tab">4-3 Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#34" data-toggle="tab">3-4 Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#eight" data-toggle="tab">Eight in the box</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#nickel" data-toggle="tab">Nickel</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#dime" data-toggle="tab">Dime</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#goalline" data-toggle="tab">Goal Line</a>
							</li>
						</ul>
                      </li>
                    </ul>
                  </div>
				  <div class="col-md-8 col-md-offset-1">
				  <h3><?php echo $location." ".$teamname;?> Depth Chart</h3>
					<div class="tab-content" id="all-scores">
						<div class="tab-pane fade in active" id="offense">
							This is the general offensive depth chart. Changes here will affect every offensive formation.
						</div>
						<div class="tab-pane fade in" id="22">
							<h4>22 Personnel</h4>
							<p>Players: 1 RB, 1 FB, 2 TE, 1 WR</p>
						</div>
						<div class="tab-pane fade in" id="21">
							<h4>21 Personnel</h4>
							<p>Players: 1 RB, 1 FB, 1 TE, 2 WR</p>
						</div>
						<div class="tab-pane fade in" id="20">
							<h4>20 Personnel</h4>
							<p>Players: 1 RB, 1 FB, 3 WR</p>
						</div>
						<div class="tab-pane fade in" id="12">
							<h4>12 Personnel</h4>
							<p>Players: 1 RB/FB, 2 TE, 2 WR</p>
						</div>
						<div class="tab-pane fade in" id="11">
							<h4>11 Personnel</h4>
							<p>Players: 1 RB/FB, 1 TE, 3 WR</p>
						</div>
						<div class="tab-pane fade in" id="10">
							<h4>10 Personnel</h4>
							<p>Players: 1 RB/FB, 4 WR</p>
						</div>
						<div class="tab-pane fade in" id="00">
							<h4>00 Personnel</h4>
							<p>Players: 5 WR</p>
						</div>
						<div class="tab-pane fade in" id="defense">
							This is the general defensive depth chart. Changes here will affect every defensive formation.
						</div>
						<div class="tab-pane fade in" id="43">
						</div>
						<div class="tab-pane fade in" id="34">
						</div>
						<div class="tab-pane fade in" id="eight">
						</div>
						<div class="tab-pane fade in" id="nickel">
						</div>
						<div class="tab-pane fade in" id="dime">
						</div>
						<div class="tab-pane fade in" id="goalline">
						</div>
					</div>
				  </div>
				</div>
			</div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>