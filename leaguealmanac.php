<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}	
if (!empty($_GET['leagueid'])) {
		$leagueid = $_GET['leagueid'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <link href="css/almanac.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Team</title>
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
              <a href="league.php?leagueid=<?php echo $leagueid;?>">League X</a>
            </p>
            <h3>League Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <?php
			  echo
                "<li>
                  <a href=\"league.php?leagueid=".$leagueid."\">Standings</a>
                </li>
                <li>
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"#\">Free Agents</a>
                </li>
                <li class=\"active\">
                  <a href=\"leaguealmanac.php?leagueid=".$leagueid."\">Almanac</a>
                </li><li>
                  <a href=\"#\">Message Board</a>
                </li>";
				?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="main">
            <h3>League Almanac</h3>
            <div class="stat-card">
              <div class="container">
                <div class="row">
                  <div class="col-md-8">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="active">
                        <a href="#team" role="tab" data-toggle="tab">Team Records</a>
                      </li>
                      <li>
                        <a href="#player" role="tab" data-toggle="tab">Player Records</a>
                      </li>
                      <li>
                        <a href="#championships" role="tab" data-toggle="tab">Championship History</a>
                      </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane active" id="team">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Record</th>
                                <th>Owner</th>
                                <th>Team</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tr>
                              <td>Longest Tenured Owner</td>
                              <td>
                                <a href="profile.php">roosevelt</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>2 seasons</td>
                            </tr>
                            <tr>
                              <td>Most Championships</td>
                              <td>
                                <a href="profile.php">roosevelt</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>2</td>
                            </tr>
                            <tr>
                              <td>Most Wins (Regular Season)</td>
                              <td>
                                <a href="profile.php">roosevelt</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>32</td>
                            </tr>
                            <tr>
                              <td>Most Wins (Postseason)</td>
                              <td>
                                <a href="profile.php">roosevelt</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>6</td>
                            </tr>
                            <tr>
                              <td>Best Win % (min 2 seasons)</td>
                              <td>
                                <a href="profile.php">roosevelt</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>1.000</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane" id="player">
                        <h4>Single-season</h4>
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Record</th>
                                <th>Player</th>
                                <th>Team</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tr>
                              <td>Passing Yards</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>5234</td>
                            </tr>
                            <tr>
                              <td>Passing TDs</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>60</td>
                            </tr>
                            <tr>
                              <td>Receiving Yards</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>2104</td>
                            </tr>
                            <tr>
                              <td>Receiving TDs</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>16</td>
                            </tr>
                            <tr>
                              <td>Rushing Yards</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>2148</td>
                            </tr>
                            <tr>
                              <td>Rushing TDs</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>18</td>
                            </tr>
                            <tr>
                              <td>Sacks</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>22</td>
                            </tr>
                            <tr>
                              <td>Interceptions</td>
                              <td>
                                <a href="player.php">Bob Jones</a>
                              </td>
                              <td>
                                <a href="team.php">New York Empire</a>
                              </td>
                              <td>15</td>
                            </tr>
                          </table>
                        </div>
                        <h4>Career</h4>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Record</th>
                              <th>Player</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tr>
                            <td>Passing Yards</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>5234</td>
                          </tr>
                          <tr>
                            <td>Passing TDs</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>60</td>
                          </tr>
                          <tr>
                            <td>Receiving Yards</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>2104</td>
                          </tr>
                          <tr>
                            <td>Receiving TDs</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>16</td>
                          </tr>
                          <tr>
                            <td>Rushing Yards</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>2148</td>
                          </tr>
                          <tr>
                            <td>Rushing TDs</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>18</td>
                          </tr>
                          <tr>
                            <td>Sacks</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>22</td>
                          </tr>
                          <tr>
                            <td>Interceptions</td>
                            <td>
                              <a href="player.php">Bob Jones</a>
                            </td>
                            <td>15</td>
                          </tr>
                        </table>
                      </div>
                      <div class="tab-pane" id="championships">
                        <div class="row">
                          <div class="col-md-10">
                            <div class="champ-card">
                              <h3>2014</h3>
                              <div class="row">
                                <div class="col-md-2">
                                  <img src="nfl-logos/19.png" />
                                </div>
                                <div class="col-md-6">
                                  <h4>
                                    <a href="team.php">New York Giants</a>
                                  </h4>
                                </div>
                                <div class="col-md-4">
                                  <h4>36</h4>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-2">
                                  <img src="nfl-logos/20.png" />
                                </div>
                                <div class="col-md-6">
                                  <h4>
                                    <a href="team.php">New York Jets</a>
                                  </h4>
                                </div>
                                <div class="col-md-4">
                                  <h4>7</h4>
                                </div>
                              </div>
							  <div class="row">
								<div class="col-md-3">
									<a href="league.php">Standings</a>
								</div>
								<div class="col-md-3">
									<a href="scores.php">Schedule</a>
								</div>
								<div class="col-md-3">
									<a href="#">Season Stats</a>
								</div>
								<div class="col-md-3">
									<a href="#">Draft</a>
								</div>
							  </div>
                            </div>
							<div class="champ-card">
                              <h3>2013</h3>
                              <div class="row">
                                <div class="col-md-2">
                                  <img src="nfl-logos/19.png" />
                                </div>
                                <div class="col-md-6">
                                  <h4>
                                    <a href="team.php">New York Giants</a>
                                  </h4>
                                </div>
                                <div class="col-md-4">
                                  <h4>36</h4>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-2">
                                  <img src="nfl-logos/20.png" />
                                </div>
                                <div class="col-md-6">
                                  <h4>
                                    <a href="team.php">New York Jets</a>
                                  </h4>
                                </div>
                                <div class="col-md-4">
                                  <h4>7</h4>
                                </div>
                              </div>
							  <div class="row">
								<div class="col-md-3">
									<a href="league.php">Standings</a>
								</div>
								<div class="col-md-3">
									<a href="scores.php">Schedule</a>
								</div>
								<div class="col-md-3">
									<a href="#">Season Stats</a>
								</div>
								<div class="col-md-3">
									<a href="#">Draft</a>
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
    </div>
  </body>
</html>
