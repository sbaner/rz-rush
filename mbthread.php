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
	
	if (!empty($_GET['threadid'])) {
		$threadid = $_GET['threadid'];
	} else {
		header('Location: 404.php');
		exit();
	}
	
	$op_query = "SELECT mb_posts.*,member.username,board.boardname FROM mb_posts JOIN member ON member.id=mb_posts.author JOIN board ON board.id=mb_posts.boardid WHERE threadid=$threadid AND post_type='thread' AND status='publish'";
	$op_result = mysqli_query($conn,$op_query);
	$post_result = mysqli_query($conn,"SELECT mb_posts.*,member.username FROM mb_posts JOIN member ON member.id=mb_posts.author WHERE threadid=$threadid AND post_type='subpost' AND status='publish'");
	if (mysqli_num_rows($op_result)==0) {
		header('Location: 404.php');
		exit();
	}
	
	$opData = mysqli_fetch_array($op_result);
	
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
		  if ($opData['boardid']!="m") {
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
					if ($opData['boardid']=="m") {
						echo "<li><a href='profile.php'>RZRush Home</a></li>";
						echo "<li><a href='mboard.php?leagueid=m'>RZRush Main Message Board</a></li>";
						echo "<li class='active'>".$opData['subject']."</li>";
					} else {
						echo "<li><a href='league.php?leagueid=".$opData['boardid']."'>".$opData['boardname']."</a></li>";
						echo "<li><a href='mboard.php?leagueid=".$opData['boardid']."'>Message Board</a></li>";
						echo "<li class='active'>".$opData['subject']."</li>";
					}
				?>
			</ol>
			<div class="main">
			<?php
			echo "<h3>".$opData['subject']."</h3>";
			?>
			<div class='row' style='margin-left:0px;'>
				<?php echo $opData['timestamp'];?><button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#newreply" style='margin-bottom:10px;' id="writemsg"><span class="glyphicon glyphicon-share-alt"></span> Reply</button>
				<div class="modal fade" id="newreply" tabindex="-1" role="dialog" aria-labelledby="newreply" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">New Reply</h4>
					  </div>
					  <div class="modal-body">
						<form class="form-horizontal" method="POST" action="mbact.php">
						<div class="form-group">
							<div class="col-xs-12">
							  <textarea class="form-control" rows="3" id="replytext" name="replytext"></textarea>
							</div>
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<input type="hidden" name="threadid" value="<?php echo $threadid;?>">
							<button type="submit" name="newreply" class="btn btn-primary">Submit Reply</button>
						</form>
					  </div>
					</div>
				  </div>
				</div>
			</div>
				<div class='row'>
					<div class='thread-post'>
						<?php
						//Top Post
						$op_author = $opData['author'];
						$photo_result = mysqli_query($conn,"SELECT filename FROM photos WHERE member_id=$op_author AND pri='yes'");
						$photoData = mysqli_fetch_array($photo_result);
						if ($photoData['filename'] != "") {
							$imagepath = "./uploads/".$photoData['filename'];
						} else {
							$imagepath="profile.jpg";
						}
						echo "<div class=\"row\" id=\"op-text\">
									<div class=\"col-sm-3 col-xs-5\">
										<a href=\"profile.php?profileid=".$op_author."\"><img src=\"".$imagepath."\" width=\"125px\"></a><br>
										<strong><a href=\"profile.php?profileid=".$op_author."\">".$opData['username']."</a></strong>
									</div>
									<div class=\"col-sm-8 col-xs-7\">".$opData['text']."
									</div>
								</div>";
								if ($opData['author']==$userID) {
									echo "<div class=\"row\" style='margin-right:0;'>
										<form method=\"POST\" action=\"mbact.php\" role=\"form\">
											<input type=\"hidden\" name=\"threadid\" value=\"".$threadid."\">
											<button type=\"submit\" id=\"delete\" name=\"delthread\" value='".$threadid."' class=\"btn btn-danger btn-sm\"><span class=\"glyphicon glyphicon-trash\"></span> Delete</button>
										</form>
									</div>";
								}
						?>
					</div>
				</div>
					<?php
					//Replies
					
					while($postData = mysqli_fetch_array($post_result)) {
						$post_author = $postData['author'];
						$photo_result = mysqli_query($conn,"SELECT filename FROM photos WHERE member_id=$post_author AND pri='yes'");
						$photoData = mysqli_fetch_array($photo_result);
						if ($photoData['filename'] != "") {
							$imagepath = "./uploads/".$photoData['filename'];
						} else {
							$imagepath="profile.jpg";
						}
						echo "<div class='row'><div class='thread-post'>
						<div class=\"row\" id=\"reply-text\">
									<div class=\"col-sm-3 col-xs-5\">
										<a href=\"profile.php?profileid=".$post_author."\"><img src=\"".$imagepath."\" width=\"125px\"></a><br>
										<strong><a href=\"profile.php?profileid=".$post_author."\">".$postData['username']."</a></strong>
									</div>
									<div class=\"col-sm-8 col-xs-7\"><p>".$postData['timestamp']."</p><p>".$postData['text']."</p>
									</div>
								</div>";
								if ($postData['author']==$userID) {
									echo "<div class=\"row\" style='margin-right:0;'>
										<form method=\"POST\" action=\"mbact.php\" role=\"form\">
											<input type=\"hidden\" name=\"postid\" value=\"".$postData['postid']."\">
											<button type=\"submit\" id=\"delete\" name=\"delpost\" value='".$postData['postid']."' class=\"btn btn-danger btn-sm\"><span class=\"glyphicon glyphicon-trash\"></span> Delete</button>
										</form>
									</div>";
								}
						echo "</div></div>";
					}
					?>
			</div>	
        </div>
      </div>
    </div>
  </body>
</html>
