<?php
	session_start();
	require_once('includes/functions.php');
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
if (!empty($_GET['playerid'])) {
		$playerid = $_GET['playerid'];
	} else {
		header('Location: 404.php');
	}
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	$player_result = mysqli_query($conn, "SELECT * FROM player WHERE id=$playerid");
	$playerData = mysqli_fetch_array($player_result);
	$player_league = $playerData['league'];
	$player_team = $playerData['team'];
	$player_name = $playerData['firstname']." ".$playerData['lastname'];
	$player_position = $playerData['position'];
	$overall_now = $playerData['overall_now'];
	$player_height = $playerData['height'];
	$player_weight = $playerData['weight'];
	$draft_pos = $playerData['draft_pos'];
	$draft_round = $playerData['draft_round'];
	$start_year = $playerData['start_year'];
	
	$league_result = mysqli_query($conn,"SELECT id,leaguename,year FROM league WHERE id=$player_league");
	$leagueData = mysqli_fetch_array($league_result);
	//$leagueid = $leagueData['id'];
	$leaguename = $leagueData['leaguename'];
	$league_year = $leagueData['year'];
	
	$player_exp = $league_year - $start_year;
	
	if ($player_team != 0) {
		$team_result = mysqli_query($conn, "SELECT id, location, teamname FROM team WHERE id=$player_team");
		$teamData = mysqli_fetch_array($team_result);
		$teamname = $teamData['location']." ".$teamData['teamname'];
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
    <link href="css/player.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.number.js"></script>
    <script src="js/bootstrap.js"></script>
	<script>
	$( document ).ready(function() {
		$.ajax({
		  url: 'fademand.php',
		  type: 'POST',
		  dataType : 'json',
		  data: {'position': '<?php echo $player_position; ?>', 'rating': '<?php echo $overall_now; ?>', 'exp': '<?php echo $player_exp;?>', 'years': $( "#years	").val()},
		  success: function(data) {
			var totsal = 0;
			var totbon = 0;
			$.each(data, function(index, value) {
				var salelementid = "#salyear"+index;
				var bonelementid = "#bonyear"+index;
				var sumelementid = "#sumyear"+index;
				var totelementid = "#totyear"+index;
				
				var salvalue = value[1];
				var bonvalue = value[2];
				var sumvalue = value[1]+value[2];
				
				$(salelementid).html(salvalue);
				$(bonelementid).html(bonvalue);
				$(sumelementid).html(sumvalue);
				
				for ( var i = data.length; i < 6; i++ ) {
					var emptysalid = "#salyear"+i;
					var emptybonid = "#bonyear"+i;
					var emptysumid = "#sumyear"+i;
					$(emptysalid).html("0");
					$(emptybonid).html("0");
					$(emptysumid).html("0");
				}
				
				for (var j = 0; j < 6; j++) {
					var sumid = "#sumyear"+j;
					var totid = "#totyear"+j;
					var aftid = "#aftyear"+j;
					
					var sumvalue = parseInt($(sumid).html());
					var totvalue = parseInt($(totid).html());
					var aftvalue = sumvalue+totvalue;
					$(aftid).html(aftvalue);
				}
				
				totsal = totsal + value[1];
				totbon = totbon + value[2];
				
			});
			$('td.number').number(true);
			$('td.number').prepend("$");
			$("#base").attr({
				min: totsal,
				value: totsal
			});
			$("#bonus").attr({
				min: totbon,
				value: totbon
			});
		  },
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }
		}); // end ajax call
	});
	</script>
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
        <div class="col-md-2">
          <div class="side-bar">
            <div class="team-card">
            <?php 
			$myteam_result = mysqli_query($conn,"SELECT id,division,location,teamname,season_win,season_loss,season_tie,logofile FROM `team` WHERE league=$player_league AND owner=$userID");
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
              <img src=\"".$myteam_logopath."\" width=\"200\"/>
            </a> 
            <b><a href=\"team.php?teamid=".$myteamid."\">
              <p>".$myteamname."</p>
            </a><p>".$myteamrecord."</p></b>";
			echo "<p>Week 1</p>
            <p>Next game: @<a href=\"#\">DAL</a></p>";	
			}
			?></div>
          </div>
        </div>
        <div class="col-md-8">
		<ol class="breadcrumb">
		<?php
			echo "<li><a href=\"league.php?leagueid=".$player_league."\">".$leaguename."</a></li>";
			if ($player_team != 0) {
				echo "<li><a href=\"team.php?teamid=".$player_team."\">".$teamname."</a></li>";
			} else {
				echo "<li><a href=\"freeagents.php?leagueid=".$player_league."\">Free Agents</a></li>";
			}
			echo "<li class=\"active\">".$player_name."</li>";
		?>
		</ol>
          <div class="main">
            <div class="player-header">
              <div class="container">
                <div class="row">
                  <div class="col-md-2">
                    <div class="first-col">
                      <h3 class="name"><?php echo $player_name; ?></h3>
                      <h4 class="position"><?php
					  echo $player_position; 
					  if ($player_team != 0) {
						echo ", <a href=\"team.php?teamid=".$player_team."\">".$teamname."</a>";
					  } else {
						echo ", Free Agent";
					  }
					  echo "<p class=\"rating\">Rating: ".$overall_now."</p>";
					  ?></h4>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="sec-col">
                      <h4>
                        <b>Player Info</b>
                      </h4>
                      <p>Height: <?php
					  $inches = $player_height%12;
					  $feet = ($player_height-$inches)/12;
					  echo $feet."'".$inches."\"";
					  ?>
					  </p>
                      <p>Weight: <?php echo $player_weight." lbs"; ?></p>
                      <p>Drafted: <?php echo $start_year." Round ".$draft_round.", Pick ".$draft_pos; ?></p>
                      <p>Experience: <?php echo $player_exp; ?></p>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="third-col">
                      <h4>
                        <b>Awards</b>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="stats">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                  <a href="#careerstats" role="tab" data-toggle="tab">Career Stats</a>
                </li>
                <li>
                  <a href="#gamelogs" role="tab" data-toggle="tab">Game Logs</a>
                </li>
                <li>
                  <a href="#progression" role="tab" data-toggle="tab">Player Progression</a>
                </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="careerstats">Stats go here</div>
                <div class="tab-pane" id="gamelogs">Game Logs go here</div>
                <div class="tab-pane" id="progression">
                  <div class="container">
                    <div class="row">
                      <div class="col-md-9">
                        <div class="progression">
                            <div class="row">
                              <div class="col-md-1"><p>Overall:</p></div>
							  <div class="col-md-1"><p>80</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 60%;">60</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 20%">+20</div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Accuracy:</p></div>
							  <div class="col-md-1"><p>94</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 80%;">87</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="7"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 7%">+7</div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Speed:</p></div>
                              <div class="col-md-1"><p>36</p></div>
							  <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="36" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 36%;">36</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">+0</span>
                                  </div>
                                </div>
                              </div>
							  
                            </div>
                            <div class="row">
                              <div class="col-md-1"><p>Strength:</p></div>
							  <div class="col-md-1"><p>59</p></div>
                              <div class="col-md-10">
                                <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="46" aria-valuemin="0"
                                  aria-valuemax="100" style="width: 46%;">46</div>
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="13"
                                  aria-valuemin="0" aria-valuemax="100" style="width: 13%">+13</div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
				
              </div>
            </div>
				<script>
					$(function(){
					
					  $('#years').on('change', function(e){
						e.preventDefault();
						$.ajax({
						  url: 'fademand.php',
						  type: 'POST',
						  dataType : 'json',
						  data: {'position': '<?php echo $player_position; ?>', 'rating': '<?php echo $overall_now; ?>', 'exp': '<?php echo $player_exp;?>', 'years': $( "#years	").val()},
						  success: function(data) {
							var totsal = 0;
							var totbon = 0;
							$.each(data, function(index, value) {
								var salelementid = "#salyear"+index;
								var bonelementid = "#bonyear"+index;
								var sumelementid = "#sumyear"+index;
								var totelementid = "#totyear"+index;
								
								var salvalue = value[1];
								var bonvalue = value[2];
								var sumvalue = value[1]+value[2];
								
								$(salelementid).html(salvalue);
								$(bonelementid).html(bonvalue);
								$(sumelementid).html(sumvalue);
								
								for ( var i = data.length; i < 6; i++ ) {
									var emptysalid = "#salyear"+i;
									var emptybonid = "#bonyear"+i;
									var emptysumid = "#sumyear"+i;
									$(emptysalid).html("0");
									$(emptybonid).html("0");
									$(emptysumid).html("0");
								}
								
								for (var j = 0; j < 6; j++) {
									var sumid = "#sumyear"+j;
									var totid = "#totyear"+j;
									var aftid = "#aftyear"+j;
									
									var sumvalue = $(sumid).html();
									var totvalue = $(totid).html();
									
									var sum =  sumvalue.replace(/[^0-9\.]/g, "");
									var tot =  totvalue.replace(/[^0-9\.]/g, "");
									
									var sumint = parseInt(sum);
									var totint = parseInt(tot);
									var aftvalue = sumint+totint;
									$(aftid).html(aftvalue);
								}
								totsal = totsal + value[1];
								totbon = totbon + value[2];
							});
							$('td.number').number(true);
							$('td.number').prepend("$");
							$("#base").attr({
								min: totsal,
								value: totsal
							});
							$("#bonus").attr({
								min: totbon,
								value: totbon
							});
						  },
						  error: function(xhr, desc, err) {
							console.log(xhr);
							console.log("Details: " + desc + "\nError:" + err);
						  }
						}); // end ajax call
					  });
					  });
				</script>
				<?php
				$user_team_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$player_league AND owner=$userID");
				$userteam_rows = mysqli_num_rows($user_team_result);
				
				if ($player_team != 0) {
					echo "<div class=\"panel-group contract\" id=\"accordion\">
				  <div class=\"panel panel-default\">
					<div class=\"panel-heading\">
					  <h4 class=\"panel-title\">
						<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapseOne\">
						  Click to view contract information
						</a>
					  </h4>
					</div>
					<div id=\"collapseOne\" class=\"panel-collapse collapse\">
					  <div class=\"panel-body\">";
				echo "<div class=\"table-responsive\">
				<table class=\"table\">
					<thead>
						<tr>
							<th>Year</th>
							<th>Bonus</th>
							<th>Base Salary</th>
							<th>Total Salary</th>
							<th>Team Salaries</th>
							<th>Team Cap</th>
						</tr>
					</thead>
					<tbody>";
					for ($i=0;$i<6;$i++) {
						$salary_year = $league_year+$i;
						$contract_result = mysqli_query($conn,"SELECT * FROM contract WHERE player=$playerid AND year=$salary_year");
						$contractData = mysqli_fetch_array($contract_result);
						$bonus = $contractData['bonus'];
						$base = $contractData['base'];
						$total = $bonus + $base;
						
						$total_result = mysqli_query($conn,"SELECT * FROM contract WHERE team=$player_team AND year=$salary_year");
						$total_salary = 0;
						while ($totalData = mysqli_fetch_array($total_result)) {
							$total_salary = $total_salary + $totalData['bonus'] + $totalData['base'];
						}
						echo "<tr>
						<td>".$salary_year."</td>
						<td>$".number_format($bonus)."</td>
						<td>$".number_format($base)."</td>
						<td>$".number_format($total)."</td>
						<td>$".number_format($total_salary)."</td>
						<td>$130,000,000</td>
						</tr>";
					}
					
				echo "</tbody>
				</table>
				</div>
				</div>
				</div>
				</div>
				</div>";
				} else if ($userteam_rows == 1) {
					$userteamData = mysqli_fetch_array($user_team_result);
					$teamid = $userteamData['id'];
					echo "<div class=\"well contract\">";
					echo "<h4>Send Contract Offer</h4>";
					echo "<form class=\"form-horizontal\" action=\"player.php\" method=\"POST\" id=\"faoffer\" name=\"faoffer\" role=\"form\">
					<div class=\"row\">
						<div class=\"col-md-5\">
							<div class=\"form-group\">
								<label for=\"base\" class=\"col-sm-5 control-label\">Base Contract Value: </label>
								<div class=\"col-sm-6\">
								  <input type=\"number\" class=\"form-control\" id=\"base\" name=\"base\" />
								</div>
							</div>
						</div>
						<div class=\"col-md-4\">
							<div class=\"form-group\">
								<label for=\"bonus\" class=\"col-sm-4 control-label\">Guaranteed: $</label>
								<div class=\"col-sm-7\">
								  <input type=\"number\" class=\"form-control\" id=\"bonus\" name=\"bonus\"  />
								</div>
							</div>
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-md-5\">
							<div class=\"col-sm-2 col-sm-offset-3\">
								<b>Years: </b>
							</div>
							<div class=\"col-sm-3\">
								<select class=\"form-control\" name=\"years\" id=\"years\">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
								</select>
							</div>
						</div>
					</div>
					<div class=\"row\">
							<div class=\"col-md-3 col-md-offset-7\">
							<button class=\"btn btn-primary\" type=\"submit\" id=\"offersubmit\">Submit Offer</button>
							</div>
					</div>
					</form>";
					$teamid = $userteamData['id'];
				echo "<p><i>This is the minimum contract the player will accept. He will be more likely to accept your offer if you offer a longer contract with more guaranteed money.</i></p><h4>Salary situation if signed:</h4><div class=\"table-responsive\" id=\"salarytable\">
					<table class=\"table\">
						<thead>
							<tr>
								<th width=\"10%\">Year</th>
								<th width=\"15%\">Salary</th>
								<th width=\"15%\">Bonus</th>
								<th width=\"15%\">Total</th>
								<th width=\"15%\">Current Spending</th>
								<th width=\"15%\">If Signed</th>
								<th width=\"15%\">Total Cap</th>
							</tr>
						</thead>
						<tbody>";
						
					for ($i=0;$i<6;$i++) {
								$contract_year = $league_year + $i;
								$total_result = mysqli_query($conn,"SELECT * FROM contract WHERE team=$teamid AND year=$contract_year");
								$total_salary = 0;
								$deadcap = 0;
								while ($totalData = mysqli_fetch_array($total_result)) {
									$total_salary = $total_salary + $totalData['bonus'] + $totalData['base'];
									$deadcap = $deadcap + $totalData['deadcap'];
								}
								echo "<tr><td>".$contract_year."</td>
									<td id=\"salyear".$i."\" class=\"number\">$0</td>
									<td id=\"bonyear".$i."\" class=\"number\">$0</td>
									<td id=\"sumyear".$i."\" class=\"number\">$0</td>
									<td id=\"totyear".$i."\" class=\"number\">".$total_salary."</td>
									<td id=\"aftyear".$i."\" class=\"number\">$0</td>
									<td>$130,000,000</td></tr>";
							}
						echo "</tbody>
								</table>
							</div>";
					echo "</div>";
					
				}
				?>
						
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
