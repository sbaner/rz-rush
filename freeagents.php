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
	
	$inornot = "";
	$nopages = false;
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
	
	if (!(isset($_GET['pagenum']))) { 
		$pagenum = 1; 
	} else {
		$pagenum = $_GET['pagenum'];
	 }
	
	//filter
	$filter = "";
	if(isset($_POST['filter'])) {
		$inornot = "in";
		$minoverall = $_POST['minoverall'];
		$maxoverall = $_POST['maxoverall'];
		$position = $_POST['position']; 
		
		if ($minoverall !=0) {
		$filter = $filter."AND overall_now >= $minoverall ";
		$nopages = true;
		}
		if ($maxoverall !=99) {
			$filter = $filter."AND overall_now <= $maxoverall ";
			$nopages = true;
		}
		if ($position !="All") {
		$filter = $filter."AND position='$position' ";
		$nopages = true;
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
    <link href="css/freeagents.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Free Agents</title>
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
                <li class=\"active\">
                  <a href=\"freeagents.php?leagueid=".$leagueid."\">Free Agents</a>
                </li>
				<li>
                  <a href=\"draft.php?leagueid=".$leagueid."\">Draft</a>
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
			echo "<li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>";
				echo "<li class='active'>Free Agents</li>";
			
		?>
		</ol>
          <div class="main">
            <h3>Free Agents</h3>
			<div class="panel-group" id="accordion">
			  <div class="panel panel-default searchfilter">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#filters">
					  <b>Filter Search</b>
					</a>
				  </h4>
				</div>
				<div id="filters" class="panel-collapse collapse <?php echo $inornot;?>">
				  <div class="panel-body">
					<form class="form horizontal" action="freeagents.php?leagueid=<?php echo $leagueid;?>" method="POST">
					<div class="row">
						<div class="form-group col-md-5">
							<label for="minoverall" class="col-sm-5 control-label">Minimum Overall</label>
							<div class="col-sm-4">
							  <input type="number" class="form-control" id="minoverall" name="minoverall" value="0">
							</div>
						</div>
						<div class="form-group col-md-5">
							<label for="maxoverall" class="col-sm-5 control-label">Maximum Overall</label>
							<div class="col-sm-4">
							  <input type="number" class="form-control" id="maxoverall" name="maxoverall" value="99">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="col-sm-5">
							<b>Position</b>
							</div>
							<div class="col-sm-4">
							<select class="form-control" name="position">
								<option>All</option>
								<option>QB</option>
								<option>RB</option>
								<option>FB</option>
								<option>WR</option>
								<option>TE</option>
								<option>G</option>
								<option>C</option>
								<option>T</option>
								<option>DE</option>
								<option>DT</option>
								<option>LB</option>
								<option>CB</option>
								<option>S</option>
								<option>K</option>
								<option>P</option>
							</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10">
						  <button type="submit" name="filter" class="btn btn-default filter-btn">Filter</button>
						</div>
					</div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
			<div class="well playerlist">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th width="10%">Pos</th>
							<th width="20%">Name</th>
							<th width="10%">Rating</th>
							<th width="10%">Exp</th>
							<th width="20%">Health</th>
						</tr>
					</thead>
					<tbody>
            <?php 
			
			 $fa_result = mysqli_query($conn,"SELECT * FROM player WHERE league=$leagueid AND team=0 $filter");
			 $rows = mysqli_num_rows($fa_result); 
			 $page_rows = 25; 

			 //This tells us the page number of our last page 
			 $last = ceil($rows/$page_rows); 
			 
			 //this makes sure the page number isn't below one, or more than our maximum pages 

			 if ($pagenum < 1) { 
				$pagenum = 1; 
			} 

			 elseif ($pagenum > $last) { 

			 $pagenum = $last; 

			 } 


			 //This sets the range to display in our query 
			if (!$nopages) {
				$max = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
			} else {
				$max = "";
			}
			  //This is your query again, the same one... the only difference is we add $max into it

			 $fa_result_p = mysqli_query($conn, "SELECT * FROM player JOIN attributes ON attributes.player=player.id WHERE player.league=$leagueid AND player.team=0 $filter ORDER BY attributes.overall_now DESC $max"); 


			 //This is where you display your query results
			 while($faData = mysqli_fetch_array( $fa_result_p ))  { 
				$player_exp = $year - $faData['start_year'];
				echo "<tr>
				<td>".$faData['position']."</td><td><a href=\"player.php?playerid=".$faData['id']."\">".$faData['firstname']." ".$faData['lastname']."</td>",
				"<td>".$faData['overall_now']."</td><td>".$player_exp."</td><td>".ucfirst($faData['health'])."</td>";
			 } 
			 
			 ?>
			 
			 </tbody>
			 </table>
			 <?php
			 

			 // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
			$previous = $pagenum-1;
			$next = $pagenum+1;
			if (!$nopages) {
			echo "Page $pagenum of $last";
			echo "<ul class=\"pager\">
			  <li class=\"previous ";
			  if ($pagenum == 1) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&pagenum=1\"><span class=\"glyphicon glyphicon-step-backward\"></span> First</a></li>
			  <li class=\"previous ";
			  if ($pagenum == 1) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&pagenum=$previous\"><span class=\"glyphicon glyphicon-chevron-left\"></span> Previous</a></li>
			  <li class=\"next ";
			  if ($pagenum == $last) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&pagenum=$last\">Last <span class=\"glyphicon glyphicon-step-forward\"></span></a></li>
			  <li class=\"next ";
			  if ($pagenum == $last) {
				echo "disabled";
			  }
			  echo "\"><a href=\"{$_SERVER['PHP_SELF']}?leagueid=$leagueid&pagenum=$next\">Next <span class=\"glyphicon glyphicon-chevron-right\"></span></a></li>
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
