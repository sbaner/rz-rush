<?php
	session_start();
	date_default_timezone_set('America/New_York');
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	if (!empty($_GET['leagueid'])) {
		$leagueid = $_GET['leagueid'];
	} else {
		header('Location: 404.php');
		exit();
	}
	$board_query = "SELECT * FROM board WHERE id='$leagueid'";
	$board_result = mysqli_query($conn,$board_query);
	if (mysqli_num_rows($board_result)==0) {
		header('Location: 404.php');
		exit();
	}
	
	$boardData = mysqli_fetch_array($board_result);
	$boardname = $boardData['boardname'];
	
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
    <link href="css/messages.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/sorttable.js"></script>
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
        <div class="col-md-8">
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
		  if ($leagueid!="m") {
		  echo "<div class=\"side-bar\"> 
            <h3>League Links</h3>
            <div class=\"nav\">
              <ul class=\"nav nav-pills nav-stacked navbar-left\">
				<li>
                  <a href=\"scores.php?leagueid=".$leagueid."\">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href=\"freeagents.php?leagueid=".$leagueid."\">Free Agents</a>
                </li>
				<li>
                  <a href=\"draft.php?leagueid=".$leagueid."\">Draft</a>
                </li>
                <li>
                  <a href=\"leaguealmanac.php?leagueid=".$leagueid."\">Almanac</a>
                </li>
				<li class='active'>
                  <a href=\"mboard.php?leagueid=".$leagueid."\">Message Board</a>
                </li>
              </ul>
            </div>
          </div>";
		  }
		  ?>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-sm-8 col-lg-offset-1 col-lg-6">
			<ol class="breadcrumb">
				<?php
					if ($leagueid=="m") {
						echo "<li><a href='profile.php'>RZRush Home</a></li>";
						echo "<li class='active'>RZRush Main Message Board</li>";
					} else {
						echo "<li><a href='league.php?leagueid=".$leagueid."'>".$boardname."</a></li>";
						echo "<li class='active'>Message Board</li>";
					}
				?>
			</ol>
			<div class="main">
			<?php
			echo "<h3>".$boardname." Message Board</h3>";
			?>
				<button class="btn btn-primary" type="submit" name="writemsg" id="writemsg" data-toggle="modal" data-target="#newthread" style="margin-bottom:10px;"><span class="glyphicon glyphicon-edit"></span> New Thread</button>
				<div class="modal fade" id="newthread" tabindex="-1" role="dialog" aria-labelledby="newthread" aria-hidden="true">
				  <div class="modal-dialog modal-lg">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">New Thread</h4>
					  </div>
					  <div class="modal-body">
						<form class="form-horizontal" method="POST" action="mbact.php">
						<div class="form-group">
							<label for="subject" class="col-sm-2 control-label">Subject</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2">
							  <textarea class="form-control" rows="3" id="threadtext" name="threadtext"></textarea>
							</div>
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<input type="hidden" name="boardid" value="<?php echo $leagueid;?>">
							<button type="submit" name="newthread" class="btn btn-primary">Submit Thread</button>
						</form>
					  </div>
					</div>
				  </div>
				</div>
			<div class="table-responsive">
			<table class="table sortable">
				<thead>
					<tr>
						<th class="sorttable_nosort" width="20%">Thread</th>
						<th class="sorttable_nosort">Author</th>
						<th class="sorttable_nosort">Replies</th>
						<th class="sorttable_nosort">Last Poster</th>
						<th>Last Post Time</th>
						<th class="sorttable_nosort" width="5%"></th>
					</tr>
					
				</thead>
					<?php
						$thread_query = "SELECT mb_posts.*,member.username FROM mb_posts JOIN member ON member.id=mb_posts.author WHERE mb_posts.boardid='$leagueid' AND mb_posts.post_type='thread' AND mb_posts.status='publish' ORDER BY mb_posts.last_mod DESC LIMIT 25";
						$thread_result = mysqli_query($conn,$thread_query);
						
					
						while ($threadData = mysqli_fetch_array($thread_result)) {
							$threadid = $threadData['threadid'];
							$replies_query = "SELECT count(*) AS replies FROM mb_posts WHERE threadid=$threadid AND post_type='subpost'";
							$replies_result = mysqli_query($conn,$replies_query);
							$repliesData = mysqli_fetch_array($replies_result);
							$replies = $repliesData['replies'];
							
							$lastpost_result = mysqli_query($conn,"SELECT mb_posts.author,member.username FROM mb_posts JOIN member ON member.id=mb_posts.author WHERE threadid=$threadid ORDER BY mb_posts.timestamp DESC LIMIT 1");
							$lastpostData = mysqli_fetch_array($lastpost_result);
							
							echo "<tr>";
							echo "<td><a href='mbthread.php?threadid=".$threadid."'>".$threadData['subject']."</a></td>";
							echo "<td><a href='profile.php?profileid=".$threadData['author']."'>".$threadData['username']."</a></td>";
							echo "<td>".$replies."</td>";
							echo "<td><a href='profile.php?profileid=".$lastpostData['author']."'>".$lastpostData['username']."</td>";
							echo "<td>".$threadData['last_mod']."</td>";
							if ($threadData['author']==$userID) {
								echo "<td><form method='POST' action='mbact.php'><button class='btn btn-xs btn-danger' value='".$threadData['threadid']."' name='delthread'>Delete</button></form></td>";
							}
							echo "</tr>";
						}
					?>
			</table>
			</div>
			</div>
				
				
        </div>
      </div>
    </div>
  </body>
</html>
