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
	
	$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <link href="css/scores.css" rel="stylesheet" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
	<script>document.write('<style>.hidden-score { display: none; }</style>');</script>
	<script>
		$(document).ready(function(){
			$("#showbutton").click(function(){
				$(".hidden-score").fadeToggle();
			});
		});
	</script>
	
    <title>RedZone Rush - Scores &amp; Schedule</title>

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
                <li class=\"active\">
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"#\">Free Agents</a>
                </li>
                <li>
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
          <div class="main"><button type="button" class="btn btn-primary" id="showbutton">Show/Hide Scores</button>
            <h3>Scores</h3> 
            <div class="score-card">
              <!-- Nav tabs -->
              <div class="container">
                <div class="row">
                  <div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                      <li class="active dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Select Week <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li role="presentation">
								<a role="menuitem" href="#pre1" data-toggle="tab">Preseason 1</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#pre2" data-toggle="tab">Preseason 2</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#pre3" data-toggle="tab">Preseason 3</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#pre4" data-toggle="tab">Preseason 4</a>
							</li>
							<li role="presentation" class="divider"></li>
							<li role="presentation">
								<a role="menuitem" href="#reg1" data-toggle="tab">Week 1</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg2" data-toggle="tab">Week 2</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg3" data-toggle="tab">Week 3</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg4" data-toggle="tab">Week 4</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg5" data-toggle="tab">Week 5</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg6" data-toggle="tab">Week 6</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg7" data-toggle="tab">Week 7</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg8" data-toggle="tab">Week 8</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg9" data-toggle="tab">Week 9</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg10" data-toggle="tab">Week 10</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg11" data-toggle="tab">Week 11</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg12" data-toggle="tab">Week 12</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg13" data-toggle="tab">Week 13</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg14" data-toggle="tab">Week 14</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg15" data-toggle="tab">Week 15</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#reg16" data-toggle="tab">Week 16</a>
							</li>
							<li role="presentation" class="divider"></li>
							<li role="presentation">
								<a role="menuitem" href="#playoffs1" data-toggle="tab">Wildcard Round</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#playoffs2" data-toggle="tab">Division Round</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#playoffs3" data-toggle="tab">Conference Championships</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#playoffs4" data-toggle="tab">League Championship</a>
							</li>
						</ul>
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-8">
                  <!-- Tab panes -->
                  <div class="tab-content" id="all-scores">
				  
                    <div class="tab-pane fade in active" id="pre1">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Preseason 1</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pre2">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Preseason 2</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pre3">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Preseason 3</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pre4">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Preseason 4</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg1">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 1</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg2">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 2</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg3">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 3</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg4">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 4</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg5">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 5</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg6">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 6</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg7">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 7</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg8">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 8</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg9">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 9</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg10">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 10</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg11">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 11</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg12">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 12</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg13">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 13</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg14">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 14</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg15">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 15</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="reg16">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Week 16</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="playoffs1">
                      <div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Wildcard Round</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
							<div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="playoffs2">
						<div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Divisional Round</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
							<div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
					</div>
                    <div class="tab-pane fade" id="playoffs3">
						<div class="row">
                        <div class="col-md-10">
						<div class="week-header">
							<h3>Conference Championships</h3>
						</div>
                          <div class="team-result">
                            <h3>Your Team Result</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
                              </div>
                            </div>
                          </div>
                          <div class="all-results">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="result">
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 1</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">43</h4>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-md-8">
                                      <h4>
                                        <a href="team.php">Team 2</a>
                                      </h4>
                                    </div>
                                    <div class="col-md-4">
                                      <h4 class="hidden-score">8</h4>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
					</div>
                    <div class="tab-pane fade" id="playoffs4">
						<div class="row">
                        <div class="col-md-10">
                          <div class="team-result">
						  <h3>League Championship</h3>
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
                                <h4 class="hidden-score">36</h4>
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
                                <h4 class="hidden-score">7</h4>
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
