<?php
	session_start();
	require_once('includes/functions.php');
	require_once('includes/getweek.php');
	require_once('includes/statcalc.php');
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
if (!empty($_GET['gameid'])) {
		$gameid = $_GET['gameid'];
	} else {
		header('Location: 404.php');
	}
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	$game_result = mysqli_query($conn,"SELECT * FROM games WHERE id=$gameid");
	if (mysqli_num_rows($game_result)==0) {
		header('Location: 404.php');
		exit();
	}
	$gameData = mysqli_fetch_array($game_result);
	$leagueid = $gameData['league'];
	$year = $gameData['year'];
	$week = $gameData['week'];
	$header = "";
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
	$home_team = $gameData['home'];
	$away_team = $gameData['away'];
	$home_score = $gameData['h_score'];
	$away_score = $gameData['a_score'];
	$h_score_q1 = $gameData['h_score_q1'];
	$h_score_q2 = $gameData['h_score_q2'];
	$h_score_q3 = $gameData['h_score_q3'];
	$h_score_q4 = $gameData['h_score_q4'];
	$h_score_OT = $gameData['a_score_OT'];
	$a_score_q1 = $gameData['a_score_q1'];
	$a_score_q2 = $gameData['a_score_q2'];
	$a_score_q3 = $gameData['a_score_q3'];
	$a_score_q4 = $gameData['a_score_q4'];
	$a_score_OT = $gameData['a_score_OT'];
	$h_win = $gameData['h_win'];
	$h_loss = $gameData['h_loss'];
	$h_tie = $gameData['h_tie'];
	$a_win = $gameData['a_win'];
	$a_loss = $gameData['a_loss'];
	$a_tie = $gameData['a_tie'];
	$gamestatus = $gameData['status'];
	
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
    <link href="css/gameresult.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.number.js"></script>
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
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-sm-3 col-lg-2">
            
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
			echo "<div class='side-bar'><div class='team-card'><h3>My team</h3><a href=\"team.php?teamid=".$myteamid."\">
              <img src=\"".$myteam_logopath."\" width=\"150\"/>
            </a> 
            <b><a href=\"team.php?teamid=".$myteamid."\">
              <p>".$myteamname."</p>
            </a><p>".$myteamrecord."</p></b>";
			echo "<p>".getWeek($leagueid)."</p></div></div>";	
			}
			?>
          
        </div>
        <div class="col-sm-9 col-lg-8">
		<ol class="breadcrumb">
		<?php
			$league_result = mysqli_query($conn,"SELECT leaguename FROM league WHERE id=$leagueid");
			$leagueData = mysqli_fetch_array($league_result);
			$leaguename = $leagueData['leaguename'];
			echo "<li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>";
			echo "<li><a href='scores.php?leagueid=".$leagueid."'>Scores & Schedule</a></li>";
		?>
		<li class='active'>Game Results</li>
		</ol>
          <div class="main">
            <div class="game-header row">
				<div class="row">
				  <div class="col-xs-6 col-sm-3">
					<div class="first-col">
					 <!-- Home Team -->
					 <?php
						$hometeam_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$home_team");
						$homeData = mysqli_fetch_array($hometeam_result);
						$home_record = "(".$h_win."-".$h_loss."-".$h_tie.")";
						$awayteam_result = mysqli_query($conn,"SELECT location,teamname,abbrev,season_win,season_loss,season_tie,logofile FROM team WHERE id=$away_team");
						$awayData = mysqli_fetch_array($awayteam_result);
						$away_record = "(".$a_win."-".$a_loss."-".$a_tie.")";
						echo "<a href='team.php?teamid=".$home_team."'><img width='65%' src='uploads/logos/".$homeData['logofile']."'></a>";
						echo "<h4><a href='team.php?teamid=".$home_team."'><b>".$homeData['location']." ".$homeData['teamname']."</b></a></h4>";
						echo "<p>".$home_record."</p>";
					 ?>
					</div>
				  </div>
				  <div class="col-sm-6 hidden-xs">
					<div class="third-col" id='bigscreens'>
					<!-- Scoring Breakdown-->
					<table class='table'>
						<thead>
							<th></th>
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>OT</th>
							<th>Final</th>
						</thead>
						<tbody>
						<?php
						echo
							"<tr>
								<td>".$homeData['abbrev']."</td>
								<td>".$h_score_q1."</td>
								<td>".$h_score_q2."</td>
								<td>".$h_score_q3."</td>
								<td>".$h_score_q4."</td>
								<td>".$h_score_OT."</td>
								<td><b>".$home_score."</b></td>
							</tr>
							<tr>
								<td>".$awayData['abbrev']."</td>
								<td>".$a_score_q1."</td>
								<td>".$a_score_q2."</td>
								<td>".$a_score_q3."</td>
								<td>".$a_score_q4."</td>
								<td>".$a_score_OT."</td>
								<td><b>".$away_score."</b></td>
							</tr>";
						?>
						</tbody>
					</table>
					<p><a href='#'>View Play-by-Play</a></p>
					</div>
				  </div>
				  <div class="col-xs-6 col-sm-3">
					<div class="sec-col">
					 <!-- Away Team -->
					<?php
						echo "<a href='team.php?teamid=".$away_team."'><img width='65%' src='uploads/logos/".$awayData['logofile']."'></a>";
						echo "<h4><a href='team.php?teamid=".$away_team."'><b>".$awayData['location']." ".$awayData['teamname']."</b></a></h4>";
						echo "<p>".$away_record."</p>";
					 ?>
					</div>
				  </div>
				  <div class="col-xs-12 col-sm-6 col-sm-offset-3 hidden-sm hidden-md hidden-lg">
					<div class="third-col" id="smallscreens">
					<!-- Scoring Breakdown-->
					<table class='table'>
						<thead>
							<th></th>
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>OT</th>
							<th>Final</th>
						</thead>
						<tbody>
						<?php
						echo
							"<tr>
								<td>".$homeData['abbrev']."</td>
								<td>".$h_score_q1."</td>
								<td>".$h_score_q2."</td>
								<td>".$h_score_q3."</td>
								<td>".$h_score_q4."</td>
								<td>".$h_score_OT."</td>
								<td><b>".$h_score."</b></td>
							</tr>
							<tr>
								<td>".$awayData['abbrev']."</td>
								<td>".$a_score_q1."</td>
								<td>".$a_score_q2."</td>
								<td>".$a_score_q3."</td>
								<td>".$a_score_q4."</td>
								<td>".$a_score_OT."</td>
								<td><b>".$a_score."</b></td>
							</tr>";
						?>
						</tbody>
					</table>
					<p><a href='#'>View Play-by-Play</a></p>
					</div>
				  </div>
				</div>
            </div>
			<div class="row">
			<h4>Detailed Stats</h4>
			<p>Click headings to show/hide</p>
			<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#summary">Scoring Summary</a></div>
						<div id='summary' class='panel-collapse collapse'>
							<table class="table">
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td width='60%'></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#teamstats">Team Statistics Comparison</a></div>
						<div id='teamstats' class='panel-collapse collapse'>
							<table class="table">
							<?php
								echo "<thead>
									<th width='33%'></th>
									<th width='33%'>".$homeData['abbrev']."</th>
									<th width='33%'>".$awayData['abbrev']."</th>
								</thead>
								<tbody>
								<tr class='trheading'>
									<td>1st Downs</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Passing</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Rushing</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>From Penalties</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>3rd Down Conv</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>4th Down Conv</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Total Plays</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Total Yards</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Passing</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Comp - Att</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Yd/Pass</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Interceptions</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Sacks - Yds</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Rushing</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Attempts</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trchild'>
									<td>Yards</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Penatlies - Yds</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Fumbles - Lost</td>
									<td></td>
									<td></td>
								</tr>
								<tr class='trheading'>
									<td>Time of Posession</td>
									<td></td>
									<td></td>
								</tr> 	
								</tbody>";
							?>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row textrow'>
					<b>Individual Stats</b>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homepass"><?php echo $homeData['location'];?> Passing</a></div>
						<div id='homepass' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Comp</th>
									<th>Att</th>
									<th>Yds</th>
									<th>TD</th>
									<th>INT</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaypass"><?php echo $awayData['location'];?> Passing</a></div>
						<div id='awaypass' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Comp</th>
									<th>Att</th>
									<th>Yds</th>
									<th>TD</th>
									<th>INT</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homerush"><?php echo $homeData['location'];?> Rushing</a></div>
						<div id='homerush' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Att</th>
									<th>Yds</th>
									<th>Yds/Att</th>
									<th>TD</th>
									<th>Long</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awayrush"><?php echo $awayData['location'];?> Rushing</a></div>
						<div id='awayrush' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Att</th>
									<th>Yds</th>
									<th>Yds/Att</th>
									<th>TD</th>
									<th>Long</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homefum"><?php echo $homeData['location'];?> Fumbles</a></div>
						<div id='homefum' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Fum</th>
									<th>Rec</th>
									<th>Lost</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awayfum"><?php echo $awayData['location'];?> Fumbles</a></div>
						<div id='awayfum' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='60%'></th>
									<th>Fum</th>
									<th>Rec</th>
									<th>Lost</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#hometck"><?php echo $homeData['location'];?> Tackling</a></div>
						<div id='hometck' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='40%'></th>
									<th>Tck</th>
									<th>MTck</th>
									<th>Sck</th>
									<th>TFL</th>
									<th>FF</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaytck"><?php echo $awayData['location'];?> Tackling</a></div>
						<div id='awaytck' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='40%'></th>
									<th>Tck</th>
									<th>MTck</th>
									<th>Sck</th>
									<th>TFL</th>
									<th>FF</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homepdef"><?php echo $homeData['location'];?> Pass Defense</a></div>
						<div id='homepdef' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='40%'></th>
									<th>Tgt</th>
									<th>Rec Alwd</th>
									<th>PDef</th>
									<th>Int</th>
									<th>Int TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaypdef"><?php echo $awayData['location'];?> Pass Defense</a></div>
						<div id='awaypdef' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='40%'></th>
									<th>Tgt</th>
									<th>Rec Alwd</th>
									<th>PDef</th>
									<th>Int</th>
									<th>Int TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homekret"><?php echo $homeData['location'];?> Kick Returns</a></div>
						<div id='homekret' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>KR</th>
									<th>KR Yds</th>
									<th>Yds/KR</th>
									<th>KR Long</th>
									<th>KR TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaykret"><?php echo $awayData['location'];?> Kick Returns</a></div>
						<div id='awaykret' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>KR</th>
									<th>KR Yds</th>
									<th>Yds/KR</th>
									<th>KR Long</th>
									<th>KR TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homekret"><?php echo $homeData['location'];?> Punt Returns</a></div>
						<div id='homekret' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>PR</th>
									<th>PR Yds</th>
									<th>Yds/PR</th>
									<th>PR Long</th>
									<th>PR TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaykret"><?php echo $awayData['location'];?> Punt Returns</a></div>
						<div id='awaykret' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>PR</th>
									<th>PR Yds</th>
									<th>Yds/PR</th>
									<th>PR Long</th>
									<th>PR TD</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homekick"><?php echo $homeData['location'];?> Kicking</a></div>
						<div id='homekick' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>FG Made</th>
									<th>FG Att</th>
									<th>Long</th>
									<th>XP Made</th>
									<th>XP Att</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaykick"><?php echo $awayData['location'];?> Kicking</a></div>
						<div id='awaykick' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>FG Made</th>
									<th>FG Att</th>
									<th>Long</th>
									<th>XP Made</th>
									<th>XP Att</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				</div>
				<div class='row statrow'>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#homepunt"><?php echo $homeData['location'];?> Punting</a></div>
						<div id='homepunt' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>Punt</th>
									<th>Yds</th>
									<th>Net</th>
									<th>Long</th>
									<th>TB</th>
									<th>In 20</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
						  </div>
					</div>
				</div>
				<div class=" col-md-6 col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#awaypunt"><?php echo $awayData['location'];?> Punting</a></div>
						<div id='awaypunt' class='panel-collapse collapse in'>
							<table class="table">
								<thead>
									<th width='30%'></th>
									<th>Punt</th>
									<th>Yds</th>
									<th>Net</th>
									<th>Long</th>
									<th>TB</th>
									<th>In 20</th>
								</thead>
								<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								</tbody>
						  </table>
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
