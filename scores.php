<?php
	require_once('includes/getweek.php');
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
	
	$fa_result = mysqli_query($conn,"SELECT * FROM player WHERE team=0 AND league=$leagueid");
	
	$league_result = mysqli_query($conn,"SELECT year FROM league WHERE id=$leagueid");
	$league_result = mysqli_query($conn,"SELECT * FROM league WHERE id=$leagueid");
	if (mysqli_num_rows($league_result)==0) {
		header('Location: 404.php');
		exit();
	} else {
		$leagueData = mysqli_fetch_array($league_result);
	}
	$year = $leagueData['year'];
	
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
	} else {
		$myteamid = 0;
	}
	
	//Retrieve POST data
	if (isset($_POST['week'])) {
		$week = $_POST['week'];
		if ($myteamid!=0) {
			$yg_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=".mysqli_real_escape_string($conn,$week)." AND (home=$myteamid OR away=$myteamid)"; 
		}
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=$week";
		//Week Header
		switch (true) {
			case ($week <=16):
				$header = "Week ".$week;
				break;
			case ($week <= 20):
				$preweek = $week-16;
				$header = "Preseason Week ".$preweek;
				break;
			case ($week==21):
				$header = "Wildcard Round";
				break;
			case ($week==22):
				$header = "Divisional Round";
				break;
			case ($week==23):
				$header = "Conference Championships";
				break;
			case ($week==24):
				$header = "League Championship";
				break;
		}
	} else {
		$week = 1;
		$yg_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=1 AND (home=$myteamid OR away=$myteamid)";
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND week=1";
		$header = "Week 1";
	}
	
	if (isset($_POST['team'])) {
		$schedteam = $_POST['team'];
		$score_query = "SELECT * FROM games WHERE league=$leagueid AND year=$year AND (home=".mysqli_real_escape_string($conn,$schedteam)." OR away=".mysqli_real_escape_string($conn,$schedteam).") AND week BETWEEN 1 AND 16 ORDER BY week";
		$schedteam_result = mysqli_query($conn,"SELECT location,teamname FROM team WHERE id=$schedteam");
		
		$schedData = mysqli_fetch_array($schedteam_result);
		$header = $schedData['location']." ".$schedData['teamname']." "." Schedule";
	}


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
	<link rel="shortcut icon" href="favicon.ico" />
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
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-sm-3 col-lg-2">
          <div class="side-bar">
            <div class="team-card">
            <?php 
			if ($myteamid!=0) {
			echo "<h3>My team</h3><a href=\"team.php?teamid=".$myteamid."\">
              <img src=\"".$myteam_logopath."\" width=\"150\"/>
            </a> 
            <b><a href=\"team.php?teamid=".$myteamid."\">
              <p>".$myteamname."</p>
            </a><p>".$myteamrecord."</p></b>";
			echo "<p>".getWeek($leagueid)."</p>";
			} 
			?></div>
			<h3>League Links</h3>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <?php
			  echo
                "<li class=\"active\">
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"freeagents.php?leagueid=".$leagueid."\">Free Agents</a>
                </li>
				<li>
                  <a href=\"draft.php?leagueid=".$leagueid."\">Draft</a>
                </li>
				<li>
                  <a href=\"tradeblock.php?leagueid=".$leagueid."\">Trade Block</a>
                </li>
                <li>
                  <a href=\"leaguealmanac.php?leagueid=".$leagueid."\">Almanac</a>
                </li>
				<li>
                  <a href=\"mboard.php?leagueid=".$leagueid."\">Message Board</a>
                </li>";
				?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-sm-9 col-lg-8">
		<ol class="breadcrumb">
		<?php
			$leaguename = $leagueData['leaguename'];
			echo "<li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>";
				echo "<li>Trade Block</li>";
		?>
		</ol>
          <div class="main"><button type="button" class="btn btn-primary" id="showbutton">Show/Hide Scores</button>
            <h3>Scores</h3> 
            <div class="score-card">
                <div class="row">
                  <div class="col-md-3 col-lg-2 selectdiv">
					<form method="POST" action="scores.php?leagueid=<?php echo $leagueid;?>">
						<select class="form-control" name="week" onchange="this.form.submit()">
							<option>Select Week</option>
							<optgroup label="Preseason">
								<option value="17">Week 1</option>
								<option value="18">Week 2</option>
								<option value="19">Week 3</option>
								<option value="20">Week 4</option>
							</optgroup>
							<optgroup label="Regular Season">
								<option value="1">Week 1</option>
								<option value="2">Week 2</option>
								<option value="3">Week 3</option>
								<option value="4">Week 4</option>
								<option value="5">Week 5</option>
								<option value="6">Week 6</option>
								<option value="7">Week 7</option>
								<option value="8">Week 8</option>
								<option value="9">Week 9</option>
								<option value="10">Week 10</option>
								<option value="11">Week 11</option>
								<option value="12">Week 12</option>
								<option value="13">Week 13</option>
								<option value="14">Week 14</option>
								<option value="15">Week 15</option>
								<option value="16">Week 16</option>
							</optgroup>
							<optgroup label="Playoffs">
								<option value="21">Wildcard Round</option>
								<option value="22">Divisional Round</option>
								<option value="23">Conference Championships</option>
								<option value="24">League Championship</option>
							</optgroup>
						</select>
					</form>
					<form method="POST" action="scores.php?leagueid=<?php echo $leagueid;?>">
						<select class="form-control" name="team" onchange="this.form.submit()">
						<option>Select Team</option>
						<?php
							$allteams_result = mysqli_query($conn,"SELECT id,teamname,location FROM team WHERE league=$leagueid");
							
							while ($allteamData = mysqli_fetch_array($allteams_result)) {
								echo "<option value='".$allteamData['id']."'>".$allteamData['location']." ".$allteamData['teamname']."</option>";
							}
						?>
						</select>
					</form>
                  </div>
                  <div class="col-md-9 col-lg-8">
                  <!-- Tab panes -->
                  <div id="all-scores">
                      <div class="row">
                        <div class="col-md-12">
						<div class="week-header">
							<h3><?php echo $header;?></h3>
						</div>
                            
							<?php
							if ($myteamid!=0 && !isset($schedteam) && $week<21) {
								$yourgame_result = mysqli_query($conn,$yg_query);
								$yourgameData = mysqli_fetch_array($yourgame_result);
								$yourgame = $yourgameData['id'];
								
								$yourteam_result = mysqli_query($conn,"SELECT * FROM games WHERE id=$yourgame");
								$yourteamData = mysqli_fetch_array($yourteam_result);
								$yghome = $yourteamData['home'];
								$ygaway = $yourteamData['away'];
								
								$yghome_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$yghome");
								$yghomeData = mysqli_fetch_array($yghome_result);
								$ygaway_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$ygaway");
								$ygawayData = mysqli_fetch_array($ygaway_result);
							echo "
							<div class='team-result'>
							<h3>Your Team Result</h3>
                            <div class='row teamrow'>
                              <div class='col-md-2 col-xs-3'>
                                <img width='60' src='uploads/logos/".$yghomeData['logofile']."' />
                              </div>
                              <div class='col-md-6 col-xs-6'>
                                <h4>
                                  <a href='team.php?teamid=".$yghome."'>".$yghomeData['location']." ".$yghomeData['teamname']."</a>
                                </h4>
                              </div>
                              <div class='col-md-4 col-xs-2'>
                                <h4 class='hidden-score'>".$yourteamData['h_score']."</h4>
                              </div>
                            </div>
                            <div class='row teamrow'>
                              <div class='col-md-2 col-xs-3'>
                                <img width='60' src='uploads/logos/".$ygawayData['logofile']."' />
                              </div>
                              <div class='col-md-6 col-xs-6'>
                                <h4>
                                  <a href='team.php?teamid=".$ygaway."'>".$ygawayData['location']." ".$ygawayData['teamname']."</a>
                                </h4>
                              </div>
                              <div class='col-md-4 col-xs-2'>
                                <h4 class='hidden-score'>".$yourteamData['a_score']."</h4>
                              </div>
                            </div>
							</div>";
							}
							?>
                          
                          <div class="all-results">
                            <div class="row">
							<?php
							$week_result = mysqli_query($conn,$score_query);
							$num = 1;
							while ($weekData = mysqli_fetch_array($week_result)) {
							$home = $weekData['home'];
							$away = $weekData['away'];
							$home_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$home");
							$homeData = mysqli_fetch_array($home_result);
							$away_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$away");
							$awayData = mysqli_fetch_array($away_result);
                            if (!isset($schedteam)) { echo "<div class='col-md-6'>"; } 
                                echo "<div class='result'>
                                  <div class='row'>";
								  if (isset($schedteam)) { echo "<div class='col-md-1'><b>".$weekData['week']."</b></div>"; }
									echo "<div class='col-md-3 col-xs-12'>
									<img width=";
										if (!isset($schedteam)) { echo "'40' class='smallhelm'"; }
										else { echo "'60'"; }
										echo "src='uploads/logos/".$homeData['logofile']."' />
									</div>
                                    <div class='col-md-5 col-xs-6'>
                                      <h4>
                                        <a href='team.php?teamid=".$home."'>".$homeData['location']." ".$homeData['teamname']."</a>
                                      </h4>
                                    </div>
                                    <div class='col-md-2 col-xs-4'>
                                      <h4 class='hidden-score'>".$weekData['h_score']."</h4>
                                    </div>
                                  </div>
                                  <div class='row'>
									<div class='col-md-3 ";
									if (isset($schedteam)) { echo "col-md-offset-1 "; }
									echo "col-xs-12'>
										<img class='smallhelm' width=";
										if (!isset($schedteam)) { echo "'40' class='smallhelm'"; }
										else { echo "'60'"; }
										echo "src='uploads/logos/".$awayData['logofile']."' />
									</div>
                                    <div class='col-md-5 col-xs-6'>
                                      <h4>
                                        <a href='team.php?teamid=".$away."'>".$awayData['location']." ".$awayData['teamname']."</a>
                                      </h4>
                                    </div>
                                   <div class='col-md-2 col-xs-4'>
                                      <h4 class='hidden-score'>".$weekData['a_score']."</h4>
                                    </div>
                                  </div>
								  <div class='row buttonrow'>
								  <form method='GET' action='gameresult.php' target='_blank'>
									<button class='btn btn-sm btn-simple' type='submit' name='gameid' value='".$weekData['id']."'>Box Score</button>
								  </form>
								  </div>
                                </div>";
                            if (!isset($schedteam)) {  echo "</div>"; }
							  if ($num%2==0) {
								echo "</div><div class='row'>";
							  }
							  $num++;
							  }
							  ?>
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
