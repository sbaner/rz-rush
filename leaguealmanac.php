<?php
	session_start();
	require_once('includes/getweek.php');
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
		die();
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
    <title>RedZone Rush - League Almanac</title>
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
            <h3>League Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <?php
			  echo
                "<li>
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"freeagents.php?leagueid=".$leagueid."\">Free Agents</a>
                </li>
				<li>
                  <a href=\"draft.php?leagueid=".$leagueid."\">Draft</a>
                </li>
                <li class=\"active\">
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
          <div class="main">
            <h3>League Almanac</h3>
            <div class="stat-card">
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
                      <div class="tab-pane active fade in" id="team">
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
							<tbody>
							 <?php
								$tenure_result = mysqli_query($conn,"SELECT team.id,team.owner,member.username,team.location,team.teamname,team.owndate,team.seasons FROM `team` INNER JOIN `member` ON team.owner=member.id WHERE league=$leagueid ORDER BY team.owndate DESC LIMIT 1");
								$tenureData = mysqli_fetch_array($tenure_result);
								
								$champ_result = mysqli_query($conn,"SELECT team.id,team.owner,member.username,team.location,team.teamname,team.championships FROM `team` INNER JOIN `member` ON team.owner=member.id WHERE league=$leagueid ORDER BY team.championships DESC LIMIT 1");
								$champData = mysqli_fetch_array($champ_result);
								
								$win_result = mysqli_query($conn,"SELECT team.id,team.owner,member.username,team.location,team.teamname,team.total_win FROM `team` INNER JOIN `member` ON team.owner=member.id WHERE league=$leagueid ORDER BY team.total_win DESC LIMIT 1");
								$winData = mysqli_fetch_array($win_result);
								
								$po_result = mysqli_query($conn,"SELECT team.id,team.owner,member.username,team.location,team.teamname,team.po_win FROM `team` INNER JOIN `member` ON team.owner=member.id WHERE league=$leagueid ORDER BY team.po_win DESC LIMIT 1");
								$poData = mysqli_fetch_array($po_result);
								
								$winp_result = mysqli_query($conn,"SELECT team.id,team.owner,member.username,team.location,team.teamname,(team.total_win/(team.total_win+team.total_loss)) AS winp FROM `team` INNER JOIN `member` ON team.owner=member.id WHERE league=$leagueid AND seasons >= 2  AND (team.total_win > 0 OR team.total_loss > 0) ORDER BY winp DESC LIMIT 1");
								$winpData = mysqli_fetch_array($winp_result);
                           echo "<tr>
                              <td>Longest Tenured Owner</td>
                              <td>
                                <a href='profile.php?profileid=".$tenureData['owner']."'>".$tenureData['username']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$tenureData['id']."'>".$tenureData['location']." ".$tenureData['teamname']."</a>
                              </td>
                              <td>".$tenureData['seasons']." seasons</td>
                            </tr>
                            <tr>
                              <td>Most Championships</td>
                              <td>
                                <a href='profile.php?profileid=".$champData['owner']."'>".$champData['username']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$champData['id']."'>".$champData['location']." ".$champData['teamname']."</a>
                              </td>
                              <td>".$champData['championships']."</td>
                            </tr>
                            <tr>
                              <td>Most Wins (Regular Season)</td>
                              <td>
                                <a href='profile.php?profileid=".$winData['owner']."'>".$winData['username']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$winData['id']."'>".$winData['location']." ".$winData['teamname']."</a>
                              </td>
                              <td>".$winData['total_win']."</td>
                            </tr>
                            <tr>
                              <td>Most Wins (Postseason)</td>
                              <td>
                                <a href='profile.php?profileid=".$poData['owner']."'>".$poData['username']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$poData['id']."'>".$poData['location']." ".$poData['teamname']."</a>
                              </td>
                              <td>".$poData['po_win']."</td>
                            </tr>
                            <tr>
                              <td>Best Win % (min 2 seasons)</td>
                              <td>
                                <a href='profile.php?profileid=".$winpData['owner']."'>".$winpData['username']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$winpData['id']."'>".$winpData['location']." ".$winpData['teamname']."</a>
                              </td>
                              <td>".round($winpData['winp'],3)."</td>
                            </tr>";
							?>
							</tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="player">
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
							<tbody>
							<?php
								$ss_passyds_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.pass_yds AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.pass_yds DESC LIMIT 1");
								$ss_passydsData = mysqli_fetch_array($ss_passyds_result);
								
								$ss_passtd_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.pass_td AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.pass_td DESC LIMIT 1");
								$ss_passtdData = mysqli_fetch_array($ss_passtd_result);
								
								$ss_recyds_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.rec_yds AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.rec_yds DESC LIMIT 1");
								$ss_recydsData = mysqli_fetch_array($ss_recyds_result);
								
								$ss_rectd_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.rec_td AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.rec_td DESC LIMIT 1");
								$ss_rectdData = mysqli_fetch_array($ss_rectd_result);
								
								$ss_rushyds_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.rush_yds AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.rush_yds DESC LIMIT 1");
								$ss_rushydsData = mysqli_fetch_array($ss_rushyds_result);
								
								$ss_rushtd_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.rush_td AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.rush_td DESC LIMIT 1");
								$ss_rushtdData = mysqli_fetch_array($ss_rushtd_result);
								
								$ss_sack_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.sack AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.sack DESC LIMIT 1");
								$ss_sackData = mysqli_fetch_array($ss_sack_result);
								
								$ss_int_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,stats.player,stats.int AS value,stats.team,team.location,team.teamname FROM stats INNER JOIN player ON player.id=stats.player INNER JOIN team ON team.id=stats.team WHERE stats.league=1 ORDER BY stats.int DESC LIMIT 1");
								$ss_intData = mysqli_fetch_array($ss_int_result);
								
								$c_passyds_result = mysqli_query($conn,"SELECT stats.player, sum(stats.pass_yds) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_passydsData = mysqli_fetch_array($c_passyds_result);
								
								$c_passtd_result = mysqli_query($conn,"SELECT stats.player, sum(stats.pass_td) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_passtdData = mysqli_fetch_array($c_passtd_result);
								
								$c_recyds_result = mysqli_query($conn,"SELECT stats.player, sum(stats.rec_yds) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_recydsData = mysqli_fetch_array($c_recyds_result);
								
								$c_rectd_result = mysqli_query($conn,"SELECT stats.player, sum(stats.rec_td) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_rectdData = mysqli_fetch_array($c_rectd_result);
								
								$c_rushyds_result = mysqli_query($conn,"SELECT stats.player, sum(stats.rush_yds) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_rushydsData = mysqli_fetch_array($c_rushyds_result);
								
								$c_rushtd_result = mysqli_query($conn,"SELECT stats.player, sum(stats.rush_td) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_rushtdData = mysqli_fetch_array($c_rushtd_result);
								
								$c_sack_result = mysqli_query($conn,"SELECT stats.player, sum(stats.sack) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_sackData = mysqli_fetch_array($c_sack_result);
								
								$c_int_result = mysqli_query($conn,"SELECT stats.player, sum(stats.int) AS 'value', player.firstname,player.lastname FROM stats INNER JOIN player ON player.id=stats.player WHERE stats.league=$leagueid GROUP BY stats.player ORDER BY value DESC LIMIT 1");
								$c_intData = mysqli_fetch_array($c_int_result);
								
                            echo "<tr>
                              <td>Passing Yards</td>
                              <td>
                                <a href='player.php?playerid=".$ss_passydsData['player']."'>".$ss_passydsData['firstname']." ".$ss_passydsData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_passydsData['team']."'>".$ss_passydsData['location']." ".$ss_passydsData['teamname']."</a>
                              </td>
                              <td>".$ss_passydsData['value']."</td>
                            </tr>
                            <tr>
                              <td>Passing TDs</td>
                              <td>
                                <a href='player.php?playerid=".$ss_passtdData['player']."'>".$ss_passtdData['firstname']." ".$ss_passtdData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_passtdData['team']."'>".$ss_passtdData['location']." ".$ss_passtdData['teamname']."</a>
                              </td>
                              <td>".$ss_passtdData['value']."</td>
                            </tr>
                            <tr>
                              <td>Receiving Yards</td>
                              <td>
                                <a href='player.php?playerid=".$ss_recydsData['player']."'>".$ss_recydsData['firstname']." ".$ss_recydsData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_recydsData['team']."'>".$ss_recydsData['location']." ".$ss_recydsData['teamname']."</a>
                              </td>
                              <td>".$ss_recydsData['value']."</td>
                            </tr>
                            <tr>
                              <td>Receiving TDs</td>
                              <td>
                                <a href='player.php?playerid=".$ss_rectdData['player']."'>".$ss_rectdData['firstname']." ".$ss_rectdData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_rectdData['team']."'>".$ss_rectdData['location']." ".$ss_rectdData['teamname']."</a>
                              </td>
                              <td>".$ss_rectdData['value']."</td>
                            </tr>
                            <tr>
                              <td>Rushing Yards</td>
                              <td>
                                <a href='player.php?playerid=".$ss_rushydsData['player']."'>".$ss_rushydsData['firstname']." ".$ss_rushydsData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_rushydsData['team']."'>".$ss_rushydsData['location']." ".$ss_rushydsData['teamname']."</a>
                              </td>
                              <td>".$ss_rushydsData['value']."</td>
                            </tr>
                            <tr>
                              <td>Rushing TDs</td>
                              <td>
                                <a href='player.php?playerid=".$ss_rushtdData['player']."'>".$ss_rushtdData['firstname']." ".$ss_rushtdData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_rushtdData['team']."'>".$ss_rushtdData['location']." ".$ss_rushtdData['teamname']."</a>
                              </td>
                              <td>".$ss_rushtdData['value']."</td>
                            </tr>
                            <tr>
                              <td>Sacks</td>
                              <td>
                                <a href='player.php?playerid=".$ss_sackData['player']."'>".$ss_sackData['firstname']." ".$ss_sackData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_sackData['team']."'>".$ss_sackData['location']." ".$ss_sackData['teamname']."</a>
                              </td>
                              <td>".$ss_sackData['value']."</td>
                            </tr>
                            <tr>
                              <td>Interceptions</td>
                              <td>
                                <a href='player.php?playerid=".$ss_intData['player']."'>".$ss_intData['firstname']." ".$ss_intData['lastname']."</a>
                              </td>
                              <td>
                                <a href='team.php?teamid=".$ss_intData['team']."'>".$ss_intData['location']." ".$ss_intData['teamname']."</a>
                              </td>
                              <td>".$ss_intData['value']."</td>
                            </tr>
                          </table>
                        </div>
                        <h4>Career</h4>
                        <table class='table table-striped'>
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
                              <a href='player.php?playerid=".$c_passydsData['player']."'>".$c_passydsData['firstname']." ".$c_passydsData['lastname']."</a>
                            </td>
                            <td>".$c_passydsData['value']."</td>
                          </tr>
                          <tr>
                            <td>Passing TDs</td>
                            <td>
                              <a href='player.php?playerid=".$c_passtdData['player']."'>".$c_passtdData['firstname']." ".$c_passtdData['lastname']."</a>
                            </td>
                            <td>".$c_passtdData['value']."</td>
                          </tr>
                          <tr>
                            <td>Receiving Yards</td>
                            <td>
                              <a href='player.php?playerid=".$c_recydsData['player']."'>".$c_recydsData['firstname']." ".$c_recydsData['lastname']."</a>
                            </td>
                            <td>".$c_recydsData['value']."</td>
                          </tr>
                          <tr>
                            <td>Receiving TDs</td>
                            <td>
                              <a href='player.php?playerid=".$c_rectdData['player']."'>".$c_rectdData['firstname']." ".$c_rectdData['lastname']."</a>
                            </td>
                            <td>".$c_rectdData['value']."</td>
                          </tr>
                          <tr>
                            <td>Rushing Yards</td>
                            <td>
                              <a href='player.php?playerid=".$c_rushydsData['player']."'>".$c_rushydsData['firstname']." ".$c_rushydsData['lastname']."</a>
                            </td>
                            <td>".$c_rushydsData['value']."</td>
                          </tr>
                          <tr>
                            <td>Rushing TDs</td>
                            <td>
                              <a href='player.php?playerid=".$c_rushtdData['player']."'>".$c_rushtdData['firstname']." ".$c_rushtdData['lastname']."</a>
                            </td>
                            <td>".$c_rushtdData['value']."</td>
                          </tr>
                          <tr>
                            <td>Sacks</td>
                            <td>
                              <a href='player.php?playerid=".$c_sackData['player']."'>".$c_sackData['firstname']." ".$c_sackData['lastname']."</a>
                            </td>
                            <td>".$c_sackData['value']."</td>
                          </tr>
                          <tr>
                            <td>Interceptions</td>
                            <td>
                              <a href='player.php?playerid=".$c_intData['player']."'>".$c_intData['firstname']." ".$c_intData['lastname']."</a>
                            </td>
                            <td>".$c_intData['value']."</td>
                          </tr>";
						  ?>
						  </tbody>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="championships">
                        <div class="row">
                          <div class="col-md-10">
						  <?php
							$champ_result = mysqli_query($conn,"SELECT games.id,games.year,games.week,games.home,games.away,games.h_score,games.a_score,hteam.location AS hlocation,hteam.teamname AS hteamname,ateam.location AS alocation,ateam.teamname AS ateamname,hteam.logofile AS hlogofile,ateam.logofile AS alogofile,games.h_owner,games.a_owner,hmember.username AS husername,amember.username AS ausername FROM games JOIN team hteam ON hteam.id=games.home JOIN team ateam ON ateam.id=games.away JOIN member hmember ON hmember.id=games.h_owner JOIN member amember ON amember.id=games.a_owner WHERE games.league=$leagueid AND games.week=24 ORDER BY year DESC");
							while ($champData = mysqli_fetch_array($champ_result)) {
								echo "<div class='champ-card'>
                              <h3>".$champData['year']."</h3>
                              <div class='row teamrow'>
                                <div class='col-md-2'>
                                  <img width='50' src='/uploads/logos/".$champData['hlogofile']."' />
                                </div>
                                <div class='col-md-6 col-lg-4'>
                                  <h4>
                                    <a href='team.php?teamid=".$champData['home']."'>".$champData['hlocation']." ".$champData['hteamname']."</a>
                                  </h4>
                                </div>
                                <div class='col-md-2'>
                                  <h4>".$champData['h_score']."</h4>
                                </div>
								<div class='col-md-2'>
									<h5><a href='profile.php?profileid=".$champData['h_owner']."'>".$champData['husername']."</a></h5>
								</div>
                              </div>
                              <div class='row teamrow'>
                                <div class='col-md-2'>
                                  <img width='50' src='/uploads/logos/".$champData['alogofile']."' />
                                </div>
                                <div class='col-md-6 col-lg-4'>
                                  <h4>
                                    <a href='team.php?teamid=".$champData['away']."'>".$champData['alocation']." ".$champData['ateamname']."</a>
                                  </h4>
                                </div>
                                <div class='col-md-2'>
                                  <h4>".$champData['a_score']."</h4>
                                </div>
								<div class='col-md-2'>
									<h5><a href='profile.php?profileid=".$champData['a_owner']."'>".$champData['ausername']."</a></h5>
								</div>
                              </div>
							  <div class='row'>
								<div class='col-md-3'>
									<a href='league.php?leagueid=".$leagueid."'>Standings</a>
								</div>
								<div class='col-md-3'>
									<a href='scores.php?leagueid=".$leagueid."'>Schedule</a>
								</div>
								<div class='col-md-3'>
									<a href='#'>Season Stats</a>
								</div>
								<div class='col-md-3'>
									<a href='draft.php?leagueid=".$leagueid."'>Draft</a>
								</div>
							  </div>
                            </div>";
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
  </body>
</html>
