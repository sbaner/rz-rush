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
	
	if (!(isset($_GET['round']))) { 
		$roundnum = 1; 
	} else {
		$roundnum = $_GET['round'];
	 }
	 
	 //Retreive year from POST
	 if (isset($_POST['draft_year'])) {
		$draft_year = $_POST['draft_year'];
	 } else {
		$draft_year = $year;
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
    <link href="css/freeagents.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Draft</title>
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
                  <a href=\"league.php?leagueid=".$leagueid."\">Standings</a>
                </li>
                <li>
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"freeagents.php?leagueid=".$leagueid."\">Free Agents</a>
                </li>
				<li class=\"active\">
                  <a href=\"draft.php?leagueid=".$leagueid."\">Draft</a>
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
        <div class="col-md-9 col-lg-8">
		<ol class="breadcrumb">
		<?php
			echo "<li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>";
				echo "<li><a href=\"draft.php?leagueid=".$leagueid."\">Draft</a></li>";
			
		?>
		</ol>
          <div class="main">
            <h3><?php echo $leaguename." ".$draft_year." Draft"?></h3>
			<form class="form horizontal" action="draft.php?leagueid=<?php echo $leagueid;?>" method="POST">
			<div class="row">
				<div class="col-md-2">
					<select class="form-control" name="draft_year" onchange="this.form.submit()">
					<?php
						$draftyear_result = mysqli_query($conn,"SELECT start_year FROM `player` ORDER BY start_year LIMIT 1");
						$draftyearData = mysqli_fetch_array($draftyear_result);
						$firstyear = $draftyearData['start_year'];
						if ($firstyear == $year) {
							echo "<option>".$firstyear."</option>";
						} else {
							for ($i=$firstyear;$i<$year;$i++) {
								echo "<option>".$i."</option>";
							}
							echo "<option selected>".$year."</option>";
						}
					?>
					</select>
				</div>
			</div>
			</form>
			<div class="well playerlist" style="margin-top: 10px">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th width="10%">Pos</th>
							<th width="20%">Name</th>
							<th width="20%">Team</th>
							<th width="20%">Rating</th>
							<th width="15%">Round</th>
							<th width="15%">Pick #</th>
						</tr>
					</thead>
					<tbody>
            <?php 
			
			 $draft_result = mysqli_query($conn,"SELECT * FROM player WHERE league=$leagueid AND draft_round=$roundnum AND start_year=$draft_year ORDER BY draft_round,draft_pos");
			 $draft_result_p = mysqli_query($conn,"SELECT * FROM player WHERE league=$leagueid AND start_year=$draft_year ORDER BY draft_round,draft_pos");
			 $rows = mysqli_num_rows($draft_result_p); 
			 $page_rows = 32; 

			 //This tells us the page number of our last page 
			 $last = ceil($rows/$page_rows); 
			 if ($last==0)  {
				$nopages = true;
			 } else {
				$nopages = false;
			 }
			 //this makes sure the page number isn't below one, or more than our maximum pages 

			 if ($roundnum < 1) { 
				$roundnum = 1; 
			} 

			 elseif ($roundnum > $last) { 

			 $roundnum = $last; 

			 } 
			 
			 //This is where you display your query results
			 while($draftData = mysqli_fetch_array($draft_result))  {
				$draftteamid = $draftData['draft_team'];
				$teamname_result = mysqli_query($conn,"SELECT location,teamname FROM team WHERE id=$draftteamid");
				$teamnameData = mysqli_fetch_array($teamname_result);
				$teamname = $teamnameData['location']." ".$teamnameData['teamname'];
				echo "<tr>
				<td>".$draftData['position']."</td><td><a href=\"player.php?playerid=".$draftData['id']."\">".$draftData['firstname']." ".$draftData['lastname']."</td>",
				"<td><a href=\"team.php?teamid=".$draftteamid."\">".$teamname."</a></td><td>".$draftData['overall_now']."</td><td>".$draftData['draft_round']."</td><td>".$draftData['draft_pos']."</td>";
			 } 
			 
			 ?>
			 
			 </tbody>
			 </table>
			 <?php
			 
			 // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
			$previous = $roundnum-1;
			$next = $roundnum+1;
			if (!$nopages) {
			echo "Round $roundnum of $last";
			echo "<ul class=\"pager\">
			  <li class=\"previous ";
			  if ($roundnum == 1) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&round=1\"><span class=\"glyphicon glyphicon-step-backward\"></span> First</a></li>
			  <li class=\"previous ";
			  if ($roundnum == 1) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&round=$previous\"><span class=\"glyphicon glyphicon-chevron-left\"></span> Previous</a></li>
			  <li class=\"next ";
			  if ($roundnum == $last) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&round=$last\">Last <span class=\"glyphicon glyphicon-step-forward\"></span></a></li>
			  <li class=\"next ";
			  if ($roundnum == $last) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&round=$next\">Next <span class=\"glyphicon glyphicon-chevron-right\"></span></a></li>
			</ul>";
			}
			 ?> 
			</div>
			</div>
			</div>
        </div>
        </div>
      </div>
    </div>
  </body>
</html>
