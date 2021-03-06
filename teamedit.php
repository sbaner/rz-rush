<?php
	session_start();
	require_once('includes/getweek.php');
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
	//Verify user owns team
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE id=$teamid");
	
	$teamData = mysqli_fetch_array($team_result, MYSQL_ASSOC);
	$leagueid = $teamData['league'];
	$owner = $teamData['owner'];
	if ($owner == $userID) {
			$own_team = true;
	} else {
		header('Location: profile.php');
		die();
	}
	$logopath = "uploads/logos/".$teamData['logofile'];
	$location = $teamData['location'];
	$teamname = $teamData['teamname'];
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	//Retrieve POST data and update
	if(isset($_POST['delete-button'])) {
		mysqli_query($conn,"UPDATE team SET owner=0,total_win=0,total_loss=0,championships=0,total_tie=0,logofile='helmet.png',owndate='',seasons=0 WHERE id=$teamid");
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		mysqli_query($conn,"INSERT INTO leagueactivity (league,team,member,action,timestamp) VALUES ($leagueid,$teamid,$userID,'dropped','$timestamp')");
		header('Location: team.php?teamid='.$teamid);
	} else if(isset($_POST['location'])) {
		$newlocation = mysqli_real_escape_string($conn,$_POST['location']);
		$newname = mysqli_real_escape_string($conn,$_POST['teamname']);
		$newabbrev = mysqli_real_escape_string($conn,$_POST['abbrev']);
		
		mysqli_query($conn,"UPDATE team SET location='$newlocation',teamname='$newname',abbrev='$newabbrev' WHERE id=$teamid");
		
		header('Location: team.php?teamid='.$teamid);
	}
	
	//Tutorial
	mysqli_query($conn,"UPDATE tutorial SET profile='1',teamselect='1',league='1',team='1',teamedit='1' WHERE member=$userID");
	
	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../css/register.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
	<script>
		function checkForm() {
			var location = $("#location").val()
			var teamname = $("#teamname").val();
			var abbrev = $("#abbrev").val();
			var button = document.getElementById('signup-button');
			
			if (location.length > 0 && teamname.length > 0 && abbrev.length > 0) {
				button.disabled = false;
			} else {
				button.disabled = true;
			}
		}
		$(document).ready(function () {
		   $("#location").keyup(checkForm);
		   $("#teamname").keyup(checkForm);
		   $("#abbrev").keyup(checkForm);
		});
		</script>
    <title>RedZone Rush - Edit Team</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row" id="top">
        <div class="col-md-2">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
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
            <h3>Team Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
				<li class="active">
					<a href="teamedit.php?teamid=<?php echo $teamid;?>">Edit Team</a>
				</li>
                <li>
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
				<li>
                  <a href="league.php?leagueid=<?php echo $leagueid;?>">Standings</a>
                </li>
                <li>
                  <a href="scores.php?leagueid=<?php echo $leagueid;?>">Scores &amp; Schedule</a>
                </li><li>
                  <a href="depthchart.php?teamid=<?php echo $teamid;?>">Depth Chart</a>
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
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-md-offset-1 col-sm-9 col-md-6">
          <div class="main">
		  <h3>Edit Team</h3>
            <form class="form-horizontal" method="POST" id="edit-team" action="teamedit.php?teamid=<?php echo $teamid;?>" role="form">
              <div class="form-group">
                <label for="location" class="col-sm-2 control-label">Location</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="location" name="location" placeholder="Location (Ex: New York)"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="teamname" class="col-sm-2 control-label">Team Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="teamname" name="teamname" placeholder="Team Name (Ex: Giants)"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="abbrev" class="col-sm-2 control-label">Abbreviation (max 3 char)</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="abbrev" name="abbrev" placeholder="Abbreviation (Ex: NYG)" maxlength="3"/>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary" id="signup-button" disabled>Update Info</button>
                  <button type="button" class="btn btn-danger" data-toggle='modal' data-target='#confdel'>Delete Team</button>
				  <div class='modal fade' id='confdel' tabindex='-1' role='dialog' aria-labelledby='ConfirmDelete' aria-hidden='true'>
					  <div class='modal-dialog modal-sm'>
						<div class='modal-content'>
						  <div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
							<h4 class='modal-title' id='cutModalLabel'>Confirm action</h4>
						  </div>
						  <div class='modal-body'>
							Delete this team? All historical data will be lost and you will no longer control this team.
						  </div>
						  <div class='modal-footer'>
							<button type="submit" class="btn btn-danger" id="delete-button" name="delete-button">Delete Team</button>
						  </div>
						</div>
					  </div>
					</div>
                </div>
              </div>
            </form>
			<h4><b>Update Team Logo</b></h4>
			<?php if(!empty($message)) { echo "<p>{$message}</p>";}?>
			<form action="upload.php?teamid=<?php echo $teamid;?>" id="profile-pic-form" enctype="multipart/form-data" method="POST">
				
				<input type="hidden" name="MAX_FILE_SIZE" value="5000000">
				<input type="file" id="upload_file" name="upload_file">
                <button type="submit" name="changelogo" class="btn btn-primary" id="upload-button">Upload</button>
				<span class="help-block">Image requirements: JPG or PNG, less than 5MB.</span>
			</form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>