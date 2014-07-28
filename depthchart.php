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
	if (isset($_POST['savegen'])) {
		$QB_1 = $_POST['qb1select'];
		$QB_2 = $_POST['qb2select'];
		$QB_3 = $_POST['eqbselect'];
		$LT_1 = $_POST['lt1select'];
		$LT_2 = $_POST['lt2select'];
		$LG_1 = $_POST['lg1select'];
		$LG_2 = $_POST['lg2select'];
		$C_1 = $_POST['c1select'];
		$C_2 = $_POST['c2select'];
		$RG_1 = $_POST['rg1select'];
		$RG_2 = $_POST['rg2select'];
		$RT_1 = $_POST['rt1select'];
		$RT_2 = $_POST['rt2select'];
		$FB_1 = $_POST['fb1select'];
		$FB_2 = $_POST['fb2select'];
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$TE1_1 = $_POST['te1-1select'];
		$TE1_2 = $_POST['te1-2select'];
		$TE2_1 = $_POST['te2-1select'];
		$TE2_2 = $_POST['te2-2select'];
		$WR1_1 = $_POST['wr1-1select'];
		$WR1_2 = $_POST['wr1-2select'];
		$WR2_1 = $_POST['wr2-1select'];
		$WR2_2 = $_POST['wr2-2select'];
		$WR3_1 = $_POST['wr3-1select'];
		$WR3_2 = $_POST['wr3-2select'];
		$WR4_1 = $_POST['wr4-1select'];
		$WR4_2 = $_POST['wr4-2select'];
		$WR5_1 = $_POST['wr5-1select'];
		$WR5_2 = $_POST['wr5-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET QB_1=$QB_1,QB_2=$QB_2,QB_3=$QB_3,LT_1=$LT_1,LT_2=$LT_2,LG_1=$LG_1,LG_2=$LG_2,C_1=$C_1,C_2=$C_2,RG_1=$RG_1,RG_2=$RG_2,RT_1=$RT_1,RT_2=$RT_2,
		FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,TE2_1=$TE2_1,TE2_2=$TE2_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2,WR4_1=$WR4_1,WR4_2=$WR4_2,WR5_1=$WR5_1,WR5_2=$WR5_2 WHERE team=$teamid";
		mysqli_query($conn,$lineupquery);
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
    <link href="../css/depthchart.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/stacktable.js"></script>
	<script>
	$( document ).ready(function() {
		$('.table').stacktable();
		var value = $("#qb1select").val();
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
			
			$("#playername").html("<a href=\"player.php?playerid="+value+"\">"+name+"</a>");
			$("#playerposition").html(position);
			$("#playerhealth").html(health);
			$("#playerrating").html(rating);
			$("#height").html(height);
			$("#weight").html(weight);
		  },
		  error: function(xhr, desc, err) {
		  }
		  }); //end ajax 
		$('.playeropt').on('change click', function(e){
			e.preventDefault();
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
			
			$("#playername").html("<a href=\"player.php?playerid="+value+"\">"+name+"</a>");
			$("#playerposition").html(position);
			$("#playerhealth").html(health);
			$("#playerrating").html(rating);
			$("#height").html(height);
			$("#weight").html(weight);
		  },
		  error: function(xhr, desc, err) {
		  }
		  }); //end ajax 
		});
	});
	</script>
	
    <title>RedZone Rush - Depth Chart</title>
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
            <h3>Team Links</h3></div>
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
                </li><li class="active">
                  <a href="depthchart.php">Depth Chart</a>
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
        <div class="col-md-9 col-lg-8">
          <div class="main">
		  <div class="row">
			<div class="col-md-3 col-lg-2">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                      <li class="active dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Personnel <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li role="presentation">
								<a role="menuitem" href="#offense" data-toggle="tab">General Offense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#22" data-toggle="tab">22 Personnel - 1 HB, 1 FB, 2 TE, 1 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#21" data-toggle="tab">21 Personnel - 1 HB, 1 FB, 1 TE, 2 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#20" data-toggle="tab">20 Personnel - 1 HB, 1 FB, 3 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#12" data-toggle="tab">12 Personnel - 1 HB, 2 TE, 2 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#11" data-toggle="tab">11 Personnel - 1 HB, 1 TE, 3 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#10" data-toggle="tab">10 Personnel - 1 HB, 4 WR</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#00" data-toggle="tab">00 Personnel - 5 WR</a>
							</li>
							<li role="presentation" class="divider"></li>
							<li role="presentation">
								<a role="menuitem" href="#defense" data-toggle="tab">General Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#43" data-toggle="tab">4-3 Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#34" data-toggle="tab">3-4 Defense</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#eight" data-toggle="tab">Eight in the box</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#nickel" data-toggle="tab">Nickel</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#dime" data-toggle="tab">Dime</a>
							</li>
							<li role="presentation">
								<a role="menuitem" href="#goalline" data-toggle="tab">Goal Line</a>
							</li>
						</ul>
                      </li>
                    </ul>
                  </div>
				  <div class="col-lg-8 col-md-12">
				  <h3><?php echo $location." ".$teamname;?> Depth Chart</h3>
					<div class="tab-content" id="all-scores">
						<div class="tab-pane fade in active" id="offense">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="genoffense">
						<div class="col-md-9">
							This is the general offensive depth chart. Changes here will affect every offensive formation.
							<?php 
							$active_qb_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='QB' AND team=$teamid ORDER BY overall_now DESC");
							$active_rb_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='RB' AND team=$teamid ORDER BY overall_now DESC");
							$active_fb_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='FB' AND team=$teamid ORDER BY overall_now DESC");
							$active_wr_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='WR' AND team=$teamid ORDER BY overall_now DESC");
							$active_te_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='TE' AND team=$teamid ORDER BY overall_now DESC");
							$active_g_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='G' AND team=$teamid ORDER BY overall_now DESC");
							$active_c_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='C' AND team=$teamid ORDER BY overall_now DESC");
							$active_t_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='T' AND team=$teamid ORDER BY overall_now DESC");
							$active_de_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='DE' AND team=$teamid ORDER BY overall_now DESC");
							$active_dt_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='DT' AND team=$teamid ORDER BY overall_now DESC");
							$active_lb_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='LB' AND team=$teamid ORDER BY overall_now DESC");
							$active_cb_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='CB' AND team=$teamid ORDER BY overall_now DESC");
							$active_s_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='S' AND team=$teamid ORDER BY overall_now DESC");
							$active_k_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='K' AND team=$teamid ORDER BY overall_now DESC");
							$active_p_result = mysqli_query($conn,"SELECT * FROM player WHERE status='active' AND position='P' AND team=$teamid ORDER BY overall_now DESC");
							
							$qbArray = [];
							$hbArray = [];
							$fbArray = [];
							$wrArray = [];
							$teArray = [];
							$gArray = [];
							$cArray = [];
							$tArray = [];
							$deArray = [];
							$dtArray = [];
							$lbArray = [];
							$cbArray = [];
							$sArray = [];
							$kArray = [];
							$pArray = [];
							
							$lineup_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid");
							$lineup = mysqli_fetch_array($lineup_result);
							?>
							<div class="row well playerrow" id="qbrow">
								<h4>Quarterback</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Quarterback</td>
										<td><select class="form-control playeropt" name="qb1select" id="qb1select">
											<?php
												$currentqb1 = $lineup['QB_1'];
												$qb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentqb1");
												$qb1Data = mysqli_fetch_array($qb1_result);
												if (mysqli_num_rows($qb1_result)!=0) {
													echo "<option value=\"".$currentqb1."\">".$qb1Data['firstname']." ".$qb1Data['lastname']."</option>";
													$qb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
												}
												while($qbData = mysqli_fetch_array($active_qb_result)) {
													$playerid = $qbData['id'];
													$name = $qbData['firstname']." ".$qbData['lastname'];
													$qbArray[] = [$playerid,$name];
													if ($playerid!=$currentqb1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($qb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="qb2select">
											<?php
												$currentqb2 = $lineup['QB_2'];
												$qb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentqb2");
												$qb2Data = mysqli_fetch_array($qb2_result);
												if (mysqli_num_rows($qb2_result)!=0) {
													echo "<option value=\"".$currentqb2."\">".$qb2Data['firstname']." ".$qb2Data['lastname']."</option>";
													$qb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
												}
												foreach($qbArray as $playerarray) {
													if ($playerarray[0]!=$currentqb2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($qb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
									</tr>
									<tr>
										<td>Emergency Quarterback</td>
										<td><select class="form-control playeropt" name="eqbselect">
											<?php
												$currentqb3 = $lineup['QB_3'];
												$qb3_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentqb3");
												$qb3Data = mysqli_fetch_array($qb3_result);
												if (mysqli_num_rows($qb3_result)!=0) {
													echo "<option value=\"".$currentqb3."\">".$qb3Data['firstname']." ".$qb3Data['lastname']."</option>";
													$qb3set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($qbArray as $playerarray) {
													if ($playerarray[0]!=$currentqb3) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($qb3set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="olrow">
								<h4>Offensive Line</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Left Tackle</td>
										<td><select class="form-control playeropt" name="lt1select">
											<?php
												$currentlt1 = $lineup['LT_1'];
												$lt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentlt1");
												$lt1Data = mysqli_fetch_array($lt1_result);
												if (mysqli_num_rows($lt1_result)!=0) {
													echo "<option value=\"".$currentlt1."\">".$lt1Data['firstname']." ".$lt1Data['lastname']."</option>";
													$lt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
												}
												while($tData = mysqli_fetch_array($active_t_result)) {
													$playerid = $tData['id'];
													$name = $tData['firstname']." ".$tData['lastname'];
													$tArray[] = [$playerid,$name];
													if ($playerid!=$currentlt1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($lt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lt2select">
											<?php
												$currentlt2 = $lineup['LT_2'];
												$lt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentlt2");
												$lt2Data = mysqli_fetch_array($lt2_result);
												if (mysqli_num_rows($lt1_result)!=0) {
													echo "<option value=\"".$currentlt2."\">".$lt2Data['firstname']." ".$lt2Data['lastname']."</option>";
													$lt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
												}
												foreach($tArray as $playerarray) {
													if ($playerarray[0]!=$currentlt2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Guard</td>
										<td><select class="form-control playeropt" name="lg1select">
											<?php
												$currentlg1 = $lineup['LG_1'];
												$lg1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentlg1");
												$lg1Data = mysqli_fetch_array($lg1_result);
												if (mysqli_num_rows($lg1_result)!=0) {
													echo "<option value=\"".$currentlg1."\">".$lg1Data['firstname']." ".$lg1Data['lastname']."</option>";
													$lg1set = true;
												} else {
													 echo "<option value=\"0\" class=\"autooption\">Auto</option>";
												}
												while($gData = mysqli_fetch_array($active_g_result)) {
													$playerid = $gData['id'];
													$name = $gData['firstname']." ".$gData['lastname'];
													$gArray[] = [$playerid,$name];
													if ($playerid!=$currentlg1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($lg1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lg2select">
											<?php
												$currentlg2 = $lineup['LG_2'];
												$lg2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentlg2");
												$lg2Data = mysqli_fetch_array($lg2_result);
												if (mysqli_num_rows($lg1_result)!=0) {
													echo "<option value=\"".$currentlg2."\">".$lg2Data['firstname']." ".$lg2Data['lastname']."</option>";
													$lg2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($gArray as $playerarray) {
													if ($playerarray[0]!=$currentlg2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lg2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Center</td>
										<td><select class="form-control playeropt" name="c1select">
											<?php
												$currentc1 = $lineup['C_1'];
												$c1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentc1");
												$c1Data = mysqli_fetch_array($c1_result);
												if (mysqli_num_rows($c1_result)!=0) {
													echo "<option value=\"".$currentc1."\">".$c1Data['firstname']." ".$c1Data['lastname']."</option>";
													$c1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												while($cData = mysqli_fetch_array($active_c_result)) {
													$playerid = $cData['id'];
													$name = $cData['firstname']." ".$cData['lastname'];
													$cArray[] = [$playerid,$name];
													if ($playerid!=$currentc1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($c1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="c2select">
											<?php
												$currentc2 = $lineup['C_2'];
												$c2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentc2");
												$c2Data = mysqli_fetch_array($c2_result);
												if (mysqli_num_rows($c2_result)!=0) {
													echo "<option value=\"".$currentc2."\">".$c2Data['firstname']." ".$c2Data['lastname']."</option>";
													$c2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($cArray as $playerarray) {
													if ($playerarray[0]!=$currentc2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($c2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Guard</td>
										<td><select class="form-control playeropt" name="rg1select">
											<?php
												$currentrg1 = $lineup['RG_1'];
												$rg1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentrg1");
												$rg1Data = mysqli_fetch_array($rg1_result);
												if (mysqli_num_rows($rg1_result)!=0) {
													echo "<option value=\"".$currentrg1."\">".$rg1Data['firstname']." ".$rg1Data['lastname']."</option>";
													$rg1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($gArray as $playerarray) {
													if ($playerarray[0]!=$currentrg1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rg1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rg2select">
											<?php
												$currentrg2 = $lineup['RG_2'];
												$rg2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentrg2");
												$rg2Data = mysqli_fetch_array($rg2_result);
												if (mysqli_num_rows($rg2_result)!=0) {
													echo "<option value=\"".$currentrg2."\">".$rg2Data['firstname']." ".$rg2Data['lastname']."</option>";
													$rg2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($gArray as $playerarray) {
													if ($playerarray[0]!=$currentrg2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rg2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Tackle</td>
										<td><select class="form-control playeropt" name="rt1select">
											<?php
												$currentrt1 = $lineup['RT_1'];
												$rt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentrt1");
												$rt1Data = mysqli_fetch_array($rt1_result);
												if (mysqli_num_rows($rt1_result)!=0) {
													echo "<option value=\"".$currentrt1."\">".$rt1Data['firstname']." ".$rt1Data['lastname']."</option>";
													$rt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($tArray as $playerarray) {
													if ($playerarray[0]!=$currentrt1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rt2select">
											<?php
												$currentrt2 = $lineup['RT_2'];
												$rt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentrt2");
												$rt2Data = mysqli_fetch_array($rt2_result);
												if (mysqli_num_rows($rt2_result)!=0) {
													echo "<option value=\"".$currentrt2."\">".$rt2Data['firstname']." ".$rt2Data['lastname']."</option>";
													$rt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($tArray as $playerarray) {
													if ($playerarray[0]!=$currentrt2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												$currenthb1 = $lineup['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												$hb1Data = mysqli_fetch_array($hb1_result);
												if (mysqli_num_rows($hb1_result)!=0) {
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												while($rbData = mysqli_fetch_array($active_rb_result)) {
													$playerid = $rbData['id'];
													$name = $rbData['firstname']." ".$rbData['lastname'];
													$hbArray[] = [$playerid,$name];
													if ($playerid!=$currenthb1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												$hb2Data = mysqli_fetch_array($hb2_result);
												if (mysqli_num_rows($hb2_result)!=0) {
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												$currentfb1 = $lineup['FB_1'];
												$fb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb1");
												$fb1Data = mysqli_fetch_array($fb1_result);
												if (mysqli_num_rows($fb1_result)!=0) {
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												while($fbData = mysqli_fetch_array($active_fb_result)) {
													$playerid = $fbData['id'];
													$name = $fbData['firstname']." ".$fbData['lastname'];
													$fbArray[] = [$playerid,$name];
													if ($playerid!=$currentfb1) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($fb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												$currentfb2 = $lineup['FB_2'];
												$fb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb2");
												$fb2Data = mysqli_fetch_array($fb2_result);
												if (mysqli_num_rows($fb2_result)!=0) {
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($fbArray as $playerarray) {
													if ($playerarray[0]!=$currentfb2) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="terow">
								<h4>Tight Ends</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Tight End #1</td>
										<td><select class="form-control playeropt" name="te1-1select">
											<?php
												$currentte11 = $lineup['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												$te11Data = mysqli_fetch_array($te11_result);
												if (mysqli_num_rows($te11_result)!=0) {
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												while($teData = mysqli_fetch_array($active_te_result)) {
													$playerid = $teData['id'];
													$name = $teData['firstname']." ".$teData['lastname'];
													$teArray[] = [$playerid,$name];
													if ($playerid!=$currentte11) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												$te12Data = mysqli_fetch_array($te12_result);
												if (mysqli_num_rows($te12_result)!=0) {
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte12) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												
												if ($te12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control playeropt" name="te2-1select">
											<?php
												$currentte21 = $lineup['TE2_1'];
												$te21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte21");
												$te21Data = mysqli_fetch_array($te21_result);
												if (mysqli_num_rows($te21_result)!=0) {
													echo "<option value=\"".$currentte21."\">".$te21Data['firstname']." ".$te21Data['lastname']."</option>";
													$te21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte21) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te2-2select">
											<?php
												$currentte22 = $lineup['TE2_2'];
												$te22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte22");
												$te22Data = mysqli_fetch_array($te22_result);
												if (mysqli_num_rows($te22_result)!=0) {
													echo "<option value=\"".$currentte22."\">".$te22Data['firstname']." ".$te22Data['lastname']."</option>";
													$te22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte22) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control playeropt" name="wr1-1select">
											<?php
												$currentwr11 = $lineup['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												$wr11Data = mysqli_fetch_array($wr11_result);
												if (mysqli_num_rows($wr11_result)!=0) {
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												while($wrData = mysqli_fetch_array($active_wr_result)) {
													$playerid = $wrData['id'];
													$name = $wrData['firstname']." ".$wrData['lastname'];
													$wrArray[] = [$playerid,$name];
													if ($playerid!=$currentwr11) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												$wr12Data = mysqli_fetch_array($wr12_result);
												if (mysqli_num_rows($wr12_result)!=0) {
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr12) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												$currentwr21 = $lineup['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												$wr21Data = mysqli_fetch_array($wr21_result);
												if (mysqli_num_rows($wr21_result)!=0) {
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr21) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												$currentwr22 = $lineup['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												$wr22Data = mysqli_fetch_array($wr22_result);
												if (mysqli_num_rows($wr22_result)!=0) {
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr22) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												$currentwr31 = $lineup['WR3_1'];
												$wr31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr31");
												$wr31Data = mysqli_fetch_array($wr31_result);
												if (mysqli_num_rows($wr31_result)!=0) {
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr31) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												$currentwr32 = $lineup['WR3_2'];
												$wr32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr32");
												$wr32Data = mysqli_fetch_array($wr32_result);
												if (mysqli_num_rows($wr31_result)!=0) {
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr32) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control playeropt" name="wr4-1select">
											<?php
												$currentwr41 = $lineup['WR4_1'];
												$wr41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr41");
												$wr41Data = mysqli_fetch_array($wr41_result);
												if (mysqli_num_rows($wr41_result)!=0) {
													echo "<option value=\"".$currentwr41."\">".$wr41Data['firstname']." ".$wr41Data['lastname']."</option>";
													$wr41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr41) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr41set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr4-2select">
											<?php
												$currentwr42 = $lineup['WR4_2'];
												$wr42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr42");
												$wr42Data = mysqli_fetch_array($wr42_result);
												if (mysqli_num_rows($wr42_result)!=0) {
													echo "<option value=\"".$currentwr42."\">".$wr42Data['firstname']." ".$wr42Data['lastname']."</option>";
													$wr42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr42) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr42set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #5 (Slot)</td>
										<td><select class="form-control playeropt" name="wr5-1select">
											<?php
												$currentwr51 = $lineup['WR5_1'];
												$wr51_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr51");
												$wr51Data = mysqli_fetch_array($wr51_result);
												if (mysqli_num_rows($wr51_result)!=0) {
													echo "<option value=\"".$currentwr51."\">".$wr51Data['firstname']." ".$wr51Data['lastname']."</option>";
													$wr51set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr51) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr51set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr5-2select">
											<?php
												$currentwr52 = $lineup['WR5_2'];
												$wr52_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr52");
												$wr52Data = mysqli_fetch_array($wr52_result);
												if (mysqli_num_rows($wr52_result)!=0) {
													echo "<option value=\"".$currentwr52."\">".$wr52Data['firstname']." ".$wr52Data['lastname']."</option>";
													$wr52set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr52) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr52set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b>
									<b><span id="playername"></span></b>
									<p><span id="playerposition"></span></p>
									<p><b>Rating: <span id="playerrating"></span></b></p>
									<p>Height: <span id="height"></span>, Weight: <span id="weight"></span></p>
									<p><span id="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="savegen">Save Depth Chart</button>
								</div>
								
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="22">
							<h4>22 Personnel</h4>
							<p>Players: 1 HB, 1 FB, 2 TE, 1 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control" name="fb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="fb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="terow">
								<h4>Tight Ends</h4>
								<table class="table borderless borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Tight End #1</td>
										<td><select class="form-control" name="te1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control" name="te2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="21">
							<h4>21 Personnel</h4>
							<p>Players: 1 HB, 1 FB, 1 TE, 2 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control" name="fb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="fb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="terow">
								<h4>Tight Ends</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Tight End #1</td>
										<td><select class="form-control" name="te1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="20">
							<h4>20 Personnel</h4>
							<p>Players: 1 HB, 1 FB, 3 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control" name="fb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="fb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control" name="wr3-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr3-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="12">
							<h4>12 Personnel</h4>
							<p>Players: 1 HB, 2 TE, 2 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="terow">
								<h4>Tight Ends</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Tight End #1</td>
										<td><select class="form-control" name="te1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control" name="te2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="11">
							<h4>11 Personnel</h4>
							<p>Players: 1 HB, 1 TE, 3 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="terow">
								<h4>Tight Ends</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Tight End #1</td>
										<td><select class="form-control" name="te1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="te1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="10">
							<h4>10 Personnel</h4>
							<p>Players: 1 HB, 4 WR</p>
							<div class="row well playerrow" id="HBrow">
								<h4>Running Backs</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Halfback</td>
										<td><select class="form-control" name="hb1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="hb2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control" name="wr3-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr3-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control" name="wr4-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr4-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="00">
							<h4>00 Personnel</h4>
							<p>Players: 5 WR</p>
							<div class="row well playerrow" id="wrrow">
								<h4>Receivers</h4>
								<table class="table borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%">Starter</th>
										<th width="40%">Backup</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Wide Receiver #1 (Split End)</td>
										<td><select class="form-control" name="wr1-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr1-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control" name="wr2-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr2-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control" name="wr3-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr3-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control" name="wr4-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr4-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #5 (Slot)</td>
										<td><select class="form-control" name="wr5-1select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
										<td><select class="form-control" name="wr5-2select">
											<option selected>Player</option>
											<option>Player</option>
											<option>Player</option>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="defense">
							This is the general defensive depth chart. Changes here will affect every defensive formation.
						</div>
						<div class="tab-pane fade" id="43">
						</div>
						<div class="tab-pane fade" id="34">
						</div>
						<div class="tab-pane fade" id="eight">
						</div>
						<div class="tab-pane fade" id="nickel">
						</div>
						<div class="tab-pane fade" id="dime">
						</div>
						<div class="tab-pane fade" id="goalline">
						</div>
					</div>
				  </div>
				</div>
			</div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>