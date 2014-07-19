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
	$league_result = mysqli_query($conn,"SELECT * FROM `league` WHERE id=$leagueid");
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	if(mysqli_num_rows($league_result) == 0) {
		//no such league
		header('Location: 404.php');
	} else {
		//get league info
		$leagueData = mysqli_fetch_array($league_result, MYSQL_ASSOC);
		$leaguename = $leagueData['leaguename'];
		$frequency = $leagueData['frequency'];
		$salarycap = $leagueData['salarycap'];
		$injuries = $leagueData['injuries'];
		$users = $leagueData['users'];
		$year = $leagueData['year'];
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
    <link href="css/league.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - League</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
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
				if(mysqli_num_rows($own_team_result) == 0) {
				} else if (mysqli_num_rows($own_team_result) == 1) {
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					echo "<li class=\"active\"><a href=\"league.php?leagueid=".$leagueArray[0]."\">League</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"active dropdown\">
							<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">League <span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\" role=\"menu\">";
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
						array_push($teamidArray, $own_teamData['id']);
						array_push($locationArray, $own_teamData['location']);
						array_push($teamnameArray, $own_teamData['teamname']);
						array_push($leagueArray, $own_teamData['league']);
						echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$teamidArray[$k]."\">League ".$leagueArray[$k]."</a></li>
						<li role=\"presentation\" class=\"divider\"></li>";
					}
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					
					echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$leagueArray[count($leagueArray)-1]."\">League ".$leagueArray[count($leagueArray)-1]."</a></li>";
					echo "</ul></li>";
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
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
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
            <p>Next game: @<a href="#">DAL</a></p>
			<p><a href="league.php?leagueid=<?php echo $leagueid;?>">League X</a></p>
			
            <h3>League Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
			  <?php
			  echo
                "<li class=\"active\">
                  <a href=\"league.php?leagueid=".$leagueid."\">Standings</a>
                </li>
                <li>
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
          <div class="main">
            <h3><?php echo $leaguename;?></h3>
            <p><?php echo $year." ";?>Standings, regular season</p>
            <div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC East</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
					<th width="8%">Owner</th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">GB</th>
                    <th width="8%">PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "afc_east";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$teamowner = $teamData['owner'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					$owner_result = mysqli_query($conn, "SELECT * from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>".$ownername."</td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC North</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "afc_north";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC South</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "afc_south";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC West</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "afc_west";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div>
            </div>
            <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC East</div>
            <!-- Table --><div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "nfc_east";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC North</div>
            <!-- Table --><div class="table-responsive">
            
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "nfc_north";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC South</div>
            <!-- Table --><div class="table-responsive">
            
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "nfc_south";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC West</div>
            <!-- Table --><div class="table-responsive">
            
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" >PF</th>
                    <th width="8%" >PA</th>
                    <th width="8%" >CONF</th>
                    <th width="8%" >DIV</th>
                    <th width="8%" >STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				  $division = "nfc_west";
				  $result = mysqli_query($conn, "SELECT * FROM `team` WHERE `league`={$leagueid} AND `division`='$division' ORDER BY season_win,season_tie,division_win,conf_win,points_for DESC");
				  for ($i = 1; $i < 5; $i++) {
					$teamData = mysqli_fetch_array($result, MYSQL_ASSOC);
					$teamid = $teamData['id'];
					$location = $teamData['location'];
					$teamname = $teamData['teamname'];
					$season_win = $teamData['season_win'];
					$season_loss = $teamData['season_loss'];
					$season_tie = $teamData['season_tie'];
					if ($season_win + $season_loss + $season_tie != 0) {
						$pct = ($season_win+($season_tie/2))/($season_win + $season_loss + $season_tie);
					} else {
						$pct = 0;
					}
					$division_win = $teamData['division_win'];
					$division_loss = $teamData['division_loss'];
					$conf_win = $teamData['conf_win'];
					$conf_loss = $teamData['conf_loss'];
					$points_for = $teamData['points_for'];
					$points_against = $teamData['points_against'];
					$logopath = "uploads/logos/".$teamData['logofile'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
                    <td>".$season_win."</td>
                    <td>".$season_loss."</td>
                    <td>".$season_tie."</td>
                    <td>".$pct."</td>
                    <td>0</td>
                    <td >".$points_for."</td>
                    <td >".$points_against."</td>
                    <td >".$conf_win."-".$conf_loss."</td>
                    <td >".$division_win."-".$division_loss."</td>
                    <td >-</td>
                  </tr>";
				  }
				  ?>
                </tbody>
              </table></div></div>
			  <div class="container">
				<div class="row">
					<div class="col-md-3">
						<h4>League Info</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						Frequency: <?php 
							if ($frequency=="ed") {
								echo "Every day";
							} else if ($frequency=="eod") {
								echo "Every other day";
							} else if ($frequency=="smf") {
								echo "Saturdays, Mondays, Wednesdays";
							}
						?>
					</div><div class="col-md-1">
						Salary cap: <?php
							if ($salarycap=="y") {
								echo "Yes";
							} else if ($salarycap=="n") {
								echo "No";
							}
						?>
					</div><div class="col-md-1">
						Injuries: <?php
							if ($injuries=="y") {
								echo "Yes";
							} else if ($injuries=="n") {
								echo "No";
							}
						?>
					</div><div class="col-md-2">
						Non-CPU players: <?php
							echo $users;
						?>/32
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
