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
		die();
	}
	
	if (!empty($_GET['and'])) {
		$tradeteam = $_GET['and'];
	} else {
		header('Location: 404.php');
		die();
	}
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
	
	$member_result = mysqli_query($conn,"SELECT * FROM `member` WHERE id=$userID");
	$memberData = mysqli_fetch_array($member_result);
	$premium = $memberData['premium'];
	
	$league_result = mysqli_query($conn,"SELECT * FROM `league` WHERE id=$leagueid");
	$leagueData = mysqli_fetch_array($league_result, MYSQL_ASSOC);
	$league_year = $leagueData['year'];
	
	$sameleague_result = mysqli_query($conn,"SELECT teamname,location FROM `team` WHERE league=$leagueid AND id=$tradeteam");
	$sameleague = false;
	if (mysqli_num_rows($sameleague_result) == 1) {
		$sameleague = true;
		$tradeteam_result = mysqli_query($conn,"SELECT id,firstname,lastname,position FROM `player` WHERE team=$tradeteam");
		$ownplayers_result = mysqli_query($conn,"SELECT id,firstname,lastname,position FROM `player` WHERE team=$teamid");
		$sameleagueData = mysqli_fetch_array($sameleague_result);
	} else {
		//Either trade team doesn't exist or not in same league
		header('Location: 404.php');
		die();
	}
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
    <link href="../css/newtrade.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
	<script src="js/jquery.number.js"></script>
    <script src="../js/bootstrap.js"></script>
	<script>document.write('<style>.playerbox { display: none; }</style>');</script>
	<script>
	$( document ).ready(function() {
	var offered = [];
	var requested = [];
		$('.playerselect').on('change click', function(e){
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
		  
		  //Contract Info
			$.ajax({
				url: 'getcontract.php',
				type: 'POST',
				dataType: 'json',
				data: {'playerid': value},
				success: function(data) {
				
					var base = data[0]["base"];
					var bonus = data[0]["bonus"];
					var conlength = data.length;
					var deadcap = 0;
					for (var i=0;i<conlength;i++) {
						deadcap = parseInt(data[i]["bonus"])+parseInt(deadcap);
					}
					
					$(".playersal").number(base);
					$(".playersal").prepend("$");
					
					$(".playerbon").number(bonus);
					$(".playerbon").prepend("$");
					
					$(".tradedead").number(deadcap);
					$(".tradedead").prepend("$");
					
					$(".conlength").text(" "+conlength+" years");
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
		 
		});
		
		$('#offerplayer').on('click', function(e) {
			var value = $('#ownplayers').val();
			$.ajax({
			  url: 'playerdata.php',
			  type: 'POST',
			  dataType : 'json',
			  data: {'playerid': value},
			  success: function(data) {
				var name = data[0];
				var position = data[1];
				
				$('#givebox').append('<a target="_blank" href="player.php?playerid='+value+'">'+position+' '+name+'</a><br>');
				$("#ownplayers option:selected").remove();
				offered[offered.length] = value;
			  },
			  error: function(xhr, desc, err) {
			  console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			  }
			}); //end ajax 
			
			//Update Table
			$.ajax({
				url: 'getcontract.php',
				type: 'POST',
				dataType: 'json',
				data: {'playerid': value, 'team': '<?php echo $teamid;?>'},
				success: function(data) {
				var bonus_array = [];
				var totcaphit = 0;
				var contract_length = data.length;
					for (var i=0;i<contract_length;i++) {
						var year = i+1;
						var bonus = data[i]["bonus"];
						var base = data[i]["base"];
						var caphit = data[i]["caphit"];
						
						bonus_array[bonus_array.length] = bonus;
						
						var spendid = "#year-"+year+"-spend";
						var year_spending = $(spendid).text();
						
						year_spending = year_spending.replace(/[^0-9\.]/g, "");
						
						var new_spending = year_spending - base;
						$(spendid).number(new_spending);
						$(spendid).prepend("$");
					}
					
					for (var i=0;i<bonus_array.length;i++) {
						totcaphit = parseInt(totcaphit)+parseInt(bonus_array[i]);
					}
					var dead_spending = $("#year-1-dead").text();
					var year_spending = $("#year-1-spend").text();
					
					dead_spending = dead_spending.replace(/[^0-9\.]/g, "");
					year_spending = year_spending.replace(/[^0-9\.]/g, "");
					
					var new_dead = parseInt(dead_spending)+parseInt(totcaphit);
					var new_spending = parseInt(year_spending)+parseInt(totcaphit);
					$("#year-1-dead").number(new_dead);
					$("#year-1-dead").prepend("$");
					$("#year-1-spend").number(new_spending);
					$("#year-1-spend").prepend("$");
					
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
		});
		$('#requestplayer').on('click', function(e) {
			var value = $('#otherplayers').val();
			$.ajax({
			  url: 'playerdata.php',
			  type: 'POST',
			  dataType : 'json',
			  data: {'playerid': value},
			  success: function(data) {
				var name = data[0];
				var position = data[1];
				
				$('#receivebox').append('<a target="_blank" href="player.php?playerid='+value+'">'+position+' '+name+'</a><br>');
				requested[requested.length] = value;
				$("#otherplayers option:selected").remove();
			  },
			  error: function(xhr, desc, err) {
			  console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			  }
			}); //end ajax 
			
			//Update Table
			$.ajax({
				url: 'getcontract.php',
				type: 'POST',
				dataType: 'json',
				data: {'playerid': value, 'team': '<?php echo $tradeteam;?>'},
				success: function(data) {
				var bonus_array = [];
				var contract_length = data.length;
					for (var i=0;i<contract_length;i++) {
						var year = i+1;
						var bonus = data[i]["bonus"];
						var base = data[i]["base"];
						var caphit = data[i]["caphit"];
						
						bonus_array[bonus_array.length] = bonus;
						
						var spendid = "#year-"+year+"-spend";
						var year_spending = $(spendid).text();
						
						year_spending = year_spending.replace(/[^0-9\.]/g, "");
						
						var new_spending = parseInt(year_spending) + parseInt(base);
						$(spendid).number(new_spending);
						$(spendid).prepend("$");
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
		});
		
		$('#sendoffer').on('click', function(e) {
			var msg = $('#msgbox').val();
			$.ajax({
			  url: 'sendoffer.php',
			  type: 'POST',
			  dataType : 'text',
			  data: {'teamone': '<?php echo $teamid;?>', 'teamtwo': '<?php echo $tradeteam;?>','offered_players': offered, 'requested_players': requested, 'message': msg},
			  success: function(data) {
				if (data == 'success') {
					window.location.href = "trades.php?teamid=<?php echo $teamid;?>&tab=sent";
				}
			  },
			  error: function(xhr, desc, err) {
			  console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			  }
			}); //end ajax 
		});
		
		$('#resetoffer').on('click',function(e) {
			offered = [];
			requested = [];
			$('#givebox').html("");
			$('#receivebox').html("");
			$('#msgbox').val("");
			location.reload();
		});
	});
	</script>
    <title>RedZone Rush</title>
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
            </div>
			<h3>Team Links</h3>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
				<li>
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
                </li>
				<li>
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
        <div class="col-sm-9 col-lg-8">
		<ol class="breadcrumb">
		<?php
		$leaguename = $leagueData['leaguename'];
		echo "
		  <li><a href=\"league.php?leagueid=".$leagueid."\">".$leaguename."</a></li>
		  <li><a href='team.php?teamid=".$teamid."'>".$location." ".$teamname."</a></li><li class='active'>Offer Trade</li>";
		?>
		</ol>
			<div class="main">
				<h3>Trading with <?php 
					if ($sameleague) {
						echo $sameleagueData['location']." ".$sameleagueData['teamname'];
					}
				?></h3>
				<div class="row mainrow">
					<div class="well col-md-4 col-sm-5 tradebox"><b>Give</b>
						<div id="givebox">
						</div>
					</div>
					<div class="well col-md-4 col-sm-5 tradebox"><b>Receive</b>
						<div id="receivebox">
						</div>
					</div>
				</div>
				<div class="row mainrow">
					<div class="col-sm-8 well">
					<p><b><?php echo $myteamData['location']." ".$myteamData['teamname'];?> Salary Outlook (if trade accepted)</b></p>
					<div class='table-responsive'>
						<table class='table'>
							<thead>
								<tr>
									<th>Year</th>
									<th>Dead Money</th>
									<th>Total Spending (includes dead money)</th>
									<th>Total Cap</th>
								</tr>
							</thead>
							<tbody>
							<?php
								for ($year=1;$year<7;$year++) {
									$contract_year = $league_year + $year-1;
									$total_result = mysqli_query($conn,"SELECT * FROM contract WHERE team=$teamid AND year=$contract_year");
									$total_salary = 0;
									$deadcap = 0;
									while ($totalData = mysqli_fetch_array($total_result)) {
										$total_salary = $total_salary + $totalData['bonus'] + $totalData['base'];
										$deadcap = $deadcap + $totalData['deadcap'];
									}
									echo "<tr id='year-".$year."'>
											<td id='year-".$year."-td'>".$contract_year."</td>
											<td id='year-".$year."-dead'>$".number_format($deadcap)."</td>
											<td id='year-".$year."-spend'>$".number_format($total_salary)."</td>
											<td id='year-".$year."-total'>$130,000,000</td>
										</tr>";
								}
								
							?>
							</tbody>
						</table>
					</div>
					</div>
					<div class="col-sm-4 col-xs-12">
						<div class="playerbox">
							<b>Selected Player:</b><br>
							<b><span class="playername"></span></b>
							<p><span class="playerposition"></span></p>
							<p><b>Rating: <span class="playerrating"></span></b></p>
							<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
							<p><span class="playerhealth"></span></p>
							<b>Contract Info</b><br>
							<table>
							<tr>
								<td>Salary This Year: </td>
								<td class="playersal"></td>
							</tr>
							<tr>
								<td>Bonus This Year: </td>
								<td class="playerbon"></td>
							</tr>
							<tr>
								<td>Dead Cap if Traded: </td>
								<td class="tradedead"></td>
							</tr>
							<tr>
								<td>Contract Length: </td>
								<td class="conlength"></td>
							</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="row mainrow">
					<div class="well col-md-4 col-sm-5 tradebox"><p><b>Your Team</b></p>
					<p>Players</p>
					<select class="form-control playerselect" id="ownplayers">
					<?php
						while ($playerData = mysqli_fetch_array($ownplayers_result)) {
							echo "<option value='".$playerData['id']."'>";
							echo $playerData['position']." ".$playerData['firstname']." ".$playerData['lastname'];
							echo "</option>";
						}
					?>
					</select>
					<p>Draft Picks</p>
					<select class="form-control pickselect" id="ownpicks">
					</select>
					<button class="btn btn-primary" id="offerplayer">Offer</button>
					</div>
					<div class="well col-sm-5 col-md-4 tradebox"><p><b>Other Team</b></p>
					<p>Players</p>
					<select class="form-control playerselect" id="otherplayers">
					<?php
						while ($playerData = mysqli_fetch_array($tradeteam_result)) {
							echo "<option value='".$playerData['id']."'>";
							echo $playerData['position']." ".$playerData['firstname']." ".$playerData['lastname'];
							echo "</option>";
						}
					?>
					</select>
					<p>Draft Picks</p>
					<select class="form-control pickselect" id="otherpicks">
					</select>
					<button class="btn btn-primary" id="requestplayer">Request</button></b>
					</div>
					
				</div>
				<div class="row mainrow">
				<div class="col-md-8">
					<b>Message (optional)</b>
					<textarea class="form-control" id="msgbox" rows="3"></textarea>
					<button class="btn btn-primary" id="sendoffer">Send Offer</button>
					<button class="btn btn-default" id="resetoffer">Reset Offer</button>
				</div>
				</div>
			</div>
		</div>
		</div>
	</div>
	</body>
	</html>