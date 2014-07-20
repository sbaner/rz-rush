<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
if (!empty($_GET['playerid'])) {
		$playerid = $_GET['playerid'];
	} else {
		header('Location: 404.php');
	}
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
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
    <link href="css/player.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <title>RedZone Rush</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row" id="top">
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
        <div class="col-md-2">
          <div class="side-bar">
            <div class="team-card">
            <h3>My team</h3>
            <a href="team.php">
              <img src="nfl-logos/19.png" />
            </a> 
            <a href="team.php">
              <p>New York Giants</p>
            </a>
            <p>Week 1</p>
            <p>Next game: @ 
            <a href="#">DAL</a></p>
            <p>
              <a href="league.php">League X</a>
            </p></div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="main">
            <div class="player-header">
              <div class="container">
                <div class="row">
                  <div class="col-md-2">
                    <div class="first-col">
                      <h3 class="name">Bob Jones</h3>
                      <img src="face/face.jpg" />
                      <h4 class="position">QB, 
                      <a href="team.php">New York Giants</a></h4>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="sec-col">
                      <h4>
                        <b>Player Info</b>
                      </h4>
                      <p>Height: 6&#39; 3&quot;</p>
                      <p>Weight: 210 lbs</p>
                      <p>Drafted: Round 4, Pick 16, NYG</p>
                      <p>Experience: 4th season</p>
                      <p>Contract: 1 year, $125,345,345</p>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="third-col">
                      <h4>
                        <b>Awards</b>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="stats">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                  <a href="#careerstats" role="tab" data-toggle="tab">Career Stats</a>
                </li>
                <li>
                  <a href="#gamelogs" role="tab" data-toggle="tab">Game Logs</a>
                </li>
                <li>
                  <a href="#progression" role="tab" data-toggle="tab">Player Progression</a>
                </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="careerstats">Stats go here</div>
                <div class="tab-pane" id="gamelogs">Game Logs go here</div>
                <div class="tab-pane" id="progression">
                  <div class="container">
                    <div class="row">
                      <div class="col-md-9">
                        <div class="progression">
                            <div class="row">
                              <div class="col-md-1"><p>Overall:</p></div>
							  <div class="col-md-1"><p>80</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 60%;">60</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 20%">+20</div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Accuracy:</p></div>
							  <div class="col-md-1"><p>94</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 80%;">87</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="7"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 7%">+7</div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Speed:</p></div>
                              <div class="col-md-1"><p>36</p></div>
							  <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="36" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 36%;">36</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">+0</span>
                                  </div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Strength:</p></div>
							  <div class="col-md-1"><p>59</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="46" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 46%;">46</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="13"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 13%">+13</div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
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
