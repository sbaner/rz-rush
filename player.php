<?php
	session_start();
	require_once('includes/functions.php');
	require_once('includes/getweek.php');
	require_once('includes/statcalc.php');
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
	
	$player_result = mysqli_query($conn, "SELECT player.*,attributes.* FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.id=$playerid ORDER BY attributes.year LIMIT 1");
	if (mysqli_num_rows($player_result) == 1) {
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
	} else {
		header('Location: 404.php');
		die("No such player");
	}
	
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
	
	$hasstats = false;
	
	//Retrieve and process POSTed free agent offer
	if (isset($_POST['offersubmit'])) {
		$offer_team = $_POST['teamid'];
		$alreadybid_result = mysqli_query($conn,"SELECT * FROM bids WHERE player=$playerid AND team=$offer_team");
		$bid_rows = mysqli_num_rows($alreadybid_result);
		
		if ($bid_rows == 0)  {
			if ($_POST['base']!="") {
				$totalbase_offer = $_POST['base']*1000;
			}
			
			if ($_POST['bonus']!="") {
				$totalbonus_offer = $_POST['bonus']*1000;
			}
			
			$yearsoffered = $_POST['years'];
			
			
			$demandarray = fa_demand($player_position,$overall_now,$player_exp,$yearsoffered);
			$totalbase_demand = 0;
			$totalbonus_demand = 0;
			foreach($demandarray as $yeararray) {
				$totalbase_demand = $totalbase_demand + $yeararray[1];
				$totalbonus_demand = $totalbonus_demand + $yeararray[2];
			}
			
			$base_difference = round(($totalbase_offer - $totalbase_demand)/$yearsoffered,-3);
			$bonus_difference = round(($totalbonus_offer - $totalbonus_demand)/$yearsoffered,-3);
			
			mysqli_query($conn,"INSERT INTO `bids` (player,team,years,totalbonus,totalbase) VALUES ($playerid,$offer_team,$yearsoffered,$totalbonus_offer,$totalbase_offer)");
			
			for ($i=0;$i<$yearsoffered;$i++) {
				$baseoffer = $demandarray[$i][1] + $base_difference;
				$bonusoffer = $demandarray[$i][2] + $bonus_difference;
				$yearnum = $i+1;
				$query = "UPDATE `bids` SET bonus_".$yearnum."=$bonusoffer,base_".$yearnum."=$baseoffer WHERE player=$playerid AND team=$offer_team";
				mysqli_query($conn,$query);
			}
		}
	}
	
	if (isset($_POST['retract'])) {
		$retract_team = $_POST['teamid'];
		mysqli_query($conn,"DELETE FROM `bids` WHERE player=$playerid AND team=$retract_team");
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
	<?php
	if ($player_team == 0) {
	echo "<script>
	$( document ).ready(function() {
		var years = 0;
		var totsal = 0;
		var totbon = 0;
		var salArray = [];
		var bonArray = [];
		$.ajax({
		  url: 'fademand.php',
		  type: 'POST',
		  dataType : 'json',
		  data: {'position': '".$player_position,"', 'rating': '".$overall_now."', 'exp': '".$player_exp."', 'years': $( \"#years\").val()},
		  success: function(data) {
			years = data.length;
			
			$.each(data, function(index, value) {
				var salelementid = \"#salyear\"+index;
				var bonelementid = \"#bonyear\"+index;
				var sumelementid = \"#sumyear\"+index;
				var totelementid = \"#totyear\"+index;
				
				var salvalue = value[1];
				var bonvalue = value[2];
				var sumvalue = value[1]+value[2];
				
				salArray[index] = salvalue;
				bonArray[index] = bonvalue;
				
				$(salelementid).html(salvalue);
				$(bonelementid).html(bonvalue);
				$(sumelementid).html(sumvalue);
				
				for ( var i = data.length; i < 6; i++ ) {
					var emptysalid = \"#salyear\"+i;
					var emptybonid = \"#bonyear\"+i;
					var emptysumid = \"#sumyear\"+i;
					$(emptysalid).html(\"0\");
					$(emptybonid).html(\"0\");
					$(emptysumid).html(\"0\");
				}
				
				for (var j = 0; j < 6; j++) {
					var sumid = \"#sumyear\"+j;
					var totid = \"#totyear\"+j;
					var aftid = \"#aftyear\"+j;
					
					var sumvalue = parseInt($(sumid).html());
					var totvalue = parseInt($(totid).html());
					var aftvalue = sumvalue+totvalue;
					$(aftid).html(aftvalue);
				}
				
				totsal = totsal + value[1];
				totbon = totbon + value[2];
				
			});
			$('td.number').number(true);
			$('td.number').prepend(\"$\");
			$(\"#base\").attr({
				min: totsal/1000,
				value: totsal/1000
			});
			$(\"#bonus\").attr({
				min: totbon/1000,
				value: totbon/1000
			});
			$(\"#saltext\").html(data.length+\" year(s), $\"+$.number(totsal)+\" base,\");
			$(\"#bontext\").html(\"$\"+$.number(totbon)+\" guaranteed.\");
		  },
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log(\"Details: \" + desc + \"Error:\" + err);
		  }
		}); // end ajax call
		
		
		$('#years').on('change', function(e){
		e.preventDefault();
		$.ajax({
		  url: 'fademand.php',
		  type: 'POST',
		  dataType : 'json',
		  data: {'position': '".$player_position,"', 'rating': '".$overall_now."', 'exp': '".$player_exp."', 'years': $( \"#years\").val()},
		  success: function(data) {
			totsal = 0;
			totbon = 0;
			$.each(data, function(index, value) {
				years = data.length;
				var salelementid = \"#salyear\"+index;
				var bonelementid = \"#bonyear\"+index;
				var sumelementid = \"#sumyear\"+index;
				var totelementid = \"#totyear\"+index;
				
				var salvalue = value[1];
				var bonvalue = value[2];
				var sumvalue = value[1]+value[2];
				
				salArray[index] = salvalue;
				bonArray[index] = bonvalue;
				
				$(salelementid).html(salvalue);
				$(bonelementid).html(bonvalue);
				$(sumelementid).html(sumvalue);
				
				totsal = totsal + value[1];
				totbon = totbon + value[2];
				
			});
			for ( var i = data.length; i < 6; i++ ) {
					var emptysalid = \"#salyear\"+i;
					var emptybonid = \"#bonyear\"+i;
					var emptysumid = \"#sumyear\"+i;
					$(emptysalid).html(\"0\");
					$(emptybonid).html(\"0\");
					$(emptysumid).html(\"0\");
				}
				
				for (var j = 0; j < 6; j++) {
					var sumid = \"#sumyear\"+j;
					var totid = \"#totyear\"+j;
					var aftid = \"#aftyear\"+j;
					
					var sumvalue = $(sumid).html();
					var totvalue = $(totid).html();
					
					var sum =  sumvalue.replace(/[^0-9\.]/g, \"\");
					var tot =  totvalue.replace(/[^0-9\.]/g, \"\");
					
					var sumint = parseInt(sum);
					var totint = parseInt(tot);
					var aftvalue = sumint+totint;
					$(aftid).html(aftvalue);
				}
			$('td.number').number(true);
			$('td.number').prepend(\"$\");
			$(\"#base\").attr({
				min: totsal/1000,
				value: totsal/1000
			});
			$(\"#bonus\").attr({
				min: totbon/1000,
				value: totbon/1000
			});
			$(\"#saltext\").html(data.length+\" year(s), $\"+$.number(totsal)+\" base,\");
			$(\"#bontext\").html(\"$\"+$.number(totbon)+\" guaranteed.\");
		  },
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log(\"Details: \" + desc + \"Error:\" + err);
		  }
		}); // end ajax call
	  });
		
			//Update table when values change
			 $('#base').on('change', function(){
				var newbase = $(this).val();
				if (newbase >= totsal/1000) {
					var change = newbase - totsal/1000;
					var yearlychange = Math.floor(change / years);
					for (var k = 0; k < years; k++) {
						var salelementid = \"#salyear\"+k;
						var bonelementid = \"#bonyear\"+k;
						var sumelementid = \"#sumyear\"+k;
						var newvalue = salArray[k] + yearlychange*1000;
						$(salelementid).html(newvalue);
						
						var bonus = parseInt($(bonelementid).html().replace(/[^0-9\.]/g, \"\"));
						var newsum = bonus+newvalue;
						$(sumelementid).html(newsum);
						
					}
					for (var j = 0; j < 6; j++) {
					var sumid = \"#sumyear\"+j;
					var totid = \"#totyear\"+j;
					var aftid = \"#aftyear\"+j;
					
					var sumvalue = $(sumid).html();
					var totvalue = $(totid).html();
					
					var sum =  sumvalue.replace(/[^0-9\.]/g, \"\");
					var tot =  totvalue.replace(/[^0-9\.]/g, \"\");
					
					var sumint = parseInt(sum);
					var totint = parseInt(tot);
					var aftvalue = sumint+totint;
					$(aftid).html(aftvalue);
				}
				}
				$(\"#saltext\").html(years+\" year(s), $\"+$.number(newbase*1000)+\" base,\");
				$('td.number').number(true);
				$('td.number').prepend(\"$\");
				
			 });
			 
			 $('#bonus').on('change', function(){
				var newbonus = $(this).val();
				if (newbonus >= totbon/1000) {
					var change = newbonus - totbon/1000;
					var yearlychange = Math.floor(change / years);
					for (var m = 0; m < years; m++) {
						var salelementid = \"#salyear\"+m;
						var bonelementid = \"#bonyear\"+m;
						var sumelementid = \"#sumyear\"+m;
						var newvalue = bonArray[m] + yearlychange*1000;
						$(bonelementid).html(newvalue);
						
						var salary = parseInt($(salelementid).html().replace(/[^0-9\.]/g, \"\"));
						var newsum = salary+newvalue;
						$(sumelementid).html(newsum);
					}
					for (var j = 0; j < 6; j++) {
					var sumid = \"#sumyear\"+j;
					var totid = \"#totyear\"+j;
					var aftid = \"#aftyear\"+j;
					
					var sumvalue = $(sumid).html();
					var totvalue = $(totid).html();
					
					var sum =  sumvalue.replace(/[^0-9\.]/g, \"\");
					var tot =  totvalue.replace(/[^0-9\.]/g, \"\");
					
					var sumint = parseInt(sum);
					var totint = parseInt(tot);
					var aftvalue = sumint+totint;
					$(aftid).html(aftvalue);
				}
				}
				$(\"#bontext\").html(\"$\"+$.number(newbonus*1000)+\" guaranteed.\");
				$('td.number').number(true);
				$('td.number').prepend(\"$\");
			 }); 
	});
	</script>";
	}
	?>
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
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-sm-3 col-lg-2">
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
				echo "<div class=\"side-bar\">
            <div class=\"team-card\"><h3>My team</h3><a href=\"team.php?teamid=".$myteamid."\">
				  <img src=\"".$myteam_logopath."\" width=\"150\"/>
				</a> 
				<b><a href=\"team.php?teamid=".$myteamid."\">
				  <p>".$myteamname."</p>
				</a><p>".$myteamrecord."</p></b>";
				echo "<p>".getWeek($player_league)."</p></div>
          </div>";	
			}
			?>
			
        </div>
        <div class="col-sm-9 col-lg-8">
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
                <div class="row">
                  <div class="col-md-2 col-sm-3">
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
                  <div class="col-sm-3">
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
                  <div class="col-sm-5">
                    <div class="third-col">
                      <h4>
                        <b>Awards</b>
                      </h4>
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
                  <a href="#atts" role="tab" data-toggle="tab">Attributes</a>
                </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active fade in" id="careerstats">
				<?php
					$cstats_result = mysqli_query($conn,"SELECT player FROM `stats` WHERE player=$playerid ORDER BY year DESC");
					if (mysqli_num_rows($cstats_result)!=0) {
						$hasstats = true;
					}
				?>
					<ul class="nav nav-tabs categories" role="tablist">
						<li class="active">
						  <a href="#c_passing" role="tab" data-toggle="tab">Passing</a>
						</li>
						<li>
						  <a href="#c_rushing" role="tab" data-toggle="tab">Rushing</a>
						</li>
						<li>
						  <a href="#c_receiving" role="tab" data-toggle="tab">Receiving</a>
						</li>
						<li>
						  <a href="#c_blocking" role="tab" data-toggle="tab">Blocking</a>
						</li>
						<li>
						  <a href="#c_tackling" role="tab" data-toggle="tab">Tackling</a>
						</li>
						<li>
						  <a href="#c_pdefense" role="tab" data-toggle="tab">Pass Defense</a>
						</li>
						<li>
						  <a href="#c_kicking" role="tab" data-toggle="tab">Kicking</a>
						</li>
						<li>
						  <a href="#c_punting" role="tab" data-toggle="tab">Punting</a>
						</li>
						<li>
						  <a href="#c_returning" role="tab" data-toggle="tab">Returning</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane statspane active" id="c_passing">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th>Comp</th>
										<th>Att</th>
										<th>Comp %</th>
										<th>Yds</th>
										<th>Yds/Att</th>
										<th>Long</th>
										<th>TD</th>
										<th>Int</th>
										<th>Sck</th>
										<th>Sck Yd</th>
										<th>Rate</th>
										<th>ANY/A</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
									<?php
										if ($hasstats) {
											$cpassing_result = mysqli_query($conn,"SELECT year,team,abbrev,games,snaps,fum,fum_lost,pass_cmp,pass_att,pass_yds,pass_long,pass_td,pass_int,pass_sck,pass_scky FROM `stats` WHERE player=$playerid ORDER BY year DESC");
											while ($cpassingData = mysqli_fetch_array($cpassing_result)) {
												
												echo "<tr>";
												echo "<td>".$cpassingData['year']."</td>";
												echo "<td><a href='team.php?teamid=".$cpassingData['team']."'>".$cpassingData['abbrev']."</td>";
												echo "<td>".$cpassingData['games']."</td>";
												echo "<td>".$cpassingData['snaps']."</td>";
												echo "<td>".$cpassingData['pass_cmp']."</td>";
												echo "<td>".$cpassingData['pass_att']."</td>";
												$comppercent = round($cpassingData['pass_cmp']/$cpassingData['pass_att']*100,1);
												echo "<td>".$comppercent."</td>";
												echo "<td>".$cpassingData['pass_yds']."</td>";
												$ydsperatt = round($cpassingData['pass_yds']/$cpassingData['pass_att'],1);
												echo "<td>".$ydsperatt."</td>";
												echo "<td>".$cpassingData['pass_long']."</td>";
												echo "<td>".$cpassingData['pass_td']."</td>";
												echo "<td>".$cpassingData['pass_int']."</td>";
												echo "<td>".$cpassingData['pass_sck']."</td>";
												echo "<td>".$cpassingData['pass_scky']."</td>";
												$passerrating = round(passerRating($cpassingData['pass_att'],$cpassingData['pass_cmp'],$cpassingData['pass_yds'],$cpassingData['pass_td'],$cpassingData['pass_int']),1);
												$anya = round(anya($cpassingData['pass_att'],$cpassingData['pass_yds'],$cpassingData['pass_td'],$cpassingData['pass_int'],$cpassingData['pass_sck'],$cpassingData['pass_scky']),1);
												echo "<td>".$passerrating."</td>";
												echo "<td>".$anya."</td>";
												echo "<td>".$cpassingData['fum']."</td>";
												echo "<td>".$cpassingData['fum_lost']."</td>";
												echo "</tr>";
											}
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_rushing">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th>Att</th>
										<th>Yds</th>
										<th>Yds/Att</th>
										<th>Long</th>
										<th>TD</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
										<?php
										if ($hasstats) {
											$crushing_result = mysqli_query($conn,"SELECT year,team,abbrev,games,snaps,fum,fum_lost,rush_att,rush_yds,rush_td,rush_long FROM `stats` WHERE player=$playerid ORDER BY year DESC");
											while ($crushingData = mysqli_fetch_array($crushing_result)) {
												
												echo "<tr>";
												echo "<td>".$crushingData['year']."</td>";
												echo "<td><a href='team.php?teamid=".$crushingData['team']."'>".$crushingData['abbrev']."</td>";
												echo "<td>".$crushingData['games']."</td>";
												echo "<td>".$crushingData['snaps']."</td>";
												echo "<td>".$crushingData['rush_att']."</td>";
												echo "<td>".$crushingData['rush_yds']."</td>";
												$rushypc = round($crushingData['rush_yds']/$crushingData['rush_att'],1);
												echo "<td>".$rushypc."</td>";
												echo "<td>".$crushingData['rush_long']."</td>";
												echo "<td>".$crushingData['rush_td']."</td>";
												echo "<td>".$crushingData['fum']."</td>";
												echo "<td>".$crushingData['fum_lost']."</td>";
												echo "</tr>";
											}
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_receiving">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th>Rec</th>
										<th>Tgts</th>
										<th>Catch %</th>
										<th>Yds</th>
										<th>YAC</th>
										<th>Yds/Rec</th>
										<th>YAC/Rec</th>
										<th>Long</th>
										<th>TD</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
										<?php
										if ($hasstats) {
											$creceiving_result = mysqli_query($conn,"SELECT year,team,abbrev,games,snaps,fum,fum_lost,rec,rec_tgt,rec_yds,rec_td,rec_long,rec_yac FROM `stats` WHERE player=$playerid ORDER BY year DESC");
											while ($creceivingData = mysqli_fetch_array($creceiving_result)) {
												
												echo "<tr>";
												echo "<td>".$creceivingData['year']."</td>";
												echo "<td><a href='team.php?teamid=".$creceivingData['team']."'>".$creceivingData['abbrev']."</td>";
												echo "<td>".$creceivingData['games']."</td>";
												echo "<td>".$creceivingData['snaps']."</td>";
												echo "<td>".$creceivingData['rec']."</td>";
												echo "<td>".$creceivingData['rec_tgt']."</td>";
												$catchpercent = round($creceivingData['rec']/$creceivingData['rec_tgt']*100,1);
												echo "<td>".$catchpercent."</td>";
												echo "<td>".$creceivingData['rec_yds']."</td>";
												echo "<td>".$creceivingData['rec_yac']."</td>";
												$ydsperrec = round($creceivingData['rec_yds']/$creceivingData['rec'],1);
												$yacperrec = round($creceivingData['rec_yac']/$creceivingData['rec'],1);
												echo "<td>".$ydsperrec."</td>";
												echo "<td>".$yacperrec."</td>";
												echo "<td>".$creceivingData['rec_long']."</td>";
												echo "<td>".$creceivingData['rec_td']."</td>";
												echo "<td>".$creceivingData['fum']."</td>";
												echo "<td>".$creceivingData['fum_lost']."</td>";
												echo "</tr>";
											}
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_blocking">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th width="15%">Sacks Allowed</th>
										<th width="10%">Rush Yds</th>
										<th width="10%">Rush Att</th>
										<th>Rush YPC</th>
									</thead>
									<tbody>
										<?php
										if ($hasstats) {
											$cblocking_result = mysqli_query($conn,"SELECT year,team,abbrev,games,snaps,sckallow,ol_rushatt,ol_rushyds FROM `stats` WHERE player=$playerid ORDER BY year DESC");
											while ($cblockingData = mysqli_fetch_array($cblocking_result)) {
												
												echo "<tr>";
												echo "<td>".$cblockingData['year']."</td>";
												echo "<td><a href='team.php?teamid=".$cblockingData['team']."'>".$cblockingData['abbrev']."</td>";
												echo "<td>".$cblockingData['games']."</td>";
												echo "<td>".$cblockingData['snaps']."</td>";
												echo "<td>".$cblockingData['sckallow']."</td>";
												echo "<td>".$cblockingData['ol_rushyds']."</td>";
												echo "<td>".$cblockingData['ol_rushatt']."</td>";
												$olrushypc = round($cblockingData['ol_rushyds']/$cblockingData['ol_rushatt'],1);
												echo "<td>".$olrushypc."</td>";
												echo "</tr>";
											}
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_tackling">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th>Run Snaps</th>
										<th>Pass Snaps</th>
										<th>Tackles</th>
										<th>Missed Tackles</th>
										<th>Sacks</th>
										<th>Sack Yds</th>
										<th>Tck for Loss</th>
										<th>Forced Fum</th>
										<th>Fum Yds</th>
										<th>Fum TD</th>
									</thead>
									<tbody>
										<?php
											if ($hasstats) {
												$ctackling_result = mysqli_query($conn,"SELECT year,team,abbrev,games,runsnaps,passsnaps,tackles,miss_tck,sack,sack_yds,tfl,ff,f_yds,f_td FROM `stats` WHERE player=$playerid ORDER BY year DESC");
												while ($ctacklingData = mysqli_fetch_array($ctackling_result)) {
													
													echo "<tr>";
													echo "<td>".$ctacklingData['year']."</td>";
													echo "<td><a href='team.php?teamid=".$ctacklingData['team']."'>".$ctacklingData['abbrev']."</td>";
													echo "<td>".$ctacklingData['games']."</td>";
													echo "<td>".$ctacklingData['runsnaps']."</td>";
													echo "<td>".$ctacklingData['passsnaps']."</td>";
													echo "<td>".$ctacklingData['tackles']."</td>";
													echo "<td>".$ctacklingData['miss_tck']."</td>";
													echo "<td>".$ctacklingData['sack']."</td>";
													echo "<td>".$ctacklingData['sack_yds']."</td>";
													echo "<td>".$ctacklingData['tfl']."</td>";
													echo "<td>".$ctacklingData['ff']."</td>";
													echo "<td>".$ctacklingData['f_yds']."</td>";
													echo "<td>".$ctacklingData['f_td']."</td>";
													echo "</tr>";
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div><div class="tab-pane statspane" id="c_pdefense">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th>Run Snaps</th>
										<th>Pass Snaps</th>
										<th>Targets</th>
										<th>Rec Allowed</th>
										<th>Yds Allowed</th>
										<th>TD Allowed</th>
										<th>Pass Def</th>
										<th>Int</th>
										<th>Int Yds</th>
										<th>Int TD</th>
										<th>Rating Against</th>
									</thead>
									<tbody>
										<?php
											if ($hasstats) {
												$cpdefense_result = mysqli_query($conn,"SELECT year,team,abbrev,games,runsnaps,passsnaps,pass_tgt,rec_alwd,recyds_alwd,td_alwd,pdef,`int`,int_yds,int_td FROM `stats` WHERE player=$playerid ORDER BY year DESC");
												while ($cpdefenseData = mysqli_fetch_array($cpdefense_result)) {
													
													echo "<tr>";
													echo "<td>".$cpdefenseData['year']."</td>";
													echo "<td><a href='team.php?teamid=".$cpdefenseData['team']."'>".$cpdefenseData['abbrev']."</td>";
													echo "<td>".$cpdefenseData['games']."</td>";
													echo "<td>".$cpdefenseData['runsnaps']."</td>";
													echo "<td>".$cpdefenseData['passsnaps']."</td>";
													echo "<td>".$cpdefenseData['pass_tgt']."</td>";
													echo "<td>".$cpdefenseData['rec_alwd']."</td>";
													echo "<td>".$cpdefenseData['recyds_alwd']."</td>";
													echo "<td>".$cpdefenseData['td_alwd']."</td>";
													echo "<td>".$cpdefenseData['pdef']."</td>";
													echo "<td>".$cpdefenseData['int']."</td>";
													echo "<td>".$cpdefenseData['int_yds']."</td>";
													echo "<td>".$cpdefenseData['int_td']."</td>";
													$ratingagainst = round(passerRating($cpdefenseData['pass_tgt'],$cpdefenseData['rec_alwd'],$cpdefenseData['recyds_alwd'],$cpdefenseData['td_alwd'],$cpdefenseData['int']),1);
													echo "<td>".$ratingagainst."</td>";
													echo "</tr>";
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_kicking">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th>XP Made</th>
										<th>XP Att</th>
										<th>XP %</th>
										<th>FG Made</th>
										<th>FG Att</th>
										<th>FG %</th>
										<th>Long</th>
										<th>Pnts</th>
									</thead>
									<tbody>
										<?php
											if ($hasstats) {
												$ckicking_result = mysqli_query($conn,"SELECT year,team,abbrev,games,xpa,xpm,fga,fgm,fg_long,fg_pnts FROM `stats` WHERE player=$playerid ORDER BY year DESC");
												while ($ckickingData = mysqli_fetch_array($ckicking_result)) {
													
													echo "<tr>";
													echo "<td>".$ckickingData['year']."</td>";
													echo "<td><a href='team.php?teamid=".$ckickingData['team']."'>".$ckickingData['abbrev']."</td>";
													echo "<td>".$ckickingData['games']."</td>";
													echo "<td>".$ckickingData['xpm']."</td>";
													echo "<td>".$ckickingData['xpa']."</td>";
													$xppercent = round($ckickingData['xpm']/$ckickingData['xpa']*100,1);
													echo "<td>".$xppercent."</td>";
													echo "<td>".$ckickingData['fgm']."</td>";
													echo "<td>".$ckickingData['fga']."</td>";
													$fgpercent = round($ckickingData['fgm']/$ckickingData['fga']*100,1);
													echo "<td>".$fgpercent."</td>";
													echo "<td>".$ckickingData['fg_long']."</td>";
													echo "<td>".$ckickingData['fg_pnts']."</td>";
													echo "</tr>";
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_punting">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th>Punts</th>
										<th>Yds</th>
										<th>Net Yds</th>
										<th>Yds/Punt</th>
										<th>Net Yds/Punt</th>
										<th>Long</th>
										<th>In 20</th>
										<th>Touchbacks</th>
									</thead>
									<tbody>
										<?php
											if ($hasstats) {
												$cpunting_result = mysqli_query($conn,"SELECT year,team,abbrev,games,punts,punt_yds,punt_net,punt_long,punt_in20,punt_tb FROM `stats` WHERE player=$playerid ORDER BY year DESC");
												while ($cpuntingData = mysqli_fetch_array($cpunting_result)) {
													
													echo "<tr>";
													echo "<td>".$cpuntingData['year']."</td>";
													echo "<td><a href='team.php?teamid=".$cpuntingData['team']."'>".$cpuntingData['abbrev']."</td>";
													echo "<td>".$cpuntingData['games']."</td>";
													echo "<td>".$cpuntingData['punts']."</td>";
													echo "<td>".$cpuntingData['punt_yds']."</td>";
													echo "<td>".$cpuntingData['punt_net']."</td>";
													$ydsperpunt = round($cpuntingData['punt_yds']/$cpuntingData['punts'],1);
													$netydsperpunt = round($cpuntingData['punt_net']/$cpuntingData['punts'],1);
													echo "<td>".$ydsperpunt."</td>";
													echo "<td>".$netydsperpunt."</td>";
													echo "<td>".$cpuntingData['punt_long']."</td>";
													echo "<td>".$cpuntingData['punt_in20']."</td>";
													echo "<td>".$cpuntingData['punt_tb']."</td>";
													echo "</tr>";
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="c_returning">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th>Kick Ret</th>
										<th>KR Yds</th>
										<th>Yds/KR</th>
										<th>KR Long</th>
										<th>KR TD</th>
										<th>Punt Ret</th>
										<th>PR Yds</th>
										<th>Yds/PR</th>
										<th>PR Long</th>
										<th>PR TD</th>
									</thead>
									<tbody>
										<?php
											if ($hasstats) {
												$creturning_result = mysqli_query($conn,"SELECT year,team,abbrev,games,kret,kret_yds,kret_long,kret_td,pret,pret_yds,pret_long,pret_td FROM `stats` WHERE player=$playerid ORDER BY year DESC");
												while ($creturningData = mysqli_fetch_array($creturning_result)) {
													
													echo "<tr>";
													echo "<td>".$creturningData['year']."</td>";
													echo "<td><a href='team.php?teamid=".$creturningData['team']."'>".$creturningData['abbrev']."</td>";
													echo "<td>".$creturningData['games']."</td>";
													echo "<td>".$creturningData['kret']."</td>";
													echo "<td>".$creturningData['kret_yds']."</td>";
													$ydsperkr = round($creturningData['kret_yds']/$creturningData['kret'],1);
													echo "<td>".$ydsperkr."</td>";
													echo "<td>".$creturningData['kret_long'];
													echo "<td>".$creturningData['kret_td'];
													echo "<td>".$creturningData['pret']."</td>";
													echo "<td>".$creturningData['pret_yds']."</td>";
													$ydsperpr = round($creturningData['pret_yds']/$creturningData['pret'],1);
													echo "<td>".$ydsperpr."</td>";
													echo "<td>".$creturningData['pret_long'];
													echo "<td>".$creturningData['pret_td'];
													echo "</tr>";
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
                <div class="tab-pane fade" id="gamelogs">
					<ul class="nav nav-tabs categories" role="tablist">
						<li class="active">
						  <a href="#gl_passing" role="tab" data-toggle="tab">Passing</a>
						</li>
						<li>
						  <a href="#gl_rushing" role="tab" data-toggle="tab">Rushing</a>
						</li>
						<li>
						  <a href="#gl_receiving" role="tab" data-toggle="tab">Receiving</a>
						</li>
						<li>
						  <a href="#gl_blocking" role="tab" data-toggle="tab">Blocking</a>
						</li>
						<li>
						  <a href="#gl_tackling" role="tab" data-toggle="tab">Tackling</a>
						</li>
						<li>
						  <a href="#gl_pdefense" role="tab" data-toggle="tab">Pass Defense</a>
						</li>
						<li>
						  <a href="#gl_kicking" role="tab" data-toggle="tab">Kicking</a>
						</li>
						<li>
						  <a href="#gl_punting" role="tab" data-toggle="tab">Punting</a>
						</li>
						<li>
						  <a href="#gl_returning" role="tab" data-toggle="tab">Returning</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane statspane active" id="gl_passing">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th>Comp</th>
										<th>Att</th>
										<th>Comp %</th>
										<th>Yds</th>
										<th>Yds/Att</th>
										<th>Long</th>
										<th>TD</th>
										<th>Int</th>
										<th>Sck</th>
										<th>Sck Yd</th>
										<th>Rate</th>
										<th>ANYA</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_rushing">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th width="5%">Year</th>
										<th width="5%">Team</th>
										<th width="5%">Games</th>
										<th width="5%">Snaps</th>
										<th>Att</th>
										<th>Yds</th>
										<th>Yds/Att</th>
										<th>Long</th>
										<th>TD</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_receiving">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Snaps</th>
										<th>Rec</th>
										<th>Tgts</th>
										<th>Yds</th>
										<th>YAC</th>
										<th>Yds/Rec</th>
										<th>YAC/Rec</th>
										<th>Long</th>
										<th>TD</th>
										<th>Fum</th>
										<th>Fum Lost</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_blocking">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Snaps</th>
										<th>Sacks Allowed</th>
										<th>Rush Yds</th>
										<th>Rush Att</th>
										<th>Rush YPC</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_tackling">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Snaps</th>
										<th>Tackles</th>
										<th>Missed Tackles</th>
										<th>Sacks</th>
										<th>Sack Yds</th>
										<th>Tck for Loss</th>
										<th>Forced Fum</th>
										<th>Fum Yds</th>
										<th>Fum TD</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div><div class="tab-pane statspane" id="gl_pdefense">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Snaps</th>
										<th>Targets</th>
										<th>Rec Allowed</th>
										<th>TD Allowed</th>
										<th>Pass Def</th>
										<th>Int</th>
										<th>Int Yds</th>
										<th>Int TD</th>
										<th>Rating Against</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_kicking">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>XP Made</th>
										<th>XP Att</th>
										<th>XP %</th>
										<th>FG Made</th>
										<th>FG Att</th>
										<th>FG %</th>
										<th>Long</th>
										<th>Pnts</th>
										<th>Kickoffs</th>
										<th>Kickoff Yds</th>
										<th>Yds/KO</th>
										<th>Touchbacks</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_punting">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Punts</th>
										<th>Yds</th>
										<th>Net Yds</th>
										<th>Yds/Punt</th>
										<th>Net Yds/Punt</th>
										<th>Long</th>
										<th>In 20</th>
										<th>Touchbacks</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane statspane" id="gl_returning">
							<div class="table-responsive">
								<table class="table ">
									<thead>
										<th>Year</th>
										<th>Team</th>
										<th>Games</th>
										<th>Kick Ret</th>
										<th>KR Yds</th>
										<th>Yds/KR</th>
										<th>KR Long</th>
										<th>KR TD</th>
										<th>Punt Ret</th>
										<th>PR Yds</th>
										<th>Yds/PR</th>
										<th>PR Long</th>
										<th>PR TD</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
                <div class="tab-pane fade" id="atts">
                    <div class="row">
                      <div class="col-md-9">
                        <div class="attributes">
						<?php
						$showarray = [];
						switch($player_position) {
							case "QB":
								$showarray = ["elusive","fw","read","pocket","throw_pow","throw_acc","clutch","leadership","durability","toughness"];
								break;
							case "RB":
								$showarray = ["strength","speed","burst","carrying","hands","elusive","read","pass_block","durability","toughness"];
								break;
							case "FB":
								$showarray = ["strength","speed","burst","carrying","hands","read","pass_block","run_block","durability","toughness"];
								break;
							case "WR":
								$showarray = ["strength","speed","burst","carrying","hands","elusive","route","jump","durability","toughness"];
								break;
							case "TE":
								$showarray = ["strength","speed","burst","carrying","hands","elusive","read","pass_block","run_block","route","jump","durability","toughness"];
								break;
							case "T":
								$showarray = ["strength","burst","fw","read","pass_block","run_block","durability","toughness"];
								break;
							case "G":
								$showarray = ["strength","burst","fw","read","pass_block","run_block","durability","toughness"];
								break;
							case "C":
								$showarray = ["strength","burst","fw","read","pass_block","run_block","leadership","durability","toughness"];
								break;
							case "DE":
								$showarray = ["strength","speed","burst","fw","read","block_shed","tackling","durability","toughness"];
								break;
							case "DT":
								$showarray = ["strength","speed","burst","fw","read","block_shed","tackling","durability","toughness"];
								break;
							case "LB":
								$showarray = ["strength","speed","burst","read","block_shed","coverage","tackling","leadership","durability","toughness"];
								break;
							case "CB":
								$showarray = ["strength","speed","burst","hands","read","jump","block_shed","coverage","tackling","durability","toughness"];
								break;
							case "S":
								$showarray = ["strength","speed","read","block_shed","coverage","tackling","leadership","durability","toughness"];
								break;
							case "K":
								$showarray = ["kick_pow","kick_acc","clutch"];
								break;
							case "P":
								$showarray = ["hands","kick_pow","punt_acc"];
								break;
						}
						?>
							<table class="table">
							<?php
								$last_year = $league_year - 1;
								$progression_result = mysqli_query($conn,"SELECT * FROM attributes WHERE player=$playerid AND year=$last_year");
								if (mysqli_num_rows($progression_result)!=0) {
									$progressionData = mysqli_fetch_array($progression_result);
									$progress = true;
								} else {
									$progress = false;
								}
							?>
									<thead>
									<tr>
										<?php
										$overall_now = $playerData['overall_now'];
										if ($progress) {
											$overall_then = $progressionData['overall_now'];
											$overall_growth = $overall_now-$overall_then;
										}
										echo "<th width='30%'>Rating</td>
										<th>".$overall_now."</td>
										<th>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$overall_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$overall_then."%;'>".$overall_then."</div>";
											if ($overall_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$overall_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$overall_growth."%'>+".$overall_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$overall_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$overall_now."%;'>".$overall_now."</div>";	
										}
										?>
									</tr>
									</thead>
									<tbody>
									<tr>
										<?php
										if (in_array("strength",$showarray)) {
											$strength_now = $playerData['strength_now'];
											if ($progress) {
												$strength_then = $progressionData['strength_now'];
												$strength_growth = $strength_now-$strength_then;
											}
											echo "<td>Strength</td>
											<td>".$strength_now."</td>
											<td>";
											if ($progress) { 
												echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$strength_then."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$strength_then."%;'>".$strength_then."</div>";
												if ($strength_growth > 0) {
													echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$strength_growth."'
													aria-valuemin='0' aria-valuemax='100' style='width: ".$strength_growth."%'>+".$strength_growth."</div></td>";
												}
											} else {
												echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$strength_now."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$strength_now."%;'>".$strength_now."</div>";	
											}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("speed",$showarray)) {
										$speed_now = $playerData['speed_now'];
										if ($progress) {
											$speed_then = $progressionData['speed_now'];
											$speed_growth = $speed_now-$speed_then;
										}
										echo "<td>Speed</td>
										<td>".$speed_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$speed_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$speed_then."%;'>".$speed_then."</div>";
											if ($speed_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$speed_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$speed_growth."%'>+".$speed_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$speed_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$speed_now."%;'>".$speed_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("burst",$showarray)) {
										$burst_now = $playerData['burst_now'];
										if ($progress) {
											$burst_then = $progressionData['burst_now'];
											$burst_growth = $burst_now-$burst_then;
										}
										echo "<td width='15%'>Burst</td>
										<td>".$burst_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$burst_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$burst_then."%;'>".$burst_then."</div>";
											if ($burst_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$burst_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$burst_growth."%'>+".$burst_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$burst_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$burst_now."%;'>".$burst_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("carry",$showarray)) {
										$carry_now = $playerData['carry_now'];
										if ($progress) {
											$carry_then = $progressionData['carry_now'];
											$carry_growth = $carry_now-$carry_then;
										}
										echo "<td>Ball Carrying</td>
										<td>".$carry_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$carry_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$carry_then."%;'>".$carry_then."</div>";
											if ($carry_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$carry_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$carry_growth."%'>+".$carry_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$carry_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$carry_now."%;'>".$carry_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("hands",$showarray)) {
										$hands_now = $playerData['hands_now'];
										if ($progress) {
											$hands_then = $progressionData['hands_now'];
											$hands_growth = $hands_now-$hands_then;
										}
										echo "<td>Hands</td>
										<td>".$hands_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$hands_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$hands_then."%;'>".$hands_then."</div>";
											if ($hands_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$hands_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$hands_growth."%'>+".$hands_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$hands_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$hands_now."%;'>".$hands_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("elusive",$showarray)) {
										$elusive_now = $playerData['elusive_now'];
										if ($progress) {
											$elusive_then = $progressionData['elusive_now'];
											$elusive_growth = $elusive_now-$elusive_then;
										}
										echo "<td>Elusiveness</td>
										<td>".$elusive_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$elusive_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$elusive_then."%;'>".$elusive_then."</div>";
											if ($elusive_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$elusive_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$elusive_growth."%'>+".$elusive_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$elusive_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$elusive_now."%;'>".$elusive_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("fw",$showarray)) {
										$fw_now = $playerData['fw_now'];
										if ($progress) {
											$fw_then = $progressionData['fw_now'];
											$fw_growth = $fw_now-$fw_then;
										}
										echo "<td>Footwork</td>
										<td>".$fw_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$fw_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$fw_then."%;'>".$fw_then."</div>";
											if ($fw_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$fw_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$fw_growth."%'>+".$fw_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$fw_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$fw_now."%;'>".$fw_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("read",$showarray)) {
										$read_now = $playerData['read_now'];
										if ($progress) {
											$read_then = $progressionData['read_now'];
											$read_growth = $read_now-$read_then;
										}
										echo "<td>Read Opposition</td>
										<td>".$read_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$read_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$read_then."%;'>".$read_then."</div>";
											if ($read_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$read_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$read_growth."%'>+".$read_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$read_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$read_now."%;'>".$read_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("pocket",$showarray)) {
										$pocket_now = $playerData['pocket_now'];
										if ($progress) {
											$pocket_then = $progressionData['pocket_now'];
											$pocket_growth = $pocket_now-$pocket_then;
										}
										echo "<td>Pocket Presence</td>
										<td>".$pocket_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pocket_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$pocket_then."%;'>".$pocket_then."</div>";
											if ($pocket_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$pocket_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$pocket_growth."%'>+".$pocket_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pocket_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$pocket_now."%;'>".$pocket_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("throw_pow",$showarray)) {
										$throw_pow_now = $playerData['throw_pow_now'];
										if ($progress) {
											$throw_pow_then = $progressionData['throw_pow_now'];
											$throw_pow_growth = $throw_pow_now-$throw_pow_then;
										}
										echo "<td>Throwing Power</td>
										<td>".$throw_pow_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_pow_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$throw_pow_then."%;'>".$throw_pow_then."</div>";
											if ($throw_pow_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$throw_pow_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$throw_pow_growth."%'>+".$throw_pow_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_pow_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$throw_pow_now."%;'>".$throw_pow_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("throw_acc",$showarray)) {
										$throw_acc_now = $playerData['throw_acc_now'];
										if ($progress) {
											$throw_acc_then = $progressionData['throw_acc_now'];
											$throw_acc_growth = $throw_acc_now-$throw_acc_then;
										}
										echo "<td>Throwing Accuracy</td>
										<td>".$throw_acc_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_acc_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$throw_acc_then."%;'>".$throw_acc_then."</div>";
											if ($throw_acc_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$throw_acc_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$throw_acc_growth."%'>+".$throw_acc_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_acc_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$throw_acc_now."%;'>".$throw_acc_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("pass_block",$showarray)) {
										$pass_block_now = $playerData['pass_block_now'];
										if ($progress) {
											$pass_block_then = $progressionData['pass_block_now'];
											$pass_block_growth = $pass_block_now-$pass_block_then;
										}
										echo "<td>Pass Blocking</td>
										<td>".$pass_block_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pass_block_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$pass_block_then."%;'>".$pass_block_then."</div>";
											if ($pass_block_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$pass_block_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$pass_block_growth."%'>+".$pass_block_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pass_block_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$pass_block_now."%;'>".$pass_block_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("run_block",$showarray)) {
										$run_block_now = $playerData['run_block_now'];
										if ($progress) {
											$run_block_then = $progressionData['run_block_now'];
											$run_block_growth = $run_block_now-$run_block_then;
										}
										echo "<td>Run Blocking</td>
										<td>".$run_block_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$run_block_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$run_block_then."%;'>".$run_block_then."</div>";
											if ($run_block_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$run_block_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$run_block_growth."%'>+".$run_block_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$run_block_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$run_block_now."%;'>".$run_block_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("route",$showarray)) {
										$route_now = $playerData['route_now'];
										if ($progress) {
											$route_then = $progressionData['route_now'];
											$route_growth = $route_now-$route_then;
										}
										echo "<td>Route Running</td>
										<td>".$route_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$route_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$route_then."%;'>".$route_then."</div>";
											if ($route_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$route_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$route_growth."%'>+".$route_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$route_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$route_now."%;'>".$route_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("jump",$showarray)) {
										$jump_now = $playerData['jump_now'];
										if ($progress) {
											$jump_then = $progressionData['jump_now'];
											$jump_growth = $jump_now-$jump_then;
										}
										echo "<td>Jumping</td>
										<td>".$jump_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$jump_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$jump_then."%;'>".$jump_then."</div>";
											if ($jump_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$jump_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$jump_growth."%'>+".$jump_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$jump_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$jump_now."%;'>".$jump_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("block_shed",$showarray)) {
										$block_shed_now = $playerData['block_shed_now'];
										if ($progress) {
											$block_shed_then = $progressionData['block_shed_now'];
											$block_shed_growth = $block_shed_now-$block_shed_then;
										}
										echo "<td>Block Shedding</td>
										<td>".$block_shed_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$block_shed_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$block_shed_then."%;'>".$block_shed_then."</div>";
											if ($block_shed_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$block_shed_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$block_shed_growth."%'>+".$block_shed_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$block_shed_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$block_shed_now."%;'>".$block_shed_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("coverage",$showarray)) {
										$coverage_now = $playerData['coverage_now'];
										if ($progress) {
											$coverage_then = $progressionData['coverage_now'];
											$coverage_growth = $coverage_now-$coverage_then;
										}
										echo "<td>Pass Coverage</td>
										<td>".$coverage_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$coverage_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$coverage_then."%;'>".$coverage_then."</div>";
											if ($coverage_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$coverage_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$coverage_growth."%'>+".$coverage_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$coverage_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$coverage_now."%;'>".$coverage_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("tackling",$showarray)) {
										$tackling_now = $playerData['tackling_now'];
										if ($progress) {
											$tackling_then = $progressionData['tackling_now'];
											$tackling_growth = $tackling_now-$tackling_then;
										}
										echo "<td>Tackling</td>
										<td>".$tackling_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$tackling_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$tackling_then."%;'>".$tackling_then."</div>";
											if ($tackling_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$tackling_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$tackling_growth."%'>+".$tackling_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$tackling_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$tackling_now."%;'>".$tackling_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("kick_pow",$showarray)) {
										$kick_pow_now = $playerData['kick_pow_now'];
										if ($progress) {
											$kick_pow_then = $progressionData['kick_pow_now'];
											$kick_pow_growth = $kick_pow_now-$kick_pow_then;
										}
										echo "<td>Kicking Power</td>
										<td>".$kick_pow_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_pow_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$kick_pow_then."%;'>".$kick_pow_then."</div>";
											if ($kick_pow_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$kick_pow_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$kick_pow_growth."%'>+".$kick_pow_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_pow_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$kick_pow_now."%;'>".$kick_pow_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("kick_acc",$showarray)) {
										$kick_acc_now = $playerData['kick_acc_now'];
										if ($progress) {
											$kick_acc_then = $progressionData['kick_acc_now'];
											$kick_acc_growth = $kick_acc_now-$kick_acc_then;
										}
										echo "<td>Kicking Accuracy</td>
										<td>".$kick_acc_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_acc_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$kick_acc_then."%;'>".$kick_acc_then."</div>";
											if ($kick_acc_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$kick_acc_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$kick_acc_growth."%'>+".$kick_acc_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_acc_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$kick_acc_now."%;'>".$kick_acc_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("punt_acc",$showarray)) {
										$punt_acc_now = $playerData['punt_acc_now'];
										if ($progress) {
											$punt_acc_then = $progressionData['punt_acc_now'];
											$punt_acc_growth = $punt_acc_now-$punt_acc_then;
										}
										echo "<td>Punting Accuracy</td>
										<td>".$punt_acc_now."</td>
										<td>";
										if ($progress) { 
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$punt_acc_then."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$punt_acc_then."%;'>".$punt_acc_then."</div>";
											if ($punt_acc_growth > 0) {
												echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$punt_acc_growth."'
												aria-valuemin='0' aria-valuemax='100' style='width: ".$punt_acc_growth."%'>+".$punt_acc_growth."</div></td>";
											}
										} else {
											echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$punt_acc_now."' aria-valuemin='0'
											aria-valuemax='100' style='width: ".$punt_acc_now."%;'>".$punt_acc_now."</div>";	
										}
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("clutch",$showarray)) {
										$clutch = $playerData['clutch'];
										echo "<td>Clutch</td>
										<td>".$clutch."</td>
										<td>";
										
										echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$clutch."' aria-valuemin='0'
										aria-valuemax='100' style='width: ".$clutch."%;'>".$clutch."</div>";	
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("leadership",$showarray)) {
										$leadership = $playerData['leadership'];
										echo "<td>Leadership</td>
										<td>".$leadership."</td>
										<td>";
										
										echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$leadership."' aria-valuemin='0'
										aria-valuemax='100' style='width: ".$leadership."%;'>".$leadership."</div>";	
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("durability",$showarray)) {
										$durability = $playerData['durability'];
										echo "<td>Durability</td>
										<td>".$durability."</td>
										<td>";
										
										echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$durability."' aria-valuemin='0'
										aria-valuemax='100' style='width: ".$durability."%;'>".$durability."</div>";	
										}
										?>
									</tr>
									<tr>
										<?php
										if (in_array("toughness",$showarray)) {
										$toughness = $playerData['toughness'];
										echo "<td>Toughness</td>
										<td>".$toughness."</td>
										<td>";
										
										echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$toughness."' aria-valuemin='0'
										aria-valuemax='100' style='width: ".$toughness."%;'>".$toughness."</div>";	
										}
										?>
									</tr>
								</tbody>
							</table>
							<div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#allatts">
									  Click to view all attributes
									</a>
								  </h4>
								</div>
								<div id="allatts" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
											<thead>
											<tr>
												<?php
												$overall_now = $playerData['overall_now'];
												if ($progress) {
													$overall_then = $progressionData['overall_now'];
													$overall_growth = $overall_now-$overall_then;
												}
												echo "<th width='30%'>Overall</td>
												<th>".$overall_now."</td>
												<th>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$overall_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$overall_then."%;'>".$overall_then."</div>";
													if ($overall_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$overall_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$overall_growth."%'>+".$overall_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$overall_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$overall_now."%;'>".$overall_now."</div>";	
												}
												?>
											</tr>
											</thead>
											<tbody>
											<tr>
												<?php
												$strength_now = $playerData['strength_now'];
												if ($progress) {
													$strength_then = $progressionData['strength_now'];
													$strength_growth = $strength_now-$strength_then;
												}
												echo "<td>Strength</td>
												<td>".$strength_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$strength_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$strength_then."%;'>".$strength_then."</div>";
													if ($strength_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$strength_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$strength_growth."%'>+".$strength_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$strength_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$strength_now."%;'>".$strength_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$speed_now = $playerData['speed_now'];
												if ($progress) {
													$speed_then = $progressionData['speed_now'];
													$speed_growth = $speed_now-$speed_then;
												}
												echo "<td>Speed</td>
												<td>".$speed_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$speed_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$speed_then."%;'>".$speed_then."</div>";
													if ($speed_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$speed_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$speed_growth."%'>+".$speed_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$speed_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$speed_now."%;'>".$speed_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$burst_now = $playerData['burst_now'];
												if ($progress) {
													$burst_then = $progressionData['burst_now'];
													$burst_growth = $burst_now-$burst_then;
												}
												echo "<td width='15%'>Burst</td>
												<td>".$burst_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$burst_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$burst_then."%;'>".$burst_then."</div>";
													if ($burst_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$burst_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$burst_growth."%'>+".$burst_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$burst_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$burst_now."%;'>".$burst_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$carry_now = $playerData['carry_now'];
												if ($progress) {
													$carry_then = $progressionData['carry_now'];
													$carry_growth = $carry_now-$carry_then;
												}
												echo "<td>Ball Carrying</td>
												<td>".$carry_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$carry_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$carry_then."%;'>".$carry_then."</div>";
													if ($carry_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$carry_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$carry_growth."%'>+".$carry_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$carry_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$carry_now."%;'>".$carry_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$hands_now = $playerData['hands_now'];
												if ($progress) {
													$hands_then = $progressionData['hands_now'];
													$hands_growth = $hands_now-$hands_then;
												}
												echo "<td>Hands</td>
												<td>".$hands_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$hands_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$hands_then."%;'>".$hands_then."</div>";
													if ($hands_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$hands_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$hands_growth."%'>+".$hands_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$hands_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$hands_now."%;'>".$hands_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$elusive_now = $playerData['elusive_now'];
												if ($progress) {
													$elusive_then = $progressionData['elusive_now'];
													$elusive_growth = $elusive_now-$elusive_then;
												}
												echo "<td>Elusiveness</td>
												<td>".$elusive_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$elusive_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$elusive_then."%;'>".$elusive_then."</div>";
													if ($elusive_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$elusive_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$elusive_growth."%'>+".$elusive_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$elusive_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$elusive_now."%;'>".$elusive_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$fw_now = $playerData['fw_now'];
												if ($progress) {
													$fw_then = $progressionData['fw_now'];
													$fw_growth = $fw_now-$fw_then;
												}
												echo "<td>Footwork</td>
												<td>".$fw_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$fw_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$fw_then."%;'>".$fw_then."</div>";
													if ($fw_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$fw_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$fw_growth."%'>+".$fw_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$fw_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$fw_now."%;'>".$fw_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$read_now = $playerData['read_now'];
												if ($progress) {
													$read_then = $progressionData['read_now'];
													$read_growth = $read_now-$read_then;
												}
												echo "<td>Read Opposition</td>
												<td>".$read_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$read_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$read_then."%;'>".$read_then."</div>";
													if ($read_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$read_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$read_growth."%'>+".$read_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$read_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$read_now."%;'>".$read_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$pocket_now = $playerData['pocket_now'];
												if ($progress) {
													$pocket_then = $progressionData['pocket_now'];
													$pocket_growth = $pocket_now-$pocket_then;
												}
												echo "<td>Pocket Presence</td>
												<td>".$pocket_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pocket_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$pocket_then."%;'>".$pocket_then."</div>";
													if ($pocket_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$pocket_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$pocket_growth."%'>+".$pocket_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pocket_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$pocket_now."%;'>".$pocket_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$throw_pow_now = $playerData['throw_pow_now'];
												if ($progress) {
													$throw_pow_then = $progressionData['throw_pow_now'];
													$throw_pow_growth = $throw_pow_now-$throw_pow_then;
												}
												echo "<td>Throwing Power</td>
												<td>".$throw_pow_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_pow_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$throw_pow_then."%;'>".$throw_pow_then."</div>";
													if ($throw_pow_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$throw_pow_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$throw_pow_growth."%'>+".$throw_pow_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_pow_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$throw_pow_now."%;'>".$throw_pow_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$throw_acc_now = $playerData['throw_acc_now'];
												if ($progress) {
													$throw_acc_then = $progressionData['throw_acc_now'];
													$throw_acc_growth = $throw_acc_now-$throw_acc_then;
												}
												echo "<td>Throwing Accuracy</td>
												<td>".$throw_acc_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_acc_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$throw_acc_then."%;'>".$throw_acc_then."</div>";
													if ($throw_acc_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$throw_acc_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$throw_acc_growth."%'>+".$throw_acc_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$throw_acc_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$throw_acc_now."%;'>".$throw_acc_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$pass_block_now = $playerData['pass_block_now'];
												if ($progress) {
													$pass_block_then = $progressionData['pass_block_now'];
													$pass_block_growth = $pass_block_now-$pass_block_then;
												}
												echo "<td>Pass Blocking</td>
												<td>".$pass_block_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pass_block_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$pass_block_then."%;'>".$pass_block_then."</div>";
													if ($pass_block_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$pass_block_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$pass_block_growth."%'>+".$pass_block_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$pass_block_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$pass_block_now."%;'>".$pass_block_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$run_block_now = $playerData['run_block_now'];
												if ($progress) {
													$run_block_then = $progressionData['run_block_now'];
													$run_block_growth = $run_block_now-$run_block_then;
												}
												echo "<td>Run Blocking</td>
												<td>".$run_block_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$run_block_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$run_block_then."%;'>".$run_block_then."</div>";
													if ($run_block_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$run_block_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$run_block_growth."%'>+".$run_block_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$run_block_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$run_block_now."%;'>".$run_block_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$route_now = $playerData['route_now'];
												if ($progress) {
													$route_then = $progressionData['route_now'];
													$route_growth = $route_now-$route_then;
												}
												echo "<td>Route Running</td>
												<td>".$route_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$route_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$route_then."%;'>".$route_then."</div>";
													if ($route_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$route_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$route_growth."%'>+".$route_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$route_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$route_now."%;'>".$route_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$jump_now = $playerData['jump_now'];
												if ($progress) {
													$jump_then = $progressionData['jump_now'];
													$jump_growth = $jump_now-$jump_then;
												}
												echo "<td>Jumping</td>
												<td>".$jump_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$jump_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$jump_then."%;'>".$jump_then."</div>";
													if ($jump_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$jump_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$jump_growth."%'>+".$jump_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$jump_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$jump_now."%;'>".$jump_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$block_shed_now = $playerData['block_shed_now'];
												if ($progress) {
													$block_shed_then = $progressionData['block_shed_now'];
													$block_shed_growth = $block_shed_now-$block_shed_then;
												}
												echo "<td>Block Shedding</td>
												<td>".$block_shed_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$block_shed_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$block_shed_then."%;'>".$block_shed_then."</div>";
													if ($block_shed_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$block_shed_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$block_shed_growth."%'>+".$block_shed_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$block_shed_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$block_shed_now."%;'>".$block_shed_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$coverage_now = $playerData['coverage_now'];
												if ($progress) {
													$coverage_then = $progressionData['coverage_now'];
													$coverage_growth = $coverage_now-$coverage_then;
												}
												echo "<td>Pass Coverage</td>
												<td>".$coverage_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$coverage_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$coverage_then."%;'>".$coverage_then."</div>";
													if ($coverage_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$coverage_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$coverage_growth."%'>+".$coverage_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$coverage_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$coverage_now."%;'>".$coverage_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$tackling_now = $playerData['tackling_now'];
												if ($progress) {
													$tackling_then = $progressionData['tackling_now'];
													$tackling_growth = $tackling_now-$tackling_then;
												}
												echo "<td>Tackling</td>
												<td>".$tackling_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$tackling_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$tackling_then."%;'>".$tackling_then."</div>";
													if ($tackling_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$tackling_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$tackling_growth."%'>+".$tackling_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$tackling_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$tackling_now."%;'>".$tackling_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$kick_pow_now = $playerData['kick_pow_now'];
												if ($progress) {
													$kick_pow_then = $progressionData['kick_pow_now'];
													$kick_pow_growth = $kick_pow_now-$kick_pow_then;
												}
												echo "<td>Kicking Power</td>
												<td>".$kick_pow_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_pow_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$kick_pow_then."%;'>".$kick_pow_then."</div>";
													if ($kick_pow_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$kick_pow_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$kick_pow_growth."%'>+".$kick_pow_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_pow_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$kick_pow_now."%;'>".$kick_pow_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$kick_acc_now = $playerData['kick_acc_now'];
												if ($progress) {
													$kick_acc_then = $progressionData['kick_acc_now'];
													$kick_acc_growth = $kick_acc_now-$kick_acc_then;
												}
												echo "<td>Kicking Accuracy</td>
												<td>".$kick_acc_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_acc_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$kick_acc_then."%;'>".$kick_acc_then."</div>";
													if ($kick_acc_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$kick_acc_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$kick_acc_growth."%'>+".$kick_acc_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$kick_acc_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$kick_acc_now."%;'>".$kick_acc_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$punt_acc_now = $playerData['punt_acc_now'];
												if ($progress) {
													$punt_acc_then = $progressionData['punt_acc_now'];
													$punt_acc_growth = $punt_acc_now-$punt_acc_then;
												}
												echo "<td>Punting Accuracy</td>
												<td>".$punt_acc_now."</td>
												<td>";
												if ($progress) { 
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$punt_acc_then."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$punt_acc_then."%;'>".$punt_acc_then."</div>";
													if ($punt_acc_growth > 0) {
														echo "<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$punt_acc_growth."'
														aria-valuemin='0' aria-valuemax='100' style='width: ".$punt_acc_growth."%'>+".$punt_acc_growth."</div></td>";
													}
												} else {
													echo "<div class='progress-bar' role='progressbar' aria-valuenow='".$punt_acc_now."' aria-valuemin='0'
													aria-valuemax='100' style='width: ".$punt_acc_now."%;'>".$punt_acc_now."</div>";	
												}
												?>
											</tr>
											<tr>
												<?php
												$clutch = $playerData['clutch'];
												echo "<td>Clutch</td>
												<td>".$clutch."</td>
												<td>";
												
												echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$clutch."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$clutch."%;'>".$clutch."</div>";	
												
												?>
											</tr>
											<tr>
												<?php
												$leadership = $playerData['leadership'];
												echo "<td>Leadership</td>
												<td>".$leadership."</td>
												<td>";
												
												echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$leadership."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$leadership."%;'>".$leadership."</div>";	
												
												?>
											</tr>
											<tr>
												<?php
												$durability = $playerData['durability'];
												echo "<td>Durability</td>
												<td>".$durability."</td>
												<td>";
												
												echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$durability."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$durability."%;'>".$durability."</div>";	
												
												?>
											</tr>
											<tr>
												<?php
												$toughness = $playerData['toughness'];
												echo "<td>Toughness</td>
												<td>".$toughness."</td>
												<td>";
												
												echo "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='".$toughness."' aria-valuemin='0'
												aria-valuemax='100' style='width: ".$toughness."%;'>".$toughness."</div>";	
												
												?>
											</tr>
										</tbody>
									</table>
									</div>
								</div>
							  </div>
                            
                        </div>
                      </div>
                    </div>
                </div>
				
              </div>
            </div>
				<?php
				$user_team_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$player_league AND owner=$userID");
				$userteam_rows = mysqli_num_rows($user_team_result);
				$userteamData = mysqli_fetch_array($user_team_result);
				$teamid = $userteamData['id'];
				
				$alreadybid_result = mysqli_query($conn,"SELECT * FROM bids WHERE player=$playerid AND team=$teamid");
				$bid_rows = mysqli_num_rows($alreadybid_result);
				
				if ($player_team != 0) {
					echo "<div class=\"panel-group contract\" id=\"accordion\">
				  <div class=\"panel panel-default\">
					<div class=\"panel-heading\">
					  <h4 class=\"panel-title\">
						<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#contractinfo\">
						  Click to view contract information
						</a>
					  </h4>
					</div>
					<div id=\"contractinfo\" class=\"panel-collapse collapse\">
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
				} else if ($userteam_rows == 1 && $bid_rows==0) {
					echo "<div class=\"well contract\">";
					echo "<h4>Send Contract Offer</h4><p><i>This is the minimum contract the player will accept. He will be more likely to accept your offer over other teams' if you offer a longer contract with more guaranteed money.</i></p>";
					echo "<form class=\"form-horizontal\" action=\"player.php?playerid=".$playerid."\" method=\"POST\" id=\"faoffer\" name=\"faoffer\" role=\"form\">
					<div class=\"row\">
					
						<div class=\"col-md-7\">
							<div class=\"form-group\">
								<label for=\"base\" class=\"col-md-5 control-label\">Base Contract Value: </label>
								<div class=\"col-md-6\">
									<div class=\"input-group\">
										<span class=\"input-group-addon\">$</span>
										<input type=\"number\" class=\"form-control\" id=\"base\" name=\"base\" />
										<span class=\"input-group-addon\">K</span>
									</div>
								</div>
							</div>
						</div>
						<div class=\"col-md-7\">
							<div class=\"form-group\">
								<label for=\"bonus\" class=\"col-md-5 control-label\">Guaranteed: </label>
								<div class=\"col-md-6\">
									<div class=\"input-group\">
										<span class=\"input-group-addon\">$</span>
										<input type=\"number\" class=\"form-control\" id=\"bonus\" name=\"bonus\"  />
										<span class=\"input-group-addon\">K</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-md-7\">
							<div class=\"col-md-5 control-label\">
								<b>Years: </b>
							</div>
							<div class=\"col-md-6\">
								<select class=\"form-control\" name=\"years\" id=\"years\">
									<option selected>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
								</select>
							</div>
						</div>
					</div>
					<div class=\"row\" id=\"salarytext\">
							<div class=\"col-md-3\">
								<b style=\"float: right;\">Offered contract: </b>
							</div>
							<div class=\"col-md-8\">
							<span id=\"saltext\">
							</span>
							<span id=\"bontext\">
							</span>
							<span id=\"tottext\">
							</span>
							</div>
					</div>
					<div class=\"row\" id=\"buttonrow\">
						<input type=\"hidden\" name=\"teamid\" value=\"".$teamid."\"></input>
							<div class=\"col-md-3 col-md-offset-7\">
							<button class=\"btn btn-primary\" type=\"submit\" id=\"offersubmit\" name=\"offersubmit\">Submit Offer</button>
							</div>
					</div>
					</form>";
				echo "<h4>Salary situation if signed:</h4><div class=\"table-responsive\" id=\"salarytable\">
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
					
				} else {
					$bidData = mysqli_fetch_array($alreadybid_result);
					if ($bidData['years'] == 1) {
						$year = "year";
					} else {
						$year = "years";
					}
					$totaloffer = $bidData['totalbonus'] + $bidData['totalbase'];
					echo "<div class=\"well contract\">
					<b>Offer submitted</b><p>".$bidData['years']." ".$year.", $".number_format($totaloffer)." total. ($"
					.number_format($bidData['totalbase'])." base, $".number_format($bidData['totalbonus'])." guaranteed)</p>";
					echo "<form method=\"POST\" action=\"player.php?playerid=".$playerid."\">
						<button class=\"btn btn-danger\" type=\"submit\" name=\"retract\" id=\"retract\">Retract Offer</button>
						<input type=\"hidden\" name=\"teamid\" value=\"".$teamid."\"></input>
						</form>";
						echo "<h4>Salary situation if signed:</h4><div class=\"table-responsive\" id=\"salarytable\">
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
								
								$bonusfield = "bonus_".($i+1);
								$basefield = "base_".($i+1);
								
								echo "<tr><td>".$contract_year."</td>
									<td id=\"salyear".$i."\" class=\"number\">$".number_format($bidData[$basefield])."</td>
									<td id=\"bonyear".$i."\" class=\"number\">$".number_format($bidData[$bonusfield])."</td>
									<td id=\"sumyear".$i."\" class=\"number\">$".number_format($bidData[$basefield]+$bidData[$bonusfield])."</td>
									<td id=\"totyear".$i."\" class=\"number\">$".number_format($total_salary)."</td>
									<td id=\"aftyear".$i."\" class=\"number\">$".number_format($bidData[$basefield]+$bidData[$bonusfield]+$total_salary)."</td>
									<td>$130,000,000</td></tr>";
							}
						echo "</tbody>
								</table>
							</div>";
				} 
				
				$bidteams_result = mysqli_query($conn,"SELECT team.id,team.location,team.teamname FROM `bids` JOIN team ON bids.team=team.id WHERE bids.player=$playerid");
				if (mysqli_num_rows($bidteams_result)>0) {
					echo "<h4>Teams in the Mix</h4>";
					while ($bidteamData = mysqli_fetch_array($bidteams_result)) {
						echo "<a href='team.php?teamid=".$bidteamData['id']."'>".$bidteamData['location']." ".$bidteamData['teamname']."</a><br>";
					}
				}
				echo "</div>";
				?>
						
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
