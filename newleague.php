<?php
	require_once 'includes/functions.php';
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	if (isset($_POST['submit'])){
		$leaguename = $_POST['league-name'];
		$frequency = $_POST['frequency'];
		$salarycap = $_POST['salarycap'];
		$injuries = $_POST['injuries'];
		
		if ($frequency == "Every day") {
			$freq = "ed";
		} else if ($frequency == "Every other day") {
			$freq = "eod";
		} else if ($frequency == "Saturday/Monday/Wednesday") {
			$freq = "smw";
		} else {
			echo "No frequency selected?";
			die();
		}
		
		if ($salarycap == "On") {
			$salary = "y";
		} else if ($salarycap == "Off") {
			$salary = "n";
		} else {
			echo "No salary cap selected?";
			die();
		}
		
		if ($injuries == "On") {
			$injury = "y";
		} else if ($injuries == "Off") {
			$injury = "n";
		} else {
			echo "No injury selected?";
			die();
		}
		$year = date("Y");
		mysqli_query($conn,"INSERT INTO `league` (`leaguename`, `frequency`, `salarycap`, `injuries`,`year`,`calendar`) 
				VALUES ('{$leaguename}', '{$freq}', '{$salary}', '{$injury}','{$year}',1)");
		if (mysqli_affected_rows($conn) == 1) {
			$leagueid = mysqli_insert_id($conn);
			mysqli_query($conn,"INSERT INTO `board` (id,boardname) VALUES ($leagueid,'$leaguename')");
		} else {
			echo "League create fail...";
		}
		
		//Populate team table
		$division = array("afc_east","afc_east","afc_east","afc_east","afc_north","afc_north","afc_north","afc_north",
			"afc_south","afc_south","afc_south","afc_south","afc_west","afc_west","afc_west","afc_west",
			"nfc_east","nfc_east","nfc_east","nfc_east","nfc_north","nfc_north","nfc_north","nfc_north",
			"nfc_south","nfc_south","nfc_south","nfc_south","nfc_west","nfc_west","nfc_west","nfc_west");
		$location = array("New England","New York","Miami","Buffalo","Cincinnati","Baltimore","Pittsburgh","Cleveland",
			"Indianapolis","Jacksonville","Houston","Tennessee","Denver","Kansas City","San Diego","Oakland",
			"Philadelphia","Dallas","New York","Washington","Green Bay","Chicago","Minnesota","Detroit",
			"Atlanta","Carolina","New Orleans","Tampa Bay","Seattle","San Francisco","St. Louis","Arizona");
		$teamname = array("Bulls","Ducks","Mutants","Flyers","Assassins","Bandits","Cyborgs","Bombers",
			"Heroes","Reapers","Miners","Pirates","Fireballs","Busters","Scorpions","Stingers",
			"Bullets","Blazers","Champions","Kings","Chasers","Hurricanes","Dragons","Tanks",
			"Tornadoes","Hawks","Slammers","Legends","Wild","Lightning","Sharpshooters","Commandos");
		$abbrev = array("NE","NYD","MIA","BUF","CIN","BAL","PIT","CLE",
			"IND","JAX","HOU","TEN","DEN","KC","SD","OAK",
			"PHI","DAL","NYC","WAS","GB","CHI","MIN","DET",
			"ATL","CAR","NO","TB","SEA","SF","STL","ARI");
		$standing = array(1,2,3,4,1,2,3,4,1,2,3,4,1,2,3,4,1,2,3,4,1,2,3,4,1,2,3,4,1,2,3,4);
		for ($i = 0; $i < 32; $i++) {
			mysqli_query($conn,"INSERT INTO `team`(`league`, `division`, `location`, `teamname`,`abbrev`,`season_win`,`season_loss`,`total_win`,`total_loss`,`championships`,`division_win`,`division_loss`,`conf_win`,`conf_loss`,`points_for`,`points_against`,`season_tie`,`total_tie`,`logofile`,`standing`) VALUES ('{$leagueid}', '{$division[$i]}', '{$location[$i]}', '{$teamname[$i]}', '{$abbrev[$i]}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,'helmet.png','{$standing[$i]}')");
		}
		$last_team = mysqli_insert_id($conn);
		
		//Generate Schedule
		$allteams = range($last_team-31,$last_team);
			
		for ($i=0;$i<32;$i++) {
			$teamno = $allteams[$i];
			//Create all 256 games (32 teams, 8 home games each)
			for ($j=0;$j<8;$j++) {
				mysqli_query($conn,"INSERT INTO `games` (league,year,home) VALUES ($leagueid,$year,$teamno)");
				if ($i==31 && $j==7) {
					$reggames = mysqli_insert_id($conn);
				}
			}
			//intra-divisional games (3 home games)
			$team_result = mysqli_query($conn,"SELECT division,standing FROM team WHERE id=$teamno");
			$teamData = mysqli_fetch_array($team_result);
			$division = $teamData['division'];
			$standing = $teamData['standing'];
			
			$divteams_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$leagueid AND division='$division' AND id!=$teamno");
			while ($divteamData = mysqli_fetch_array($divteams_result)) {
				$divteam = $divteamData['id'];
				mysqli_query($conn,"UPDATE `games` SET away=$divteam ,division='$division' WHERE home=$teamno AND away IS NULL LIMIT 1");
			}
			
			$sameconf = "";
			$difconf = "";
			$finalgame = "";
			switch ($division) {
				case "afc_east":
					$sameconf = "afc_west";
					$difconf = "nfc_west";
					$finalgame = "afc_north";
					break;
				case "afc_west":
					$sameconf = "afc_east";
					$difconf = "nfc_east";
					$finalgame = "afc_south";
					break;
				case "afc_south":
					$sameconf = "afc_north";
					$difconf = "nfc_south";
					$finalgame = "afc_east";
					break;
				case "afc_north":
					$sameconf = "afc_south";
					$difconf = "nfc_north";
					$finalgame = "afc_west";
					break;
				case "nfc_east":
					$sameconf = "nfc_west";
					$difconf = "afc_west";
					$finalgame = "nfc_north";
					break;
				case "nfc_west":
					$sameconf = "nfc_east";
					$difconf = "afc_east";
					$finalgame = "nfc_south";
					break;
				case "nfc_south":
					$sameconf = "nfc_north";
					$difconf = "afc_south";
					$finalgame = "nfc_east";
					break;
				case "nfc_north":
					$sameconf = "nfc_south";
					$difconf = "afc_north";
					$finalgame = "nfc_west";
					break;
			}
			
			//Find games in which team is already scheduled (as away team)
			$already_result = mysqli_query($conn,"SELECT home FROM games WHERE away=$teamno AND year=$year");
			$alArray = [];
			while ($alreadyData = mysqli_fetch_array($already_result)) {
				$alArray[] = $alreadyData['home'];
			}
			
			//Division in same conference (2 home games)
			$sameconfcount = 0;
			$sameconf_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$leagueid AND division='$sameconf'");
			
			if (!$sameconf_result) {
				printf("Error: %s\n", mysqli_error($conn));
				exit();
			}
			while ($sameData = mysqli_fetch_array($sameconf_result)) {
				$sameconfteam = $sameData['id'];
				$count_result = mysqli_query($conn,"SELECT id FROM games WHERE division='$division' AND away=$sameconfteam");
				$count = mysqli_num_rows($count_result);
				if (!in_array($sameconfteam,$alArray) && $count<2 && $sameconfcount<2) {
						mysqli_query($conn,"UPDATE `games` SET away=$sameconfteam,division='$division' WHERE year=$year AND home=$teamno AND away IS NULL LIMIT 1");
						$sameconfcount++;
				}
			}
			
			//division in other conference (2 home games)
			
			$difconfcount = 0;
			$difconf_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$leagueid AND division='$difconf'");
			if (!$difconf_result) {
				printf("Error: %s\n", mysqli_error($conn));
				exit();
			}
			while ($difData = mysqli_fetch_array($difconf_result)) {
				$difconfteam = $difData['id'];
				$count_result = mysqli_query($conn,"SELECT id FROM games WHERE division='$division' AND away=$difconfteam");
				$count = mysqli_num_rows($count_result);
				if (!in_array($difconfteam,$alArray) && $count<2 && $difconfcount<2) {
						mysqli_query($conn,"UPDATE `games` SET away=$difconfteam,division='$division' WHERE year=$year AND home=$teamno AND away IS NULL LIMIT 1");
						$difconfcount++;
				}
			}
			
			//Final game (1 home game)
			$final_result = mysqli_query($conn,"SELECT id FROM team WHERE league=$leagueid AND `division`='$finalgame' AND standing='$standing'");
			while ($finalData = mysqli_fetch_array($final_result)) {
				$finalteam = $finalData['id'];
				mysqli_query($conn,"UPDATE `games` SET away=$finalteam,division='$division' WHERE year=$year AND home=$teamno AND away IS NULL LIMIT 1");
				
			}
			
		}
		
		//Assign week
			$weekArray = range(1,16);
			shuffle($weekArray);
			$game_result = mysqli_query($conn,"SELECT id,home,away FROM games WHERE week IS NULL AND year=$year AND league=$leagueid");
			while ($gameData=mysqli_fetch_array($game_result)) {
				$id = $gameData['id'];
				$home = $gameData['home'];
				$away = $gameData['away'];
				$excluded = [];
				
				$week_query= "SELECT week FROM games WHERE (home=$home OR home=$away OR away=$home OR away=$away) AND week IS NOT NULL";
				$week_result = mysqli_query($conn,$week_query);
				// if (!$week_result) {
				//	echo($week_query."<br>");
				//	printf("Error: %s\n", mysqli_error($conn));
				//	exit();
				// }
				while ($weekData = mysqli_fetch_array($week_result)) {
					$excluded[] = $weekData['week'];
				}
				foreach($weekArray as $week) {
					if (!in_array($week,$excluded)) {
						mysqli_query($conn,"UPDATE games SET week=$week WHERE id=$id");
						break;
					}
				}
				
			}
		
		//Preseason
		$preteams = $allteams;
		shuffle($preteams);
		$prehome = array_slice($preteams,0,16);
		$preaway = array_slice($preteams,16,16);
		
		for ($week=17;$week<21;$week++) {
			shuffle($prehome);
			shuffle($preaway);
			foreach($prehome as $index => $home) {
				$away = $preaway[$index];
				if ($week%2==0) {
				$prequery = "INSERT INTO games (league,year,week,home,away) VALUES ($leagueid,$year,$week,$home,$away)";
				} else {
					$prequery = "INSERT INTO games (league,year,week,home,away) VALUES ($leagueid,$year,$week,$away,$home)";
				}
				mysqli_query($conn,$prequery);
			}
		}
		//Generate Players
		//--Generate QBs
		genplayers(128,$year,$leagueid,"QB",true);
		//--Generate RBs
		genplayers(192,$year,$leagueid,"RB",true);
		//--Generate FBs
		genplayers(64,$year,$leagueid,"FB",true);
		//--Generate WRs
		genplayers(256,$year,$leagueid,"WR",true);
		//--Generate TEs
		genplayers(128,$year,$leagueid,"TE",true);
		//--Generate Gs
		genplayers(192,$year,$leagueid,"G",true);
		//--Generate Cs
		genplayers(128,$year,$leagueid,"C",true);
		//--Generate Ts
		genplayers(192,$year,$leagueid,"T",true);
		//--Generate DEs
		genplayers(192,$year,$leagueid,"DE",true);
		//--Generate DTs
		genplayers(192,$year,$leagueid,"DT",true);
		//--Generate LBs
		genplayers(256,$year,$leagueid,"LB",true);
		//--Generate CBs
		genplayers(256,$year,$leagueid,"CB",true);
		//--Generate Ss
		genplayers(256,$year,$leagueid,"S",true);
		//--Generate Ks
		genplayers(64,$year,$leagueid,"K",true);
		//--Generate Ps
		genplayers(64,$year,$leagueid,"P",true);
		
		//Creation Draft
		$draftorder = range($last_team-31,$last_team);
		$draft_round = 1;
		
		//--Select QBs: 4 rounds
		$qb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='QB' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<4; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($qb_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"QB",$rating);
			}
			$draft_round++;
		}
		
		//--Select RBs; 6 rounds
		$rb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='RB' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<6; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($rb_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"RB",$rating);
			}
			$draft_round++;
		}
		
		//--Select FBs; 2 rounds
		$fb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='FB' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<2; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($fb_result, MYSQL_ASSOC);
				$rating = $playerData['overall_now'];
				$playerid = $playerData['id'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"FB",$rating);
			}
			$draft_round++;
		}
		
		//--Select WRs; 8 rounds
		$wr_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='WR' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<8; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($wr_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"WR",$rating);
			}
			$draft_round++;
		}
		//--Select TEs; 4 rounds
		$te_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='TE' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<4; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($te_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"TE",$rating);
			}
			$draft_round++;
		}
		//--Select Gs; 6 rounds
		$g_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='G' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<6; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($g_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"G",$rating);
			}
			$draft_round++;
		}
		
		//--Select Cs; 4 rounds
		$c_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='C' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<4; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($c_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"C",$rating);
			}
			$draft_round++;
		}
		//--Select Ts; 6 rounds
		$t_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='T' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<6; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($t_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"T",$rating);
			}
			$draft_round++;
		}
		
		//--Select DEs; 6 rounds
		$de_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='DE' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<6; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($de_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"DE",$rating);
			}
			$draft_round++;
		}
		
		//--Select DTs; 6 rounds
		$dt_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='DT' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<6; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($dt_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"DT",$rating);
			}
			$draft_round++;
		}
		
		//--Select LBs; 8 rounds
		$lb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='LB' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<8; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($lb_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"LB",$rating);
			}
			$draft_round++;
		}
		
		//--Select CBs; 8 rounds
		$cb_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='CB' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<8; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($cb_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"CB",$rating);
			}
			$draft_round++;
		}
		//--Select Ss; 8 rounds
		$s_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='S' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<8; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($s_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"S",$rating);
			}
			$draft_round++;
		}
		//--Select Ks; 2 rounds
		$k_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='K' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<2; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($k_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"K",$rating);
			}
			$draft_round++;
		}
		//--Select Ps; 2 rounds
		$p_result = mysqli_query($conn,"SELECT player.*,attributes.overall_now FROM player INNER JOIN attributes ON attributes.player=player.id WHERE player.position='P' AND player.league=$leagueid ORDER BY attributes.overall_now DESC");
		for ($k=0; $k<2; $k++) {
			shuffle($draftorder);
			for ($i=1; $i<33; $i++) {
				$playerData = mysqli_fetch_array($p_result, MYSQL_ASSOC);
				$playerid = $playerData['id'];
				$rating = $playerData['overall_now'];
				$teamid = $draftorder[$i-1];
				$draft_pos = $i;
				mysqli_query($conn,"UPDATE player SET team=$teamid,status='inactive',draft_round=$draft_round,draft_pos=$draft_pos,draft_team=$teamid WHERE id=$playerid");
				create_salaries($playerid,$year,$teamid,"P",$rating);
			}
			$draft_round++;
		}
		
		//Create personnel groupings
		$lineupteams = range($last_team-31,$last_team);
		for ($i=0;$i<32;$i++) {
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'all')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'22')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'21')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'20')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'12')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'11')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'10')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'00')");
			mysqli_query($conn,"INSERT INTO `offlineup` (team,personnel) VALUES ($lineupteams[$i],'23')");
			
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'all')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'434')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'425')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'344')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'335')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'443')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'353')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'425n')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'335n')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'416')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'326')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'317')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'623')");
			mysqli_query($conn,"INSERT INTO `deflineup` (team,personnel) VALUES ($lineupteams[$i],'632')");
			
			mysqli_query($conn,"INSERT INTO `stlineup` (team) VALUES ($lineupteams[$i])");
		}
		
		
		header('Location: league.php?leagueid='.$leagueid);
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
    <link href="../css/register.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <title>RedZone Rush - Create League</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2" id="top">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <?php
			  $teamidArray = array();
			  $locationArray = array();
			  $teamnameArray = array();
			  $leagueArray = array();
				if(mysqli_num_rows($own_team_result) == 0) {
				} else if (mysqli_num_rows($own_team_result) == 1) {
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					echo "<li><a href=\"league.php?leagueid=".$leagueArray[0]."\">League</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"dropdown\">
							<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">League <span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\" role=\"menu\">";
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
						array_push($teamidArray, $own_teamData['id']);
						array_push($locationArray, $own_teamData['location']);
						array_push($teamnameArray, $own_teamData['teamname']);
						array_push($leagueArray, $own_teamData['league']);
						echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$teamidArray[$k]."\">League ".$leagueArray[$k]."</a></li>
						<li role=\"presentation\" class=\"divider\"></li>";
					}
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
					
					echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"league.php?leagueid=".$leagueArray[count($leagueArray)-1]."\">League ".$leagueArray[count($leagueArray)-1]."</a></li>";
					echo "</ul></li>";
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
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">
          <div class="main">
		  <h3>Create League</h3>
            <form class="form-horizontal" method="POST" id="new-league" action="newleague.php" role="form">
              <div class="form-group">
                <label for="league-name" class="col-sm-2 control-label">League Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="league-name" name="league-name" placeholder="League Name">
                </div>
              </div>
			  <div class="form-group">
                <label for="frequency" class="col-sm-2 control-label">Frequency</label>
                <div class="col-sm-10">
				<select class="form-control" id="frequency" name="frequency">
					<option>Every day</option>
					<option>Every other day</option>
					<option>Saturday/Monday/Wednesday</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <label for="salarycap" class="col-sm-2 control-label">Salary Cap</label>
                <div class="col-sm-10">
				<select class="form-control" id="salarycap" name="salarycap">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
			  <div class="form-group">
                <label for="injuries" class="col-sm-2 control-label">Injuries</label>
                <div class="col-sm-10">
				<select class="form-control" id="injuries" name="injuries">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary" id="submit" name="submit">Create League</button>
                </div>
              </div>
            </form>
			
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
