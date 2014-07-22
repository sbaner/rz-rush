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
	
	$active_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active'");
	$inactive_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive'");
	$ir_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir'");
	
	//Process team actions
	if (isset($_POST['inactivate'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET status='inactive' WHERE id=$player");
		}
		$active_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir'");
	} else if (isset($_POST['activate'])) {
		$players = $_POST['playercheck'];
		if (count($players)+mysqli_num_rows($active_player_result) < 46) {
			foreach($players as $player) {
				mysqli_query($conn,"UPDATE player SET status='active' WHERE id=$player");
			}
		} else {
			for ($i=0; $i<(46-mysqli_num_rows($active_player_result)); $i++) {
				mysqli_query($conn,"UPDATE player SET status='active' WHERE id=$players[$i]");
			}
			$message = "You may only have 46 players on your active roster.";
		}
		$active_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir'");
	} else if (isset($_POST['toir'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET status='ir' WHERE id=$player");
		}
		$active_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir'");
	} else if (isset($_POST['cut'])) {
		$players = $_POST['playercheck'];
		foreach($players as $player) {
			mysqli_query($conn,"UPDATE player SET team=0,status='' WHERE id=$player");
		}
		$active_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active'");
		$inactive_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive'");
		$ir_player_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir'");
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
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-md-2">
          <div class="side-bar">
            <h3>Team Links</h3>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
			  <?php
			  if($own_team){
				echo "<li>
					<a href=\"teamedit.php?teamid=".$teamid."\">Edit Team</a>
				</li>";
				}
			  ?>
                <li class="active">
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
				<li>
                  <a href="league.php?leagueid=<?php echo $leagueid;?>">Standings</a>
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
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8">
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
				<div class="container">
					<div class="row">
						<div class="col-md-3">
								<h3 id="teamname"><?php echo $location." ".$teamname;?></h3>
								<img src="<?php echo $logopath;?>">
						</div>
						<div class="col-md-3 col-md-offset-1">
							<div class="middle-col">
							<?php
							echo "<h3>".$season_win." - ".$season_loss."</h3>";
							if ($owner!=0) {
								echo "<p>Owned by <a href=\"profile.php?profileid=".$owner."\">".$ownername."</a> since: ".$owndate."</p><p>Championships: ".$championships."</p>";
							} else {
								echo "<p>CPU Team</p>";
								if($canclaim) {
									echo "<p><form action=\"claimteam.php?teamid=".$teamid."\" method=\"POST\">
											  <button class=\"btn btn-primary\">Claim Team</button>
											</form></p>";
								}
							}							
							?>
							</div>
						</div>
						<div class="col-md-3">
						<div class="last-col">
						<?php
						if (!$own_team && $owner!=0) {
							echo "<div class=\"row\">
												<button type=\"button\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-share\"></span> Offer Trade</button>
											</div>";
						}
						?>
						</div>
						</div>
					</div>
				</div>
			</div>
            <h3>Team Details</h3>
			<p>Total Salary:</p>
			 <div class="container">
			 <div class="row">
			 <div class="col-md-9">
			 <?php
			 if (isset($message)) {
				 echo "<div class=\"alert alert-warning fade in\" id=\"message\">
				 <button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button><strong>Sorry! </strong>"
				 .$message.
				 "</div>";
			}
			 if($own_team) {
			 echo "<form name=\"active\" action=\"team.php?teamid=".$teamid."\" method=\"POST\" role=\"form\">";
			 }
			 ?>
			 <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">Active Players (<?php echo mysqli_num_rows($active_player_result);?>/46)</div>
			<?php
			 if($own_team) {
			echo "<button type=\"submit\" name=\"cut\" class=\"btn btn-danger\" onclick=\"return confirm('Cut the selected players?');\">Cut</button>
			<button type=\"submit\" name=\"toir\" class=\"btn btn-warning\" onclick=\"return confirm('Place the player(s) on injured reserve? This will free up their roster spot but you cannot reactivate the player for the rest of the season.');\">Place on IR</button>
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
				$active_qb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='QB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_qb_result); $i++) {
					$activeData = mysqli_fetch_array($active_qb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_rb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='RB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_rb_result); $i++) {
					$activeData = mysqli_fetch_array($active_rb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_fb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='FB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_fb_result); $i++) {
					$activeData = mysqli_fetch_array($active_fb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_wr_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='WR' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_wr_result); $i++) {
					$activeData = mysqli_fetch_array($active_wr_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_te_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='TE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_te_result); $i++) {
					$activeData = mysqli_fetch_array($active_te_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_g_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='G' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_g_result); $i++) {
					$activeData = mysqli_fetch_array($active_g_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_c_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='C' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_c_result); $i++) {
					$activeData = mysqli_fetch_array($active_c_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_t_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='T' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_t_result); $i++) {
					$activeData = mysqli_fetch_array($active_t_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_de_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='DE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_de_result); $i++) {
					$activeData = mysqli_fetch_array($active_de_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_dt_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='DT' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_dt_result); $i++) {
					$activeData = mysqli_fetch_array($active_dt_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_lb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='LB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_lb_result); $i++) {
					$activeData = mysqli_fetch_array($active_lb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_cb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='CB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_cb_result); $i++) {
					$activeData = mysqli_fetch_array($active_cb_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_s_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='S' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_s_result); $i++) {
					$activeData = mysqli_fetch_array($active_s_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_k_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='K' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_k_result); $i++) {
					$activeData = mysqli_fetch_array($active_k_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$active_p_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='active' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($active_p_result); $i++) {
					$activeData = mysqli_fetch_array($active_p_result);
					$playerid = $activeData['id'];
					$player_exp = $league_year - $activeData['start_year'];
					$player_name = $activeData['firstname']." ".$activeData['lastname'];
					$position = $activeData['position'];
					$health = ucfirst($activeData['health']);
					$rating = $activeData['overall_now'];
					
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
					echo "<td></td><td>";
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
			echo "<button type=\"submit\" name=\"cut\" class=\"btn btn-danger\" onclick=\"return confirm('Cut the selected players?');\">Cut</button>
			<button type=\"submit\" name=\"toir\" class=\"btn btn-warning\" onclick=\"return confirm('Place the player on injured reserve? This will free up a roster spot but you cannot reactive the player for the rest of the season.');\">Place on IR</button>
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
					$inactive_qb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='QB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_qb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_qb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_rb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='RB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_rb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_rb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_fb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='FB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_fb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_fb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_wr_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='WR' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_wr_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_wr_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_te_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='TE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_te_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_te_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_g_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='G' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_g_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_g_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_c_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='C' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_c_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_c_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_t_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='T' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_t_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_t_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_de_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='DE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_de_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_de_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_dt_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='DT' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_dt_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_dt_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_lb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='LB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_lb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_lb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_cb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='CB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_cb_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_cb_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_s_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='S' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_s_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_s_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_k_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='K' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_k_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_k_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$inactive_p_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='inactive' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($inactive_p_result); $i++) {
					$inactiveData = mysqli_fetch_array($inactive_p_result);
					$playerid = $inactiveData['id'];
					$player_exp = $league_year - $inactiveData['start_year'];
					$player_name = $inactiveData['firstname']." ".$inactiveData['lastname'];
					$position = $inactiveData['position'];
					$health = ucfirst($inactiveData['health']);
					$rating = $inactiveData['overall_now'];
					
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
					echo "<td></td><td>";
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
					$ir_qb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='QB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_qb_result); $i++) {
					$irData = mysqli_fetch_array($ir_qb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_rb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='RB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_rb_result); $i++) {
					$irData = mysqli_fetch_array($ir_rb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_fb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='FB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_fb_result); $i++) {
					$irData = mysqli_fetch_array($ir_fb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_wr_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='WR' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_wr_result); $i++) {
					$irData = mysqli_fetch_array($ir_wr_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_te_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='TE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_te_result); $i++) {
					$irData = mysqli_fetch_array($ir_te_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_g_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='G' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_g_result); $i++) {
					$irData = mysqli_fetch_array($ir_g_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_c_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='C' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_c_result); $i++) {
					$irData = mysqli_fetch_array($ir_c_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_t_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='T' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_t_result); $i++) {
					$irData = mysqli_fetch_array($ir_t_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_de_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='DE' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_de_result); $i++) {
					$irData = mysqli_fetch_array($ir_de_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_dt_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='DT' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_dt_result); $i++) {
					$irData = mysqli_fetch_array($ir_dt_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_lb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='LB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_lb_result); $i++) {
					$irData = mysqli_fetch_array($ir_lb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_cb_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='CB' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_cb_result); $i++) {
					$irData = mysqli_fetch_array($ir_cb_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_s_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='S' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_s_result); $i++) {
					$irData = mysqli_fetch_array($ir_s_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_k_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='K' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_k_result); $i++) {
					$irData = mysqli_fetch_array($ir_k_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
					if($own_team) {
						echo "<input type=\"checkbox\" name=\"playercheck[]\" id=\"playercheck\" value=\"".$playerid."\">";
					}
					echo "</td></tr>";
				}
				$ir_p_result = mysqli_query($conn,"SELECT id,start_year,firstname,lastname,position,health,overall_now FROM player WHERE team=$teamid AND status='ir' AND position='P' ORDER BY overall_now DESC");
				for ($i=0; $i < mysqli_num_rows($ir_p_result); $i++) {
					$irData = mysqli_fetch_array($ir_p_result);
					$playerid = $irData['id'];
					$player_exp = $league_year - $irData['start_year'];
					$player_name = $irData['firstname']." ".$irData['lastname'];
					$position = $irData['position'];
					$health = ucfirst($irData['health']);
					$rating = $irData['overall_now'];
					
					echo "<tr>
							<td>".$position."</td>
							<td><a href=\"player.php?playerid=".$playerid."\">".$player_name."</a></td>
							<td>".$rating."</td>
							<td>".$player_exp."</td>";
					echo "<td></td><td>";
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
			  </div></div></div>
			  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
