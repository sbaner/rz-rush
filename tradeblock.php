<?php
	ob_start();
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
	
	$league_result = mysqli_query($conn,"SELECT * FROM league WHERE id=$leagueid");
	if (mysqli_num_rows($league_result)==0) {
		header('Location: 404.php');
		exit();
	} else {
		$leagueData = mysqli_fetch_array($league_result);
	}
	
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
    <title>RedZone Rush - Trade Block</title>
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
				<li class='active'>
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
          <div class="main">
            <h3><?php echo $leagueData['leaguename']." Trade Block";?></h3>
            <div class="stat-card">
				<?php
				if (mysqli_num_rows($myteam_result) != 0) {
					echo "<a href='trades.php?teamid=".$myteamid."&tab=block'>Edit your Trade Block</a>";
				}
			?>
                <?php
					$leagueteams_result = mysqli_query($conn,"SELECT id,location,teamname FROM team WHERE league=$leagueid");
					$tb_empty=true;
					while ($ltData = mysqli_fetch_array($leagueteams_result)) {
						$lteam = $ltData['id'];
						$tb_result = mysqli_query($conn,"SELECT tradeblock.*,player.firstname,player.lastname,player.position FROM tradeblock JOIN player on player.id=tradeblock.player WHERE player.team=$lteam");
						if (mysqli_num_rows($tb_result)>0) {
							$tb_empty=false;
							echo "
							<div class='row' style='margin-right:0px;'>
								<div class='tb-card col-sm-6 col-xs-12'>
									<h4><a href='team.php?teamid=".$lteam."'>".$ltData['location']." ".$ltData['teamname']."</a></h4>";
							while ($tbData = mysqli_fetch_array($tb_result)) {
								echo "<a href='player.php?playerid=".$tbData['player']."'>".$tbData['position']." ".$tbData['firstname']." ".$tbData['lastname']."</a><br>";
								if ($tbData['message']!="") {
									echo "<p class='tb-notes'><b>Notes: </b>".$tbData['message']."</p>";
								}
							}
							echo "	</div>
							</div>";
						}
					}
					if ($tb_empty) {
						echo "<div class='tb-card'>No teams have players on the Trade Block.</div>";
					}
				?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

<?php ob_flush(); ?>
