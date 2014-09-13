<?php
	date_default_timezone_set('America/New_York');
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
	
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	if(isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	}
	
	if(isset($_GET['teamid'])) {
		$teamid=$_GET['teamid'];
	} else {
		header('Location: 404.php');
		die();
	}
	
	$own_team = false;
	//Verify user owns team
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE id=$teamid");
	
	$teamData = mysqli_fetch_array($team_result, MYSQL_ASSOC);
	$leagueid = $teamData['league'];
	$league_result = mysqli_query($conn,"SELECT * FROM league WHERE id=$leagueid");
	$leagueData = mysqli_fetch_array($league_result);
	$owner = $teamData['owner'];
	if ($owner == $userID) {
			$own_team = true;
	} else {
		header('Location: profile.php');
		die();
	}
	
	$ownplayers_result = mysqli_query($conn,"SELECT player.id,player.firstname,player.lastname,player.position FROM `player` WHERE player.team=$teamid AND id NOT IN (SELECT player AS id FROM tradeblock)");
	
	//Retrieve and process POST data
	
	if (isset($_POST['accept'])) {
		$tradeid = $_POST['tradeid'];
		$timestamp = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i")." ".date("A");
		$accept_result = mysqli_query($conn,"SELECT team_one,team_two,offer_player,request_player FROM trades WHERE id=$tradeid");
		if (mysqli_num_rows($accept_result)==1) {
			$acceptData = mysqli_fetch_array($accept_result);
			$teamone = $acceptData['team_one'];
			$teamtwo = $acceptData['team_two'];
			$offer_player = unserialize($acceptData['offer_player']);
			$request_player = unserialize($acceptData['request_player']);
			
			foreach($offer_player as $player) {
				mysqli_query($conn,"UPDATE player SET team=$teamtwo,status='inactive' WHERE id=$player");
				mysqli_query($conn,"INSERT INTO leagueactivity (league,team,action,player,team2,`timestamp`) VALUES ($leagueid,$teamone,'traded',$player,$teamtwo,'$timestamp')");
			}
			
			foreach($request_player as $player) {
				mysqli_query($conn,"UPDATE player SET team=$teamone,status='inactive' WHERE id=$player");
				mysqli_query($conn,"INSERT INTO leagueactivity (league,team,action,player,team2,`timestamp`) VALUES ($leagueid,$teamtwo,'traded',$player,$teamone,'$timestamp')");
			}
		}
		mysqli_query($conn,"UPDATE trades SET status='1' WHERE id=$tradeid");
	}
	if (isset($_POST['reject'])) {
		$tradeid = $_POST['tradeid'];
		mysqli_query($conn,"UPDATE trades SET status='2' WHERE id=$tradeid");
	}
	if (isset($_POST['revoke'])) {
		$tradeid = $_POST['tradeid'];
		mysqli_query($conn,"UPDATE trades SET status='3' WHERE id=$tradeid");
		$tab = "sent";
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
    <link href="css/messages.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script>
	$(function() {
		<?php
		if(isset($tab)){
			echo "$('a[href=\"#".$tab."\"]').tab('show');";
		}
		?>
		$('#addtb').on('change click', function(e){
			var value = $(this).val();
			$.ajax({
		  url: 'playerdata.php',
		  type: 'POST',
		  dataType : 'json',
		  data: {'playerid': value},
		  success: function(data) {
			var name = data[0];
			var position = data[1];
			var health = data[2];
			health = health.toLowerCase().replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			});
			var rating = data[3];
			var height = data[4];
			var weight = data[5];
			
			$(".playername").html("<a target=\"_blank\" href=\"player.php?playerid="+value+"\">"+name+"</a>");
			$(".playerposition").html(position);
			$(".playerhealth").html(health);
			$(".playerrating").html(rating);
			$(".height").html(height);
			$(".weight").html(weight);
			
			 $('.playerbox').fadeIn();
		  },
		  error: function(xhr, desc, err) {
		  console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }
		  }); //end ajax 
		 
		});
		});
	</script>
	<script>document.write('<style>.playerbox { display: none; }</style>');</script>
    <title>RedZone Rush - Trades</title>
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
					echo "<li class='active'><a href=\"team.php?teamid=".$teamidArray[0]."\">Team</a></li>";
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
            <h3>Team Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
				<li>
					<a href="teamedit.php?teamid=<?php echo $teamid;?>">Edit Team</a>
				</li>
                <li>
                  <a href="team.php?teamid=<?php echo $teamid;?>">Roster</a>
                </li>
				<?php
				$newtrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE `status`='0' AND `team_two`=$teamid ORDER BY timestamp DESC");
				$alltrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE `team_two`=$teamid ORDER BY timestamp DESC");
				$senttrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE `team_one`=$teamid ORDER BY timestamp DESC");
				echo "<li class=\"active\">
                  <a href=\"trades.php?teamid=".$teamid."\">Trades ";
				  if (mysqli_num_rows($newtrade_result) != 0) {
					$num_unread = mysqli_num_rows($newtrade_result);
					echo "<span class=\"badge\">".$num_unread."</span>";
				}
				echo "</a>
                </li>";
				?>
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
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
		</div>
        <div class="col-sm-8 col-lg-offset-1 col-lg-6">
			<ol class="breadcrumb">
				<?php
				$leaguename = $leagueData['leaguename'];
				$location = $teamData['location'];
				$teamname = $teamData['teamname'];
				echo "
				  <li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>
				  <li><a href=\"team.php?teamid=".$teamid."\">".$location." ".$teamname."</a></li>
				  <li class=\"active\">Trades</li>
				  ";
				?>
				</ol>
			<div class="main">
				<h3>Trades</h3>
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
				  <li class="active"><a href="#received" role="tab" data-toggle="tab">Received</a></li>
				  <li><a href="#sent" role="tab" data-toggle="tab">Sent</a></li>
				  <li><a href="#block" role="tab" data-toggle="tab">Your Trade Block</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
				  <div class="tab-pane fade in active" id="received">
				  <?php
				if (mysqli_num_rows($alltrade_result) != 0) {
					for ($i=0;$i<mysqli_num_rows($alltrade_result);$i++) {
						$tradeData = mysqli_fetch_array($alltrade_result);
						$sender = $tradeData['team_one'];
						$offer_player = unserialize($tradeData['offer_player']);
						$request_player = unserialize($tradeData['request_player']);
						$tradeid = $tradeData['id'];
						$timestamp = $tradeData['timestamp'];
						$tradestatus = $tradeData['status'];
						$trademessage = $tradeData['message'];
						
						$sender_result = mysqli_query($conn,"SELECT location,teamname,logofile FROM team WHERE id=$sender");
						$senderData = mysqli_fetch_array($sender_result);
						$sender_name = $senderData['location']." ".$senderData['teamname'];
						$imagepath = "uploads/logos/".$senderData['logofile'];
						
						echo "<div class=\"row\">
						<div class=\"col-md-8\" id=\"msgcontainer\">
							<div class=\"well\">
								<div class=\"row\" id=\"sender\">
									<div class=\"col-md-8\">
										<img src=\"".$imagepath."\" height=\"50\">
										<strong><a href=\"team.php?teamid=".$sender."\">".$sender_name."</a></strong>
									</div>
									<div class=\"col-md-3\">".$timestamp."
									</div>
								</div>
								<div class=\"row\" id=\"offered\">
									<div class=\"col-md-8 col-md-offset-3\">
									<b>You get</b><p>
									";
									foreach ($offer_player as $player) {
										$player_result = mysqli_query($conn,"SELECT firstname,lastname,position FROM `player` WHERE id=$player");
										$playerData = mysqli_fetch_array($player_result);
										echo "<a target='_blank' href='player.php?playerid=".$player."'>".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</a><br>";
									}
									echo "</p>
									</div>
								</div>
								<div class=\"row\" id=\"requested\">
									<div class=\"col-md-8 col-md-offset-3\">
									<b>You give</b><p>
									";
									foreach ($request_player as $player) {
										$player_result = mysqli_query($conn,"SELECT firstname,lastname,position FROM `player` WHERE id=$player");
										$playerData = mysqli_fetch_array($player_result);
										echo "<a target='_blank' href='player.php?playerid=".$player."'>".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</a><br>";
									}
									echo "</p>
									</div>
								</div>";
								if ($trademessage!="") {
									echo "<div class='row' id='message'>
									<div class='col-md-8 col-md-offset-3'><b>Message: </b><p>".$trademessage."
									</p></div>
								</div>";
								}
								echo "<div class=\"row\">";
								switch ($tradestatus) {
									case 0:
										echo "<form method=\"POST\" action=\"trades.php?teamid=".$teamid."\" role=\"form\">
												<input type=\"hidden\" name=\"tradeid\" value=\"".$tradeid."\">
												<button type=\"submit\" id=\"delete\" name=\"reject\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Reject</button>
											</form>
											<form method=\"POST\" action=\"trades.php?teamid=".$teamid."\" role=\"form\">
												<input type=\"hidden\" name=\"tradeid\" value=\"".$tradeid."\">
												<button type=\"submit\" id=\"reply\" name=\"accept\" class=\"btn btn-success\"><span class=\"glyphicon glyphicon-ok\"></span> Accept</button>
											</form>";
										break;
									case 1:
										echo "<span class='tradestatus'>You accepted this trade</span>";
										break;
									case 2:
										echo "<span class='tradestatus'>You rejected this trade</span>";
										break;
									case 3:
										echo "<span class='tradestatus'>Revoked by other team</span>";
										break;
									default:
										echo "Error";
										break;
								}
								
							echo "</div>
							</div>
						</div>
					</div>";
					}
				}
				if (mysqli_num_rows($alltrade_result) == 0) {
					echo "No trade offers!";
				}
				?></div>
				  <div class="tab-pane fade" id="sent">
				  <?php
				  if(mysqli_num_rows($senttrade_result)!= 0) {
					for ($i=0;$i<mysqli_num_rows($senttrade_result);$i++) {
						$tradeData = mysqli_fetch_array($senttrade_result);
						$recipient = $tradeData['team_two'];
						$offer_player = unserialize($tradeData['offer_player']);
						$request_player = unserialize($tradeData['request_player']);
						$tradeid = $tradeData['id'];
						$timestamp = $tradeData['timestamp'];
						$tradestatus = $tradeData['status'];
						$trademessage = $tradeData['message'];
						
						$recipient_result = mysqli_query($conn,"SELECT location,teamname,logofile FROM team WHERE id=$recipient");
						$recipientData = mysqli_fetch_array($recipient_result);
						$recipient_name = $recipientData['location']." ".$recipientData['teamname'];
						$imagepath = "uploads/logos/".$recipientData['logofile'];
						
						echo "<div class=\"row\">
						<div class=\"col-md-8\" id=\"msgcontainer\">
							<div class=\"well\">
								<div class=\"row\" id=\"sender\">
									<div class=\"col-md-8\">
										<img src=\"".$imagepath."\" height=\"50\">
										<strong><a href=\"team.php?teamid=".$recipient."\">".$recipient_name."</a></strong>
									</div>
									<div class=\"col-md-3\">".$timestamp."
									</div>
								</div>
								<div class=\"row\" id=\"offered\">
									<div class=\"col-md-8 col-md-offset-3\">
									<b>You give</b><p>
									";
									foreach ($offer_player as $player) {
										$player_result = mysqli_query($conn,"SELECT firstname,lastname,position FROM `player` WHERE id=$player");
										$playerData = mysqli_fetch_array($player_result);
										echo "<a target='_blank' href='player.php?playerid=".$player."'>".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</a><br>";
									}
									echo "</p>
									</div>
								</div>
								<div class=\"row\" id=\"requested\">
									<div class=\"col-md-8 col-md-offset-3\">
									<b>You get</b><p>
									";
									foreach ($request_player as $player) {
										$player_result = mysqli_query($conn,"SELECT firstname,lastname,position FROM `player` WHERE id=$player");
										$playerData = mysqli_fetch_array($player_result);
										echo "<a target='_blank' href='player.php?playerid=".$player."'>".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</a><br>";
									}
									echo "</p>
									</div>
								</div>";
								if ($trademessage!="") {
									echo "<div class='row' id='message'>
									<div class='col-md-8 col-md-offset-3'><b>Message: </b><p>".$trademessage."
									</p></div>
								</div>";
								}
								echo "<div class=\"row\"><span class='tradestatus'>";
								switch ($tradestatus) {
									case 0:
										echo "<p>Awaiting response</p>
										<form method=\"POST\" action=\"trades.php?teamid=".$teamid."\" role=\"form\">
										<input type=\"hidden\" name=\"tradeid\" value=\"".$tradeid."\">
										<button type=\"submit\" id=\"reply\" name=\"revoke\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Revoke Offer</button>
										</form>";
										break;
									case 1:
										echo "<p>Trade accepted</p>";
										break;
									case 2:
										echo "<p>Trade rejected</p>";
										break;
									case 3:
										echo "<p>Revoked by you</p>";
										break;
									default:
										echo "<p>Error</p>";
										break;
								}
								echo "</span></div>
							</div>
						</div>
					</div>";
					}
				  } else {
					echo "No trades sent.";
				  }
				  ?>
				  </div>
				  <div class="tab-pane fade" id="block">
				  <div class='row'>
					
					<form method="POST" action="blockact.php">
					<input type="hidden" name="team" value="<?php echo $teamid;?>">
					  <?php
						$tb_result = mysqli_query($conn,"SELECT * FROM tradeblock WHERE team=$teamid");
						if (mysqli_num_rows($tb_result)!=0) {
							echo "
							<div class='col-sm-6 col-xs-12' id='blockplayers'>
							<h4><b>".$location." ".$teamname." Trade Block</b></h4>";
						}
						while ($tbData = mysqli_fetch_array($tb_result)) {
							$player = $tbData['player'];
							$player_result = mysqli_query($conn,"SELECT player.firstname,player.lastname,player.position,attributes.overall_now FROM player JOIN attributes ON attributes.player=player.id WHERE player.id=$player");
							$playerData = mysqli_fetch_array($player_result);
							echo "
								<div class='row playerrow'>
								<a href='player.php?playerid=".$player."'>".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</a>
								<button class='btn btn-danger btn-xs removeplayer' name='removeplayer' value='".$player."'>Remove</button><br>
							";
							if ($tbData['message']!="") {
								echo "
									<b>Notes</b><br>
									<p>".$tbData['message']."</p>
								";
							}
							echo "
							</div>
							";
						}
						if (mysqli_num_rows($tb_result)!=0) {
							echo "
							</div>";
						}
					  ?>
					</form>
					</div>
					<form method="POST" action="blockact.php" class="form">
				  <input type="hidden" name="team" value="<?php echo $teamid;?>">
				  <input type="hidden" name="league" value="<?php echo $leagueid;?>">
				  <div class="row">
					  <div class="col-sm-6 col-xs-12">
						<?php echo "<a href='tradeblock.php?leagueid=".$leagueid."'>View ".$leagueData['leaguename']." Trade Block</a>";?>
						  <h4><b>Add Player to Trade Block</b></h4>
						  <select class="form-control" id="addtb" name="addtb">
						  <?php
								while ($playerData = mysqli_fetch_array($ownplayers_result)) {
									echo "<option value='".$playerData['id']."'>";
									echo $playerData['position']." ".$playerData['firstname']." ".$playerData['lastname'];
									echo "</option>";
								}
							?>
						  </select>
					  </div>
				  </div>
				  <div class="row" style="margin-top:10px;">
					<div class="col-sm-8 col-xs-12">
					<b>Notes (optional)</b>
					<textarea class="form-control" id="msgbox" name="msgbox" rows="3"></textarea>
					<button type="submit" class="btn btn-primary" name="addplayer" style="margin-top:10px;">Add Player</button>
					</div>
					<div class="col-sm-4 col-xs-12">
						<div class="playerbox">
							<b>Selected Player:</b><br>
							<b><span class="playername"></span></b>
							<p><span class="playerposition"></span></p>
							<p><b>Rating: <span class="playerrating"></span></b></p>
							<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
							<p><span class="playerhealth"></span></p>
						</div>
					</div>
				  </div>
				  </form>
				</div>
				 
				  </div>
				</div>
			</div>
				
				
        </div>
      </div>
    </div>
  </body>
</html>
