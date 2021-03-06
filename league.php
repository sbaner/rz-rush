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
		$year = $leagueData['year'];
	}
	
	$tut_result = mysqli_query($conn,"SELECT teamselect,league FROM tutorial WHERE member=$userID");
	if (mysqli_num_rows($tut_result)==1) {
		$tutData = mysqli_fetch_array($tut_result);
		$teamselect = $tutData['teamselect'];
		$leaguetut = $tutData['league'];
		if ($teamselect==0) {
			mysqli_query($conn,"UPDATE tutorial SET profile='1',teamselect='1' WHERE member=$userID");
		}
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
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<?php
	if ($leaguetut==0) {
	echo "<script>
	$( document ).ready(function() {
		$('#getteam').popover({
				trigger: 'manual',
				placement: 'top',
				container: 'body',
				template: '<div class=\"popover\" role=\"tooltip\"><div class=\"arrow\"></div><h3 class=\"popover-title\" style=\"font-weight:bold;\"></h3><div class=\"popover-content\"></div></div>'
		});
		$('#getteam').popover('show');
	});
	</script>";
	}
	?>
    <title>RedZone Rush - League</title>
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
        <div class="col-md-3 col-lg-2">
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
        <div class="col-md-9 col-lg-8">
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
                    <th width="15%"<?php if ($leaguetut==0) { echo "data-toggle='popover' data-content=\"Look for an unowned team (one with a CPU as an owner), and click on its name.\""; }?> id='getteam'></th>
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
				  $division = "afc_north";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
              <div class="panel-heading">AFC South</div>
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
				  $division = "afc_south";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
              <div class="panel-heading">AFC West</div>
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
				  $division = "afc_west";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
            <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC East</div>
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
				  $division = "nfc_east";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
				  $division = "nfc_north";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
				  $division = "nfc_south";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
				  $division = "nfc_west";
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
					
					$owner_result = mysqli_query($conn, "SELECT id,username from member WHERE id='$teamowner'");
					$memberData = mysqli_fetch_array($owner_result, MYSQL_ASSOC);
					$ownername = $memberData['username'];
					$ownerid = $memberData['id'];
					
					echo "<tr>
                    <td>
                      <a href=\"team.php?teamid=".$teamid."\"><img src=\"".$logopath."\" height=\"40px\" /></a>
					  
                    </td>
                    <td><a href=\"team.php?teamid=".$teamid."\">".$location."
                    <br />".$teamname."</a></td>
					<td>";
					if ($ownerid!=0) { echo "<a href='profile.php?profileid=".$ownerid."'>"; }
					echo $ownername;
					if ($ownerid!=0) { echo "</a>"; }
					echo "</td>
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
              </table></div></div>
			  <div class="container">
				<div class="row">
					<div class="col-md-3">
						<h4>League Info</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						Frequency: <?php 
							if ($frequency=="ed") {
								echo "Every day";
							} else if ($frequency=="eod") {
								echo "Every other day";
							} else if ($frequency=="smw") {
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
						$leagueusers_result = mysqli_query($conn,"SELECT * FROM `team` WHERE league=$leagueid AND owner!=0");
					$users = mysqli_num_rows($leagueusers_result);
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
