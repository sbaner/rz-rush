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
	$leagues_result = mysqli_query($conn,"SELECT * FROM `league`");
	$num_leagues = mysqli_num_rows($leagues_result);
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
    <title>RedZone Rush - Join a League</title>
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
        <div class="col-md-offset-3 col-md-6">
          <div class="main">
		  <h3>Join a league</h3>
		  <div class="table-responsive">
		  <table class="table">
                <thead>
                  <tr>
                    <th width="20%">League</th>
                    <th width="20%">Frequency</th>
                    <th width="20%">Salary Cap</th>
                    <th width="20%">Injuries</th>
                    <th width="20%">Users</th>
                  </tr>
				 </thead>
				 <tbody>
				 <?php
				  for ($i=0; $i<$num_leagues; $i++) {
				  echo "<tr>";
					$leagueData = mysqli_fetch_array($leagues_result, MYSQL_ASSOC);
					$leagueid = $leagueData['id'];
					echo "<td><a href=\"league.php?leagueid=".$leagueData['id']."\">".$leagueData['leaguename']."</a></td><td>";
					if ($leagueData['frequency']=="ed") {
						echo "Every day</td><td>";
					} else if ($leagueData['frequency']=="eod") {
						echo "Every other day</td><td>";
					} else if ($leagueData['frequency']=="smw") {
						echo "Saturday/Monday/Wednesday</td><td>";
					}
					if ($leagueData['salarycap']=="y") {
						echo "On</td><td>";
					} else if ($leagueData['salarycap']=="n") {
						echo "Off</td><td>";
					} 
					if ($leagueData['injuries']=="y") {
						echo "On</td><td>";
					} else if ($leagueData['injuries']=="n") {
						echo "Off</td><td>";
					}
					$leagueusers_result = mysqli_query($conn,"SELECT * FROM `team` WHERE league=$leagueid AND owner!=0");
					$users = mysqli_num_rows($leagueusers_result);
					echo "Users: ".$users."/32</td>";
					echo "</tr>";
				  }
				  ?>
				 </tbody>
		</table>
		  </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>