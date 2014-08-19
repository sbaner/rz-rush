<?php
	require_once('includes/getweek.php');
	session_start();
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
	$own_team = false;
	$canclaim = false;
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
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
		
		$leaguecheck_result = mysqli_query($conn,"SELECT * FROM `team` WHERE league=$leagueid AND owner=$userID");
		if(mysqli_num_rows($leaguecheck_result) == 0) {
			//No other teams in the same league owned
			$canclaim = true;
		}
	}
	$league_result = mysqli_query($conn,"SELECT * FROM `league` WHERE id=$leagueid");
	$leagueData = mysqli_fetch_array($league_result, MYSQL_ASSOC);
	$league_year = $leagueData['year'];
	
	$active_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active'");
	$inactive_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive'");
	$ir_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir'");
	
	//Tutorial
	$tut_result = mysqli_query($conn,"SELECT league,team,teamedit FROM tutorial WHERE member=$userID");
	if (mysqli_num_rows($tut_result)==1) {
		$tutData = mysqli_fetch_array($tut_result);
		$leaguetut = $tutData['league'];
		$teamtut = $tutData['team'];
		$teamedittut = $tutData['teamedit'];
		if ($leaguetut==0) {
			mysqli_query($conn,"UPDATE tutorial SET profile='1',teamselect='1',league='1' WHERE member=$userID");
		}
	}
	//Process team actions
	if (isset($_POST['inactivate'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET status='inactive' WHERE id=$player");
		}
		$active_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir'");
	} else if (isset($_POST['activate'])) {
		$players = $_POST['playercheck'];
		if (count($players)+mysqli_num_rows($active_player_result) <= 46) {
			foreach($players as $player) {
				mysqli_query($conn,"UPDATE player SET status='active' WHERE id=$player");
			}
		} else {
			for ($i=0; $i<(46-mysqli_num_rows($active_player_result)); $i++) {
				mysqli_query($conn,"UPDATE player SET status='active' WHERE id=$players[$i]");
			}
			$message = "You may only have 46 players on your active roster.";
		}
		$active_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir'");
	} else if (isset($_POST['toir'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET status='ir' WHERE id=$player");
		}
		$active_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir'");
	} else if (isset($_POST['cut'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET team=0,status='' WHERE id=$player");
			mysqli_query($conn,"UPDATE contract SET base=0 WHERE player=$player AND team=$teamid");
			$caphit_result = mysqli_query($conn,"SELECT caphit FROM contract WHERE player=$player AND team=$teamid");
			$caphitData = mysqli_fetch_array($caphit_result);
			$caphit = $caphitData['caphit'];
			mysqli_query($conn,"UPDATE contract SET deadcap=$caphit WHERE player=$player AND team=$teamid");
			
			$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
			mysqli_query($conn,"INSERT INTO leagueactivity (league,team,action,player,timestamp) VALUES ($leagueid,$teamid,'cut',$player,'$timestamp')");
		}
		$active_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir'");
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
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/sorttable.js"></script>
	<script>
	$( document ).ready(function() {
		<?php
		if ($teamtut==0) {
		echo "$('#claimteam').popover({
				trigger: 'manual',
				placement: 'bottom',
				container: 'body',
				template: '<div class=\"popover\" role=\"tooltip\"><div class=\"arrow\"></div><h3 class=\"popover-title\" style=\"font-weight:bold;\"></h3><div class=\"popover-content\"></div></div>'
		});
		$('#claimteam').popover('show');";
		}
		
		if ($teamedittut==0) {
		echo "$('#teamedit').popover({
				trigger: 'manual',
				placement: 'right',
				container: 'body',
				template: '<div class=\"popover\" role=\"tooltip\"><div class=\"arrow\"></div><h3 class=\"popover-title\" style=\"font-weight:bold;\"></h3><div class=\"popover-content\"></div></div>'
		});
		
		$('#teamedit').popover('show');";
		}
		?>
	});
	</script>
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
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-sm-3 col-lg-2">
          <div class="side-bar">
		  <?php
		  if (!$own_team) {
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
			echo "<div class=\"team-card\"><h3>My team</h3><a href=\"team.php?teamid=".$myteamid."\">
              <img src=\"".$myteam_logopath."\" width=\"150\"/>
            </a> 
            <b><a href=\"team.php?teamid=".$myteamid."\">
              <p>".$myteamname."</p>
            </a><p>".$myteamrecord."</p></b>";
			echo "<p>".getWeek($leagueid)."</p></div>";	
			}
			}
		  ?>
            <h3>Team Links</h3>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
			  <?php
			  if($own_team){
				echo "<li>
					<a href=\"teamedit.php?teamid=".$teamid."\"  data-toggle='popover' data-title='Congrats!' data-content=\"You now own a team! Click here to edit the team's name and logo. If you need more help with managing your team, click the 'Help' link at the top of the screen.\" id='teamedit'>Edit Team</a>
				</li>";
				}
			  ?>
                <li class="active">
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
				<?php
				if($own_team){
					$newtrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE `status`='0' AND `team_two`=$teamid ORDER BY timestamp DESC");
					$alltrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE `team_two`=$teamid ORDER BY timestamp DESC");
					$senttrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE AND `team_one`=$teamid ORDER BY timestamp DESC");
					echo "<li>
					  <a href=\"trades.php?teamid=".$teamid."\">Trades ";
					  if (mysqli_num_rows($newtrade_result) != 0) {
						$num_unread = mysqli_num_rows($newtrade_result);
						echo "<span class=\"badge\">".$num_unread."</span>";
					}
					echo "</a>
					</li>";
				}
				?>
                <li>
                  <a href="scores.php?leagueid=<?php echo $leagueid;?>">Scores &amp; Schedule</a>
                </li>
				<?php 
				if ($own_team) {
				echo "<li>
                  <a href=\"depthchart.php?teamid=".$teamid."\">Depth Chart</a>
                </li>";
				}
				?>
                <li>
                  <a href="#">Playbooks</a>
                </li>
                <li>
                  <a href="#">Stats</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-sm-9 col-lg-8">
		<ol class="breadcrumb">
		<?php
		$leaguename = $leagueData['leaguename'];
		echo "
		  <li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>
		  <li class=\"active\">".$location." ".$teamname."</li>";
		?>
		</ol>
          <div class="main">
			<div class="team-header">
					<div class="row">
						<div class="col-lg-3 col-md-5 col-sm-6">
								<h3 id="teamname"><?php echo $location." ".$teamname;?></h3>
								<img src="<?php echo $logopath;?>">
						</div>
						<div class="col-md-3">
							<div class="middle-col">
							<?php
							echo "<h3>".$season_win." - ".$season_loss."</h3>";
							if ($owner!=0) {
								echo "<p>Owned by <a href=\"profile.php?profileid=".$owner."\">".$ownername."</a> since: ".$owndate."</p><p>Championships: ".$championships."</p>";
							} else {
								echo "<p>CPU Team</p>";
								if($canclaim) {
									echo "<p><form action=\"claimteam.php?teamid=".$teamid."\" method=\"POST\">
											  <button class=\"btn btn-primary\" data-toggle='popover' data-content=\"Claim your new team!\" id='claimteam'>Claim Team</button>
											</form></p>";
								}
							}							
							?>
							</div>
						</div>
						<div class="col-md-3">
						<div class="last-col">
						<?php
						$sameleague = false;
						$sameleague_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$leagueid AND owner=$userID");
						if (mysqli_num_rows($sameleague_result)==1) {
							$sameleague = true;
							$sameleagueData = mysqli_fetch_array($sameleague_result);
							$sameleagueteam = $sameleagueData['id'];
						}
						if (!$own_team && $owner!=0 && $sameleague) {
							
							echo "
							<form action='newtrade.php?teamid=".$sameleagueteam."&and=".$teamid."' method='POST'>
												<button type=\"submit\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-share\"></span> Offer Trade</button>
												</form>";
						}
						?>
						</div>
						</div>
					</div>
			</div>
			 <div class="row">
			 <div class="col-md-12 col-lg-10">
			 <?php
			 if (isset($message)) {
				 echo "<div class=\"alert alert-warning fade in\" id=\"message\">
				 <button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button><strong>Sorry! </strong>"
				 .$message.
				 "</div>";
			} ?>
			<div class="panel-group" id="accordion">
				  <div class="panel panel-default">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						  Click to view salary information
						</a>
					  </h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse">
					  <div class="panel-body">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Year</th>
										<th>Dead Money</th>
										<th>Total Spending (includes dead money)</th>
										<th>Total Cap</th>
									</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0;$i<6;$i++) {
									$contract_year = $league_year + $i;
									$total_result = mysqli_query($conn,"SELECT * FROM contract WHERE team=$teamid AND year=$contract_year");
									$total_salary = 0;
									$deadcap = 0;
									while ($totalData = mysqli_fetch_array($total_result)) {
										$total_salary = $total_salary + $totalData['bonus'] + $totalData['base'];
										$deadcap = $deadcap + $totalData['deadcap'];
									}
									echo "<tr><td>".$contract_year."</td>
										<td>$".number_format($deadcap)."</td>
										<td>$".number_format($total_salary)."</td>
										<td>$130,000,000</td>";
								}
								?>
								</tbody>
							</table>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			<?php
			 if($own_team) {
			 echo "<form name=\"active\" action=\"team.php?teamid=".$teamid."\" method=\"POST\" role=\"form\">";
			 }
			 ?>
			 <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">Active Players (<?php echo mysqli_num_rows($active_player_result);?>/46)</div>
			<?php
			 if($own_team) {
			echo "<button type='button' class=\"btn btn-danger\" data-toggle='modal' data-target='#cutModal'>Cut</button>
				<div class='modal fade' id='cutModal' tabindex='-1' role='dialog' aria-labelledby='ConfirmCut' aria-hidden='true'>
				  <div class='modal-dialog modal-sm'>
					<div class='modal-content'>
					  <div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
						<h4 class='modal-title' id='cutModalLabel'>Confirm action</h4>
					  </div>
					  <div class='modal-body'>
						Cut the selected players?
					  </div>
					  <div class='modal-footer'>
						<button type=\"submit\" name=\"cut\" class=\"btn btn-danger\">Cut</button>
					  </div>
					</div>
				  </div>
				</div>
			<button type='button' class=\"btn btn-warning\" data-toggle='modal' data-target='#irModal'>Place on IR</button>
			<div class='modal fade' id='irModal' tabindex='-1' role='dialog' aria-labelledby='ConfirmIR' aria-hidden='true'>
			  <div class='modal-dialog modal-sm'>
				<div class='modal-content'>
				  <div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
					<h4 class='modal-title' id='cutModalLabel'>Confirm action</h4>
				  </div>
				  <div class='modal-body'>
					Place the player(s) on injured reserve? This will free up their roster spot but you cannot reactivate the player for the rest of the season.
				  </div>
				  <div class='modal-footer'>
					<button type=\"submit\" name=\"toir\" class=\"btn btn-warning\">Place on IR</button>
				  </div>
				</div>
			  </div>
			</div>
			<button type=\"submit\" name=\"inactivate\" class=\"btn btn-info\">Inactivate</button>";
			}
			?>
            <!-- Table -->
			<div class="table-responsive">
            <table class="table sortable">
                <thead>
                  <tr>
                    <th width="10%">Pos</th>
                    <th width="20%">Name</th>
                    <th width="10%">Rating</th>
					<th width="10%">Exp</th>
					<th width="20%">Health</th>
                    <th width="20%">Salary</th>
					<th class="sorttable_nosort" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
				<?php
				$active_qb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='QB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_qb_result); $i++) {
					$activeData = mysqli_fetch_array($active_qb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_qb_result) > 0) {
					$activeData = mysqli_fetch_array($active_qb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_rb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='RB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_rb_result); $i++) {
					$activeData = mysqli_fetch_array($active_rb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_rb_result) > 0) {
					$activeData = mysqli_fetch_array($active_rb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_fb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='FB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_fb_result); $i++) {
					$activeData = mysqli_fetch_array($active_fb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}  if (mysqli_num_rows($active_fb_result) > 0) {
					$activeData = mysqli_fetch_array($active_fb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_wr_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='WR' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_wr_result); $i++) {
					$activeData = mysqli_fetch_array($active_wr_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_wr_result) > 0) {
					$activeData = mysqli_fetch_array($active_wr_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_te_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='TE' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_te_result); $i++) {
					$activeData = mysqli_fetch_array($active_te_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_te_result) > 0) {
					$activeData = mysqli_fetch_array($active_te_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_g_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='G' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_g_result); $i++) {
					$activeData = mysqli_fetch_array($active_g_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_g_result) > 0) {
					$activeData = mysqli_fetch_array($active_g_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_c_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='C' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_c_result); $i++) {
					$activeData = mysqli_fetch_array($active_c_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_c_result) > 0) {
					$activeData = mysqli_fetch_array($active_c_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_t_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='T' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_t_result); $i++) {
					$activeData = mysqli_fetch_array($active_t_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_t_result) > 0) {
					$activeData = mysqli_fetch_array($active_t_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_de_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='DE' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_de_result); $i++) {
					$activeData = mysqli_fetch_array($active_de_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_de_result) > 0) {
					$activeData = mysqli_fetch_array($active_de_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_dt_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='DT' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_dt_result); $i++) {
					$activeData = mysqli_fetch_array($active_dt_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_dt_result) > 0) {
					$activeData = mysqli_fetch_array($active_dt_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_lb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='LB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_lb_result); $i++) {
					$activeData = mysqli_fetch_array($active_lb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_lb_result) > 0) {
					$activeData = mysqli_fetch_array($active_lb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_cb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='CB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_cb_result); $i++) {
					$activeData = mysqli_fetch_array($active_cb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_cb_result) > 0) {
					$activeData = mysqli_fetch_array($active_cb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_s_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='S' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_s_result); $i++) {
					$activeData = mysqli_fetch_array($active_s_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_s_result) > 0) {
					$activeData = mysqli_fetch_array($active_s_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_k_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='K' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($active_k_result); $i++) {
					$activeData = mysqli_fetch_array($active_k_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($active_k_result) > 0) {
					$activeData = mysqli_fetch_array($active_k_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_p_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='active' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_p_result); $i++) {
					$activeData = mysqli_fetch_array($active_p_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				?>
                </tbody>
              </table>
			  </div></div>
			  <?php
			 if($own_team) {
			 echo "</form>
			  <form name=\"inactive\" action=\"team.php?teamid=".$teamid."\" method=\"POST\" role=\"form\">";
			  }
			  ?>
			<div class="panel panel-info">
            <!-- Default panel contents -->
            <div class="panel-heading">Inactive Players (<?php echo mysqli_num_rows($inactive_player_result);?>)</div>
			<?php
			 if($own_team) {
			echo "<button type='button' class=\"btn btn-danger\" data-toggle='modal' data-target='#iacutModal'>Cut</button>
				<div class='modal fade' id='iacutModal' tabindex='-1' role='dialog' aria-labelledby='ConfirmCut' aria-hidden='true'>
				  <div class='modal-dialog modal-sm'>
					<div class='modal-content'>
					  <div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
						<h4 class='modal-title' id='cutModalLabel'>Confirm action</h4>
					  </div>
					  <div class='modal-body'>
						Cut the selected players?
					  </div>
					  <div class='modal-footer'>
						<button type=\"submit\" name=\"cut\" class=\"btn btn-danger\">Cut</button>
					  </div>
					</div>
				  </div>
				</div>
			<button type='button' class=\"btn btn-warning\" data-toggle='modal' data-target='#iairModal'>Place on IR</button>
			<div class='modal fade' id='iairModal' tabindex='-1' role='dialog' aria-labelledby='ConfirmIR' aria-hidden='true'>
			  <div class='modal-dialog modal-sm'>
				<div class='modal-content'>
				  <div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
					<h4 class='modal-title' id='cutModalLabel'>Confirm action</h4>
				  </div>
				  <div class='modal-body'>
					Place the player(s) on injured reserve? This will free up their roster spot but you cannot reactivate the player for the rest of the season.
				  </div>
				  <div class='modal-footer'>
					<button type=\"submit\" name=\"toir\" class=\"btn btn-warning\">Place on IR</button>
				  </div>
				</div>
			  </div>
			</div>
			<button type=\"submit\" name=\"activate\" class=\"btn btn-success\">Activate</button>";
			}
			?>
            <!-- Table -->
			<div class="table-responsive">
            <table class="table sortable">
                <thead>
                  <tr>
                    <th width="10%">Pos</th>
                    <th width="20%">Name</th>
                    <th width="10%">Rating</th>
					<th width="10%">Exp</th>
					<th width="20%">Health</th>
                    <th width="20%">Salary</th>
					<th class="sorttable_nosort" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
					$inactive_qb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='QB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_qb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_qb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_qb_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_qb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_rb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='RB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_rb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_rb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_rb_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_rb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_fb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='FB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_fb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_fb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}  if (mysqli_num_rows($inactive_fb_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_fb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_wr_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='WR' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_wr_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_wr_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_wr_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_wr_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_te_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='TE' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_te_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_te_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_te_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_te_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_g_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='G' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_g_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_g_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_g_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_g_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_c_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='C' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_c_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_c_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_c_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_c_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_t_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='T' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_t_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_t_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_t_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_t_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_de_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='DE' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_de_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_de_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_de_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_de_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_dt_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='DT' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_dt_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_dt_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_dt_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_dt_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_lb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='LB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_lb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_lb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_lb_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_lb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_cb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='CB' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_cb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_cb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_cb_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_cb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_s_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='S' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_s_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_s_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_s_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_s_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_k_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='K' ORDER BY overall_now DESC");
				for ($i=1; $i < mysqli_num_rows($inactive_k_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_k_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				} if (mysqli_num_rows($inactive_k_result) > 0) {
					$inactiveData = mysqli_fetch_array($inactive_k_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr class=\"bottom-border\">
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_p_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='inactive' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_p_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_p_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					if ($health != "Healthy") {
						echo "<td class=\"injured\">".$health."</td>";
					} else {
						echo "<td>Healthy</td>";
					}
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
					?> 
                </tbody>
              </table>
			 </div>
			</div>
			  <?php
			 if($own_team) {
			  echo "</form>
			  <form name=\"injured\" action=\"team.php?teamid=".$teamid."\" method=\"POST\" role=\"form\">";
			  }
			  ?>
			  <div class="panel panel-danger">
            <!-- Default panel contents -->
            <div class="panel-heading">Injured Reserve (<?php echo mysqli_num_rows($ir_player_result);?>)</div>
			<?php
			 if($own_team) {
			echo "<button type=\"submit\" name=\"cut\" class=\"btn btn-danger\" onclick=\"return confirm('Cut the selected players?');\">Cut</button>";
			}
			?>
            <!-- Table -->
			<div class="table-responsive">
            <table class="table sortable">
                <thead>
                  <tr>
                    <th width="10%">Pos</th>
                    <th width="20%">Name</th>
                    <th width="10%">Rating</th>
					<th width="10%">Exp</th>
                    <th width="20%">Salary</th>
					<th class="sorttable_nosort" width="10%"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
					$ir_qb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='QB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_qb_result); $i++) {
					$irData = mysqli_fetch_array($ir_qb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_rb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='RB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_rb_result); $i++) {
					$irData = mysqli_fetch_array($ir_rb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_fb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='FB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_fb_result); $i++) {
					$irData = mysqli_fetch_array($ir_fb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_wr_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='WR' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_wr_result); $i++) {
					$irData = mysqli_fetch_array($ir_wr_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_te_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='TE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_te_result); $i++) {
					$irData = mysqli_fetch_array($ir_te_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_g_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='G' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_g_result); $i++) {
					$irData = mysqli_fetch_array($ir_g_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_c_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='C' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_c_result); $i++) {
					$irData = mysqli_fetch_array($ir_c_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_t_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='T' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_t_result); $i++) {
					$irData = mysqli_fetch_array($ir_t_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_de_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='DE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_de_result); $i++) {
					$irData = mysqli_fetch_array($ir_de_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_dt_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='DT' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_dt_result); $i++) {
					$irData = mysqli_fetch_array($ir_dt_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_lb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='LB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_lb_result); $i++) {
					$irData = mysqli_fetch_array($ir_lb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_cb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='CB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_cb_result); $i++) {
					$irData = mysqli_fetch_array($ir_cb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_s_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='S' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_s_result); $i++) {
					$irData = mysqli_fetch_array($ir_s_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_k_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='K' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_k_result); $i++) {
					$irData = mysqli_fetch_array($ir_k_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_p_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.team=$teamid AND status='ir' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_p_result); $i++) {
					$irData = mysqli_fetch_array($ir_p_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					$salary_result = mysqli_query($conn,"SELECT bonus,base FROM contract WHERE player=$playerid AND year=$league_year");
					$salaryData = mysqli_fetch_array($salary_result);
					$salary = "$".number_format($salaryData['base']+$salaryData['bonus']);
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td>".$salary."</td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
					?>
                </tbody>
              </table></div></div>
			  
			  <?php
			 if($own_team) {
			 echo "</form>";
			 }
			 ?>
			  </div></div>
			  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
