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
	$tab = "offense";
	
	$member_result = mysqli_query($conn,"SELECT * FROM `member` WHERE id=$userID");
	$memberData = mysqli_fetch_array($member_result);
	$premium = $memberData['premium'];
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
		$TE3_1 = $_POST['te3-1select'];
		$TE3_2 = $_POST['te3-2select'];
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
		FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,TE2_1=$TE2_1,TE2_2=$TE2_2,TE3_1=$TE3_1,TE3_2=$TE3_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2,WR4_1=$WR4_1,WR4_2=$WR4_2,WR5_1=$WR5_1,WR5_2=$WR5_2 WHERE team=$teamid";
		mysqli_query($conn,$lineupquery);
		$tab = "offense";
	}
	
	if (isset($_POST['save22'])) {
		
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
		
		$lineupquery = "UPDATE `offlineup` SET FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,TE2_1=$TE2_1,TE2_2=$TE2_2,WR1_1=$WR1_1,WR1_2=$WR1_2 WHERE team=$teamid AND personnel='22'";
		mysqli_query($conn,$lineupquery);
		$tab = "22";
	}
	
	if (isset($_POST['save21'])) {
		
		$FB_1 = $_POST['fb1select'];
		$FB_2 = $_POST['fb2select'];
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$TE1_1 = $_POST['te1-1select'];
		$TE1_2 = $_POST['te1-2select'];
		$WR1_1 = $_POST['wr1-1select'];
		$WR1_2 = $_POST['wr1-2select'];
		$WR2_1 = $_POST['wr2-1select'];
		$WR2_2 = $_POST['wr2-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2 WHERE team=$teamid AND personnel='21'";
		mysqli_query($conn,$lineupquery);
		$tab = "21";
	}
	
	if (isset($_POST['save20'])) {
		
		$FB_1 = $_POST['fb1select'];
		$FB_2 = $_POST['fb2select'];
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$WR1_1 = $_POST['wr1-1select'];
		$WR1_2 = $_POST['wr1-2select'];
		$WR2_1 = $_POST['wr2-1select'];
		$WR2_2 = $_POST['wr2-2select'];
		$WR3_1 = $_POST['wr3-1select'];
		$WR3_2 = $_POST['wr3-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2 WHERE team=$teamid AND personnel='20'";
		mysqli_query($conn,$lineupquery);
		$tab = "20";
	}
	
	if (isset($_POST['save12'])) {
		
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
		
		$lineupquery = "UPDATE `offlineup` SET HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,TE2_1=$TE2_1,TE2_2=$TE2_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2 WHERE team=$teamid AND personnel='12'";
		mysqli_query($conn,$lineupquery);
		$tab = "12";
	}
	
	if (isset($_POST['save11'])) {
		
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$TE1_1 = $_POST['te1-1select'];
		$TE1_2 = $_POST['te1-2select'];
		$WR1_1 = $_POST['wr1-1select'];
		$WR1_2 = $_POST['wr1-2select'];
		$WR2_1 = $_POST['wr2-1select'];
		$WR2_2 = $_POST['wr2-2select'];
		$WR3_1 = $_POST['wr3-1select'];
		$WR3_2 = $_POST['wr3-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2 WHERE team=$teamid AND personnel='11'";
		mysqli_query($conn,$lineupquery);
		$tab = "11";
	}
	
	if (isset($_POST['save10'])) {
		
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$WR1_1 = $_POST['wr1-1select'];
		$WR1_2 = $_POST['wr1-2select'];
		$WR2_1 = $_POST['wr2-1select'];
		$WR2_2 = $_POST['wr2-2select'];
		$WR3_1 = $_POST['wr3-1select'];
		$WR3_2 = $_POST['wr3-2select'];
		$WR4_1 = $_POST['wr4-1select'];
		$WR4_2 = $_POST['wr4-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET HB_1=$HB_1,HB_2=$HB_2,WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2,WR4_1=$WR4_1,WR4_2=$WR4_2 WHERE team=$teamid AND personnel='10'";
		mysqli_query($conn,$lineupquery);
		$tab = "10";
	}
	
	if (isset($_POST['save00'])) {
		
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
		
		$lineupquery = "UPDATE `offlineup` SET WR1_1=$WR1_1,WR1_2=$WR1_2,WR2_1=$WR2_1,WR2_2=$WR2_2,WR3_1=$WR3_1,WR3_2=$WR3_2,WR4_1=$WR4_1,WR4_2=$WR4_2,WR5_1=$WR5_1,WR5_2=$WR5_2 WHERE team=$teamid AND personnel='00'";
		mysqli_query($conn,$lineupquery);
		$tab = "00";
	}
	
	if (isset($_POST['save23'])) {
		
		$FB_1 = $_POST['fb1select'];
		$FB_2 = $_POST['fb2select'];
		$HB_1 = $_POST['hb1select'];
		$HB_2 = $_POST['hb2select'];
		$TE1_1 = $_POST['te1-1select'];
		$TE1_2 = $_POST['te1-2select'];
		$TE2_1 = $_POST['te2-1select'];
		$TE2_2 = $_POST['te2-2select'];
		$TE3_1 = $_POST['te3-1select'];
		$TE3_2 = $_POST['te3-2select'];
		
		$lineupquery = "UPDATE `offlineup` SET FB_1=$FB_1,FB_2=$FB_2,HB_1=$HB_1,HB_2=$HB_2,TE1_1=$TE1_1,TE1_2=$TE1_2,TE2_1=$TE2_1,TE2_2=$TE2_2,TE3_1=$TE3_1,TE3_2=$TE3_2 WHERE team=$teamid AND personnel='23'";
		mysqli_query($conn,$lineupquery);
		$tab = "23";
	}
	
	if (isset($_POST['savegendef'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$JLB_1 = $_POST['jlb1select'];
		$JLB_2 = $_POST['jlb2select'];
		$XLB_1 = $_POST['xlb1select'];
		$XLB_2 = $_POST['xlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$CB4_1 = $_POST['cb4-1select'];
		$CB4_2 = $_POST['cb4-2select'];
		$CB5_1 = $_POST['cb5-1select'];
		$CB5_2 = $_POST['cb5-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		$SS2_1 = $_POST['ss2-1select'];
		$SS2_2 = $_POST['ss2-2select'];
		$RS_1 = $_POST['rs1select'];
		$RS_2 = $_POST['rs2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,NT_1=$NT_1,NT_2=$NT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,JLB_1=$JLB_1,JLB_2=$JLB_2,
		XLB_1=$XLB_1,XLB_2=$XLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,CB4_1=$CB4_1,CB4_2=$CB4_2,
		CB5_1=$CB5_1,CB5_2=$CB5_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2,SS2_1=$SS2_1,SS2_2=$SS2_2,RS_1=$RS_1,RS_2=$RS_2 WHERE team=$teamid";
		mysqli_query($conn,$lineupquery);
		$tab = "defense";
		
	}
	
	if (isset($_POST['save434'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,CB1_1=$CB1_1,
		CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='434'";
		mysqli_query($conn,$lineupquery);
		$tab = "434";
		
	}
	
	if (isset($_POST['save425'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		$SS2_1 = $_POST['ss2-1select'];
		$SS2_2 = $_POST['ss2-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,CB1_1=$CB1_1,
		CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2,SS2_1=$SS2_1,SS2_2=$SS2_2 WHERE team=$teamid AND personnel='425'";
		mysqli_query($conn,$lineupquery);
		$tab = "425";
		
	}
	
	if (isset($_POST['save344'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$JLB_1 = $_POST['jlb1select'];
		$JLB_2 = $_POST['jlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,
		JLB_1=$JLB_1,JLB_2=$JLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='344'";
		mysqli_query($conn,$lineupquery);
		$tab = "344";
	}
	if (isset($_POST['save335'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		$SS2_1 = $_POST['ss1-1select'];
		$SS2_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,
		CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2,SS2_1=$SS2_1,SS2_2=$SS2_2 WHERE team=$teamid AND personnel='335'";
		mysqli_query($conn,$lineupquery);
		$tab = "335";
	}
	if (isset($_POST['save443'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$JLB_1 = $_POST['jlb1select'];
		$JLB_2 = $_POST['jlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,
		MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,JLB_1=$JLB_1,JLB_2=$JLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2 WHERE team=$teamid AND personnel='443'";
		mysqli_query($conn,$lineupquery);
		$tab = "443";
	}
	
	if (isset($_POST['save353'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$JLB_1 = $_POST['jlb1select'];
		$JLB_2 = $_POST['jlb2select'];
		$XLB_1 = $_POST['xlb1select'];
		$XLB_2 = $_POST['xlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,
		JLB_1=$JLB_1,JLB_2=$JLB_2,XLB_1=$XLB_1,XLB_2=$XLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,FS_1=$FS_1,FS_2=$FS_2 WHERE team=$teamid AND personnel='353'";
		mysqli_query($conn,$lineupquery);
		$tab = "353";
	}
	if (isset($_POST['save425n'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,
		FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='425n'";
		mysqli_query($conn,$lineupquery);
		$tab = "425n";
	}
	if (isset($_POST['save335n'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,
		CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='335n'";
		mysqli_query($conn,$lineupquery);
		$tab = "335n";
	}
	
	if (isset($_POST['save416'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$CB4_1 = $_POST['cb4-1select'];
		$CB4_2 = $_POST['cb4-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,CB4_1=$CB4_1,CB4_2=$CB4_2,
		FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='416'";
		mysqli_query($conn,$lineupquery);
		$tab = "416";
	}
	if (isset($_POST['save326'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$CB4_1 = $_POST['cb4-1select'];
		$CB4_2 = $_POST['cb4-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,
		CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,CB4_1=$CB4_1,CB4_2=$CB4_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='326'";
		mysqli_query($conn,$lineupquery);
		$tab = "326";
	}
	if (isset($_POST['save317'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$NT_1 = $_POST['nt1select'];
		$NT_2 = $_POST['nt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$CB2_1 = $_POST['cb2-1select'];
		$CB2_2 = $_POST['cb2-2select'];
		$CB3_1 = $_POST['cb3-1select'];
		$CB3_2 = $_POST['cb3-2select'];
		$CB4_1 = $_POST['cb4-1select'];
		$CB4_2 = $_POST['cb4-2select'];
		$CB5_1 = $_POST['cb5-1select'];
		$CB5_2 = $_POST['cb5-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,NT_1=$NT_1,NT_2=$NT_2,RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,
		CB1_1=$CB1_1,CB1_2=$CB1_2,CB2_1=$CB2_1,CB2_2=$CB2_2,CB3_1=$CB3_1,CB3_2=$CB3_2,CB4_1=$CB4_1,CB4_2=$CB4_2,CB5_1=$CB5_1,CB5_2=$CB5_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2 WHERE team=$teamid AND personnel='317'";
		mysqli_query($conn,$lineupquery);
		$tab = "317";
	}
	if (isset($_POST['save623'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$CB1_1 = $_POST['cb1-1select'];
		$CB1_2 = $_POST['cb1-2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		$SS2_1 = $_POST['ss2-1select'];
		$SS2_2 = $_POST['ss2-2select'];
		$RS_1 = $_POST['rs1select'];
		$RS_2 = $_POST['rs2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,CB1_1=$CB1_1,CB1_2=$CB1_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2,SS2_1=$SS2_1,SS2_2=$SS2_2,RS_1=$RS_1,RS_2=$RS_2 WHERE team=$teamid AND personnel='623'";
		mysqli_query($conn,$lineupquery);
		$tab = "623";
		
	}
	if (isset($_POST['save632'])) {
		$LDE_1 = $_POST['lde1select'];
		$LDE_2 = $_POST['lde2select'];
		$LDT_1 = $_POST['ldt1select'];
		$LDT_2 = $_POST['ldt2select'];
		$RDT_1 = $_POST['rdt1select'];
		$RDT_2 = $_POST['rdt2select'];
		$RDE_1 = $_POST['rde1select'];
		$RDE_2 = $_POST['rde2select'];
		$WLB_1 = $_POST['wlb1select'];
		$WLB_2 = $_POST['wlb2select'];
		$MLB_1 = $_POST['mlb1select'];
		$MLB_2 = $_POST['mlb2select'];
		$SLB_1 = $_POST['slb1select'];
		$SLB_2 = $_POST['slb2select'];
		$FS_1 = $_POST['fs1select'];
		$FS_2 = $_POST['fs2select'];
		$SS1_1 = $_POST['ss1-1select'];
		$SS1_2 = $_POST['ss1-2select'];
		$SS2_1 = $_POST['ss2-1select'];
		$SS2_2 = $_POST['ss2-2select'];
		$RS_1 = $_POST['rs1select'];
		$RS_2 = $_POST['rs2select'];
		
		$lineupquery = "UPDATE `deflineup` SET LDE_1=$LDE_1,LDE_2=$LDE_2,LDT_1=$LDT_1,LDT_2=$LDT_2,RDT_1=$RDT_1,RDT_2=$RDT_2,
		RDE_1=$RDE_1,RDE_2=$RDE_2,WLB_1=$WLB_1,WLB_2=$WLB_2,MLB_1=$MLB_1,MLB_2=$MLB_2,SLB_1=$SLB_1,SLB_2=$SLB_2,FS_1=$FS_1,FS_2=$FS_2,SS1_1=$SS1_1,SS1_2=$SS1_2,SS2_1=$SS2_1,SS2_2=$SS2_2,RS_1=$RS_1,RS_2=$RS_2 WHERE team=$teamid AND personnel='632'";
		mysqli_query($conn,$lineupquery);
		$tab = "632";
	}
	
	if (isset($_POST['savesteams'])) {
		
		$K_1=$_POST['k1select'];
		$K_2=$_POST['k2select'];
		$P_1=$_POST['p1select'];
		$P_2=$_POST['p2select'];
		$KR_1=$_POST['kr1select'];
		$KR_2=$_POST['kr2select'];
		$PR_1=$_POST['pr1select'];
		$PR_2=$_POST['pr2select'];
		
		$lineupquery = "UPDATE `stlineup` SET K_1=$K_1,K_2=$K_2,P_1=$P_1,P_2=$P_2,KR_1=$KR_1,KR_2=$KR_2,PR_1=$PR_1,PR_2=$PR_2 WHERE team=$teamid";
		mysqli_query($conn,$lineupquery);
		$tab = "steams";
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
    <script src="../js/tendina.js"></script>
	<script>
	document.write('<style>.playerbox { display: none; }</style>');
	document.write('<style>#neterror { display: none; }</style>');
	</script>
	
	<script>
	$( document ).ready(function() {
		$('a[href="#<?php echo $tab;?>"]').tab('show');
		$('#packages').tendina();
		
		$('.playeropt').on('change click', function(e){
			e.preventDefault();
			var value = $(this).val();
			if (value!=0) {
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
					
					$(".playername").html("<a href=\"player.php?playerid="+value+"\">"+name+"</a>");
					$(".playerposition").html(position);
					$(".playerhealth").html(health);
					$(".playerrating").html(rating);
					$(".height").html(height);
					$(".weight").html(weight);
					
					$('.playerbox').fadeIn();
					$('#neterror').slideUp();
				  },
				  error: function(xhr, desc, err) {
					$('#neterror').slideDown();
				  }
				  }); //end ajax 
			  }
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
				$senttrade_result = mysqli_query($conn,"SELECT * FROM trades WHERE AND `team_one`=$teamid ORDER BY timestamp DESC");
				echo "<li>
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
                </li><li class="active">
                  <a href="">Depth Chart</a>
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
          <div class="main">
		  <div class="row">
			<div class="col-md-offset-4 col-lg-offset-3 col-lg-8 col-md-12" ><h3><?php echo $location." ".$teamname;?> Depth Chart</h3>
		  </div>
		  <div class="row" id="depth">
			<div class="col-sm-6 col-md-4 col-lg-3">
			<div class="packageselect">
			<ul class="sidelist "id="packages">
				<li class="liheader">
					<a href="#" data-toggle="tab">Offense</a>
					<ul>
						<li>
							<a href="#offense" data-toggle="tab">General Offense</a>
						</li>
						<li>
							<a href="#22" data-toggle="tab">22 Personnel</a>
						</li>
						<li>
							<a href="#21" data-toggle="tab">21 Personnel</a>
						</li>
						<li>
							<a href="#20" data-toggle="tab">20 Personnel</a>
						</li>
						<li>
							<a href="#12" data-toggle="tab">12 Personnel</a>
						</li>
						<li>
							<a href="#11" data-toggle="tab">11 Personnel</a>
						</li>
						<li>
							<a href="#10" data-toggle="tab">10 Personnel</a>
						</li>
						<li>
							<a href="#00" data-toggle="tab">00 Personnel</a>
						</li>
						<li>
							<a href="#23" data-toggle="tab">Goal Line Offense</a>
						</li>
					</ul>
				</li> 
				<li class="liheader" id="deftab">
					<a href="#" data-toggle="tab">Defense</a>
					<ul>
						<li>
							<a href="#defense" data-toggle="tab">General Defense</a>
						</li>
						<li>
							<a href="#">Base Formations</a>
							<ul>
								<li>
									<a href="#434" data-toggle="tab">4-3-4</a>
								</li>
								<?php 
								if ($premium=="y") {
								echo "
								<li>
									<a href=\"#425\" data-toggle=\"tab\">4-2-5</a>
								</li>
								<li>
									<a href=\"#344\" data-toggle=\"tab\">3-4-4</a>
								</li>
								<li>
									<a href=\"#335\" data-toggle=\"tab\">3-3-5</a>
								</li>";
								}
								?>
							</ul>
						</li>
						<li>
							<a href="#">Eight in the Box</a>
							<ul>
								<li class="linohead">
									<a href="#443" data-toggle="tab">4-4-3</a>
								</li>
								<?php 
								if ($premium=="y") {
								echo "
								<li class=\"linohead\">
									<a href=\"#353\" data-toggle=\"tab\">3-5-3</a>
								</li>";
								} ?>
							</ul>
						</li>
						<li>
							<a href="#">Nickel</a>
							<ul>
								<li class="linohead">
									<a href="#425n" data-toggle="tab">4-2-5</a>
								</li>
								<?php 
								if ($premium=="y") {
								echo "
								<li class=\"linohead\">
									<a href=\"#335n\" data-toggle=\"tab\">3-3-5</a>
								</li>";
								} ?>
							</ul>
						</li>
						<li>
							<a href="#">Dime<?php 
								if ($premium=="y") {
								echo " & Quarter";}?>
							</a>
							<ul>
								<li class="linohead">
									<a href="#416" data-toggle="tab">4-1-6</a>
								</li>
								<?php 
								if ($premium=="y") {
								echo "
								<li class=\"linohead\">
									<a href=\"#326\" data-toggle=\"tab\">3-2-6</a>
								</li>
								<li class=\"linohead\">
									<a href=\"#317\" data-toggle=\"tab\">3-1-7 (Quarter)</a>
								</li>";
								} ?>
							</ul>
						</li>
						<li>
							<a href="#">Goal Line</a>
							<ul>
								<li class="linohead">
									<a href="#623" data-toggle="tab">6-2-3</a>
								</li>
								<li class="linohead">
									<a href="#632" data-toggle="tab">6-3-2</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="liheader" id="spectab">
					<a href="#steams" data-toggle="tab">Special Teams</a>
				</li>
			</ul>
			</div>
			</div>
				  <div class="col-lg-8 col-md-12">
					<div class="tab-content" id="all-tabs">
						<div class="tab-pane fade in active" id="offense">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="genoffense">
						<div class="col-md-10 col-sm-12">
						<h4>Offense</h4>
							This is the general offensive depth chart. Changes here will affect every offensive formation.
							<?php 
							$active_qb_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='QB' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_rb_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='RB' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_fb_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='FB' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_wr_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='WR' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_te_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='TE' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_g_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='G' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_c_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='C' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_t_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='T' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_de_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='DE' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_dt_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='DT' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_lb_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='LB' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_cb_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='CB' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_s_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='S' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_k_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='K' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							$active_p_result = mysqli_query($conn,"SELECT player.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.status='active' AND player.position='P' AND player.team=$teamid ORDER BY attributes.overall_now DESC");
							
							$qbArray = [];
							$rbArray = [];
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
							//Populate player arrays
							while($qbData = mysqli_fetch_array($active_qb_result)) {
								$playerid = $qbData['id'];
								$name = $qbData['firstname']." ".$qbData['lastname'];
								$qbArray[] = [$playerid,$name,"QB"];
							}
							while($rbData = mysqli_fetch_array($active_rb_result)) {
								$playerid = $rbData['id'];
								$name = $rbData['firstname']." ".$rbData['lastname'];
								$rbArray[] = [$playerid,$name,"RB"];
							}
							while($fbData = mysqli_fetch_array($active_fb_result)) {
								$playerid = $fbData['id'];
								$name = $fbData['firstname']." ".$fbData['lastname'];
								$fbArray[] = [$playerid,$name,"FB"];
							}
							while($teData = mysqli_fetch_array($active_te_result)) {
								$playerid = $teData['id'];
								$name = $teData['firstname']." ".$teData['lastname'];
								$teArray[] = [$playerid,$name,"TE"];
							}
							while($wrData = mysqli_fetch_array($active_wr_result)) {
								$playerid = $wrData['id'];
								$name = $wrData['firstname']." ".$wrData['lastname'];
								$wrArray[] = [$playerid,$name,"WR"];
							}
							while($tData = mysqli_fetch_array($active_t_result)) {
								$playerid = $tData['id'];
								$name = $tData['firstname']." ".$tData['lastname'];
								$tArray[] = [$playerid,$name,"T"];
							}
							while($gData = mysqli_fetch_array($active_g_result)) {
								$playerid = $gData['id'];
								$name = $gData['firstname']." ".$gData['lastname'];
								$gArray[] = [$playerid,$name,"G"];
							}
							while($cData = mysqli_fetch_array($active_c_result)) {
								$playerid = $cData['id'];
								$name = $cData['firstname']." ".$cData['lastname'];
								$cArray[] = [$playerid,$name,"C"];
							}
							while($deData = mysqli_fetch_array($active_de_result)) {
								$playerid = $deData['id'];
								$name = $deData['firstname']." ".$deData['lastname'];
								$deArray[] = [$playerid,$name,"DE"];
							}
							while($dtData = mysqli_fetch_array($active_dt_result)) {
								$playerid = $dtData['id'];
								$name = $dtData['firstname']." ".$dtData['lastname'];
								$dtArray[] = [$playerid,$name,"DT"];
							}
							while($lbData = mysqli_fetch_array($active_lb_result)) {
								$playerid = $lbData['id'];
								$name = $lbData['firstname']." ".$lbData['lastname'];
								$lbArray[] = [$playerid,$name,"LB"];
							}
							while($cbData = mysqli_fetch_array($active_cb_result)) {
								$playerid = $cbData['id'];
								$name = $cbData['firstname']." ".$cbData['lastname'];
								$cbArray[] = [$playerid,$name,"CB"];
							}
							while($sData = mysqli_fetch_array($active_s_result)) {
								$playerid = $sData['id'];
								$name = $sData['firstname']." ".$sData['lastname'];
								$sArray[] = [$playerid,$name,"S"];
							}
							while($kData = mysqli_fetch_array($active_k_result)) {
								$playerid = $kData['id'];
								$name = $kData['firstname']." ".$kData['lastname'];
								$kArray[] = [$playerid,$name,"K"];
							}
							while($pData = mysqli_fetch_array($active_p_result)) {
								$playerid = $pData['id'];
								$name = $pData['firstname']." ".$pData['lastname'];
								$pArray[] = [$playerid,$name,"P"];
							}
							
							
							$lineup_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid");
							$lineup = mysqli_fetch_array($lineup_result);
							
							function depthSelect($positions,$slot) { //Reusable function for creating position selects
								global $qbArray,$rbArray,$fbArray,$wrArray,$teArray,$gArray,$cArray,$tArray,$deArray,$dtArray,$lbArray,$cbArray,$sArray,$kArray,$pArray,$lineup;
								$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
								$current = $lineup[$slot];
								$result = mysqli_query($conn,"SELECT firstname,lastname,position FROM player WHERE id=$current");

								if (mysqli_num_rows($result)!=0) {
									$playerData = mysqli_fetch_array($result);
									echo "<option value=\"".$current."\">".$playerData['position']." ".$playerData['firstname']." ".$playerData['lastname']."</option>";
									$playerset = true;
								} else {
									echo "<option value=\"0\" class=\"autooption\">Auto</option>";
									$playerset = false;
								}
								
								foreach ($positions as $position) {
									switch($position) {
									case "QB":
										$posArray = $qbArray;
										break;
									case "RB":	
										$posArray = $rbArray;
										break;
									case "FB":
										$posArray = $fbArray;
										break;
									case "TE":
										$posArray = $teArray;
										break;
									case "WR":
										$posArray = $wrArray;
										break;
									case "T":
										$posArray = $tArray;
										break;
									case "G":
										$posArray = $gArray;
										break;
									case "C":	
										$posArray = $cArray;
										break;
									case "DE":
										$posArray = $deArray;
										break;
									case "DT":
										$posArray = $dtArray;
										break;
									case "LB":
										$posArray = $lbArray;
										break;
									case "CB":
										$posArray = $cbArray;
										break;
									case "S":
										$posArray = $sArray;
										break;
									case "K":
										$posArray = $kArray;
										break;
									case "P":
										$posArray = $pArray;
										break;
									default:
										echo "<option>Something is wrong</option>";
									}
									foreach($posArray as $playerarray) {
										if ($playerarray[0]!=$current) {
											echo "<option value=\"".$playerarray[0]."\">".$playerarray[2]." ".$playerarray[1]."</option>";
										}
									}
								}
								if ($playerset) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
							}
							
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
												depthSelect(["QB"],"QB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="qb2select">
											<?php
												depthSelect(["QB"],"QB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Emergency Quarterback</td>
										<td><select class="form-control playeropt" name="eqbselect">
											<?php
												depthSelect(["QB","RB","FB","TE","WR","T","G","C","T","DE","DT","LB","CB","S","K","P"],"QB_3");
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
												depthSelect(["T","G","C"],"LT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lt2select">
											<?php
												depthSelect(["T","G","C"],"LT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Guard</td>
										<td><select class="form-control playeropt" name="lg1select">
											<?php
												depthSelect(["G","T","C"],"LG_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lg2select">
											<?php
												depthSelect(["G","T","C"],"LG_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Center</td>
										<td><select class="form-control playeropt" name="c1select">
											<?php
												depthSelect(["C","G","T"],"C_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="c2select">
											<?php
												depthSelect(["C","G","T"],"C_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Guard</td>
										<td><select class="form-control playeropt" name="rg1select">
											<?php
												depthSelect(["G","T","C"],"RG_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rg2select">
											<?php
												depthSelect(["G","T","C"],"RG_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Tackle</td>
										<td><select class="form-control playeropt" name="rt1select">
											<?php
												depthSelect(["T","G","C"],"RT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rt2select">
											<?php
												depthSelect(["T","G","C"],"RT_2");
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
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												depthSelect(["FB","TE"],"FB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												depthSelect(["FB","TE"],"FB_2");
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
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control playeropt" name="te2-1select">
											<?php
												depthSelect(["TE"],"TE2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te2-2select">
											<?php
												depthSelect(["TE"],"TE2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #3 (Goal Line only)</td>
										<td><select class="form-control playeropt" name="te3-1select">
											<?php
												depthSelect(["TE"],"TE3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te3-2select">
											<?php
												depthSelect(["TE"],"TE3_2");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												depthSelect(["WR"],"WR3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												depthSelect(["WR"],"WR3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control playeropt" name="wr4-1select">
											<?php
												depthSelect(["WR"],"WR4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr4-2select">
											<?php
												depthSelect(["WR"],"WR4_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #5 (Slot)</td>
										<td><select class="form-control playeropt" name="wr5-1select">
											<?php
												depthSelect(["WR"],"WR5_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr5-2select">
											<?php
												depthSelect(["WR"],"WR5_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-md-2 col-sm-12">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="savegen">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="22">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="22offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup22_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '22'");
						$lineup = mysqli_fetch_array($lineup22_result);
						?>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												depthSelect(["FB","TE"],"FB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												depthSelect(["FB","TE"],"FB_2");
											?>
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
										<td><select class="form-control playeropt" name="te1-1select">
											<?php
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control playeropt" name="te2-1select">
											<?php
												depthSelect(["TE"],"TE2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te2-2select">
											<?php
												depthSelect(["TE"],"TE2_2");
											?>
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
										<td><select class="form-control playeropt" name="wr1-1select">
											<?php
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-md-3 col-lg-2 col-sm-12">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save22">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="21">
						<?php 
						$lineup21_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '21'");
						$lineup = mysqli_fetch_array($lineup21_result);
						?>
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="21offense">
						<div class="col-md-9 col-sm-12">
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												depthSelect(["FB","TE"],"FB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												depthSelect(["FB","TE"],"FB_2");
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
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_1");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save21">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="20">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="20offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup20_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '20'");
						$lineup = mysqli_fetch_array($lineup20_result);
						?>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												depthSelect(["FB","TE"],"FB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												depthSelect(["FB","TE"],"FB_2");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												depthSelect(["WR"],"WR3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												depthSelect(["WR"],"WR3_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save20">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="12">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="12offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup12_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '12'");
						$lineup = mysqli_fetch_array($lineup12_result);
						?>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
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
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control playeropt" name="te2-1select">
											<?php
												depthSelect(["TE"],"TE2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te2-2select">
											<?php
												depthSelect(["TE"],"TE2_2");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save12">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="11">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="11offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup11_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '11'");
						$lineup = mysqli_fetch_array($lineup11_result);
						?>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
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
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_2");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												depthSelect(["WR"],"WR3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												depthSelect(["WR"],"WR3_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save11">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="10">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="10offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup10_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '10'");
						$lineup = mysqli_fetch_array($lineup10_result);
						?>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
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
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												depthSelect(["WR"],"WR3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												depthSelect(["WR"],"WR3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control playeropt" name="wr4-1select">
											<?php
												depthSelect(["WR"],"WR4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr4-2select">
											<?php
												depthSelect(["WR"],"WR4_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save10">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="00">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="00offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup00_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '00'");
						$lineup = mysqli_fetch_array($lineup00_result);
						?>
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
										<td><select class="form-control playeropt" name="wr1-1select">
											<?php
												depthSelect(["WR"],"WR1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												depthSelect(["WR"],"WR1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #2 (Flanker)</td>
										<td><select class="form-control playeropt" name="wr2-1select">
											<?php
												depthSelect(["WR"],"WR2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr2-2select">
											<?php
												depthSelect(["WR"],"WR2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #3 (Slot)</td>
										<td><select class="form-control playeropt" name="wr3-1select">
											<?php
												depthSelect(["WR"],"WR3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr3-2select">
											<?php
												depthSelect(["WR"],"WR3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #4 (Slot)</td>
										<td><select class="form-control playeropt" name="wr4-1select">
											<?php
												depthSelect(["WR"],"WR4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr4-2select">
											<?php
												depthSelect(["WR"],"WR4_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Wide Receiver #5 (Slot)</td>
										<td><select class="form-control playeropt" name="wr5-1select">
											<?php
												depthSelect(["WR"],"WR5_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr5-2select">
											<?php
												depthSelect(["WR"],"WR5_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save00">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="23">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="23offense">
						<div class="col-md-9 col-sm-12">
						<?php 
						$lineup23_result = mysqli_query($conn,"SELECT * FROM `offlineup` WHERE team=$teamid AND personnel = '23'");
						$lineup = mysqli_fetch_array($lineup23_result);
						?>
						<h4>Goal Line Offense</h4>
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
										<td><select class="form-control playeropt" name="hb1select">
											<?php
												depthSelect(["RB","FB"],"HB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												depthSelect(["RB","FB"],"HB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Fullback</td>
										<td><select class="form-control playeropt" name="fb1select">
											<?php
												depthSelect(["FB","TE"],"FB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												depthSelect(["FB","TE"],"FB_2");
											?>
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
										<td><select class="form-control playeropt" name="te1-1select">
											<?php
												depthSelect(["TE"],"TE1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												depthSelect(["TE"],"TE1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #2</td>
										<td><select class="form-control playeropt" name="te2-1select">
											<?php
												depthSelect(["TE"],"TE2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te2-2select">
											<?php
												depthSelect(["TE"],"TE2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Tight End #3</td>
										<td><select class="form-control playeropt" name="te3-1select">
											<?php
												depthSelect(["TE"],"TE3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te3-2select">
											<?php
												depthSelect(["TE"],"TE3_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save23">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="defense">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="gendefense">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = 'all'");
						$lineup = mysqli_fetch_array($deflineup_result);
						
						?>
						<h4>Defense</h4>
						<p>This is the general defensive depth chart. Changes here will affect all packages.</p>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Nose Tackle</td>
										<td><select class=\"form-control playeropt\" name=\"nt1select\">";
										depthSelect(["DT","DE"],"NT_1");
										echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"nt2select\">";
										depthSelect(["DT","DE"],"NT_2");
										echo "</select></td>
									</tr>";
									} ?>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Jack Linebacker (Outside LB, only in 3-4)</td>
										<td><select class=\"form-control playeropt\" name=\"jlb1select\">";
											
										depthSelect(["LB","DE","S"],"JLB_1");
									echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"jlb2select\">";
										
										depthSelect(["LB","DE","S"],"JLB_2");
									echo "</select></td>
									</tr>
									<tr>
										<td>Extra Linebacker (Outside LB, only in 3-5-3)</td>
										<td><select class=\"form-control playeropt\" name=\"xlb1select\">";
										
										depthSelect(["LB","DE","S"],"XLB_1");
										
									echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"xlb2select\">";
										
										depthSelect(["LB","DE","S"],"XLB_2");
										
									echo "</select></td>
									</tr>";
									} ?>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");
											
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												depthSelect(["CB","S"],"CB4_1");
											
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												depthSelect(["CB","S"],"CB4_2");
											
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Cornerback #5</td>
										<td><select class=\"form-control playeropt\" name=\"cb5-1select\">";
											depthSelect(["CB","S"],"CB5_1");
										echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"cb5-2select\">";
											depthSelect(["CB","S"],"CB5_2");
										echo "</select></td>
									</tr>
									";
									} ?>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												depthSelect(["S","CB"],"SS2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												depthSelect(["S","CB"],"SS2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												depthSelect(["S","CB"],"RS_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												depthSelect(["S","CB"],"RS_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="savegendef">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="434">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="434">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup434_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '434'");
						$lineup = mysqli_fetch_array($deflineup434_result);
						
						?>
						<h4>4-3-4 Base Defense</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save434">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="425">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="425">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup425_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '425'");
						$lineup = mysqli_fetch_array($deflineup425_result);
						
						?>
						<h4>4-2-5 Base Defense</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");

											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												depthSelect(["S","CB"],"SS2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												depthSelect(["S","CB"],"SS2_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save425">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="344">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="344">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup344_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '344'");
						$lineup = mysqli_fetch_array($deflineup344_result);
						
						?>
						<h4>3-4-4 Base Defense</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");

											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
											depthSelect(["DT","DE"],"NT_1");
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
											depthSelect(["DT","DE"],"NT_2");

										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");

											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save344">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="335">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="335">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup335_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '335'");
						$lineup = mysqli_fetch_array($deflineup335_result);
						
						?>
						<h4>3-3-5 Base Defense</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										depthSelect(["DT","DE"],"NT_1");

										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										depthSelect(["DT","DE"],"NT_2");

										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");

											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												depthSelect(["S","CB"],"SS2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												depthSelect(["S","CB"],"SS2_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save335">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="443">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="443">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup443_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '443'");
						$lineup = mysqli_fetch_array($deflineup443_result);
						
						?>
						<h4>4-4-3</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");

											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save443">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="353">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="353">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup353_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '353'");
						$lineup = mysqli_fetch_array($deflineup353_result);
						
						?>
						<h4>3-5-3</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
											depthSelect(["DT","DE"],"NT_1");
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
											depthSelect(["DT","DE"],"NT_2");
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												depthSelect(["LB","DE","S"],"JLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Extra Linebacker</td>
										<td><select class="form-control playeropt" name="xlb1select">
											<?php
												depthSelect(["LB","DE","S"],"XLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="xlb2select">
											<?php
												depthSelect(["LB","DE","S"],"XLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save353">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="425n">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="425n">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup425n_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '425n'");
						$lineup = mysqli_fetch_array($deflineup425n_result);
						
						?>
						<h4>4-2-5 Nickel</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save425n">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="335n">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="335n">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup335n_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '335n'");
						$lineup = mysqli_fetch_array($deflineup335n_result);
						
						?>
						<h4>3-3-5 Nickel</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
											depthSelect(["DT","DE"],"NT_1");
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
											depthSelect(["DT","DE"],"NT_2");
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
									<tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save335n">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="416">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="416">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup416_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '416'");
						$lineup = mysqli_fetch_array($deflineup416_result);
						
						?>
						<h4>4-1-6 Dime</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												depthSelect(["CB","S"],"CB4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												depthSelect(["CB","S"],"CB4_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save416">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="326">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="326">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup326_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '326'");
						$lineup = mysqli_fetch_array($deflineup326_result);
						
						?>
						<h4>3-2-6 Dime</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
											depthSelect(["DT","DE"],"NT_1");
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
											depthSelect(["DT","DE"],"NT_2");
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												depthSelect(["CB","S"],"CB4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												depthSelect(["CB","S"],"CB4_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save326">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="317">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="317">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup317_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '317'");
						$lineup = mysqli_fetch_array($deflineup317_result);
						
						?>
						<h4>3-1-7 Quarter</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
											depthSelect(["DT","DE"],"NT_1");
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
											depthSelect(["DT","DE"],"NT_2");
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												depthSelect(["CB","S"],"CB2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												depthSelect(["CB","S"],"CB2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												depthSelect(["CB","S"],"CB3_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												depthSelect(["CB","S"],"CB3_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												depthSelect(["CB","S"],"CB4_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												depthSelect(["CB","S"],"CB4_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #5</td>
										<td><select class="form-control playeropt" name="cb5-1select">
											<?php
												depthSelect(["CB","S"],"CB5_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb5-2select">
											<?php
												depthSelect(["CB","S"],"CB5_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save317">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="623">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="623">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup623_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '623'");
						$lineup = mysqli_fetch_array($deflineup623_result);
						
						?>
						<h4>6-2-3 Goal Line</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Cornerback #1</td>
										<td><select class="form-control playeropt" name="cb1-1select">
											<?php
												depthSelect(["CB","S"],"CB1_1");

												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												depthSelect(["CB","S"],"CB1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												depthSelect(["S","CB"],"SS2_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												depthSelect(["S","CB"],"SS2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												depthSelect(["S","CB"],"RS_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												depthSelect(["S","CB"],"RS_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save623">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="632">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="632">
						<div class="col-md-9 col-sm-12">
						<?php
						$deflineup632_result = mysqli_query($conn,"SELECT * FROM `deflineup` WHERE team=$teamid AND personnel = '632'");
						$lineup = mysqli_fetch_array($deflineup632_result);
						
						?>
						<h4>6-3-2 Goal Line</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
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
										<td>Left Defensive End</td>
										<td><select class="form-control playeropt" name="lde1select">
											<?php
												depthSelect(["DE","DT"],"LDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												depthSelect(["DE","DT"],"LDE_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												depthSelect(["DT","DE"],"LDT_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												depthSelect(["DT","DE"],"LDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												depthSelect(["DT","DE"],"RDT_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												depthSelect(["DT","DE"],"RDT_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												depthSelect(["DE","DT"],"RDE_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												depthSelect(["DE","DT"],"RDE_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="lbrow">
								<h4>Linebackers</h4>
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
										<td>Weakside Linebacker</td>
										<td><select class="form-control playeropt" name="wlb1select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												depthSelect(["LB","DE","S"],"WLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												depthSelect(["LB","DE","S"],"MLB_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												depthSelect(["LB","DE","S"],"SLB_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="dbrow">
								<h4>Defensive Backs</h4>
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
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												depthSelect(["S","CB"],"FS_1");
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												depthSelect(["S","CB"],"FS_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												depthSelect(["S","CB"],"SS1_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												depthSelect(["S","CB"],"SS1_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												depthSelect(["S","CB"],"SS2_1");

											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												depthSelect(["S","CB"],"SS2_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												depthSelect(["S","CB"],"RS_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												depthSelect(["S","CB"],"RS_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="save632">Save Depth Chart</button>
								</div>
							</div>
							</form>
						</div>
						<div class="tab-pane fade" id="steams">
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>">
						<div class="col-md-9 col-sm-12">
						<?php
						$stlineup_result = mysqli_query($conn,"SELECT * FROM `stlineup` WHERE team=$teamid");
						$lineup = mysqli_fetch_array($stlineup_result);
						
						?>
						<h4>Special Teams</h4>
							
							<div class="row well playerrow" id="krow">
								<h4>Kicker</h4>
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
										<td>Kicker</td>
										<td><select class="form-control playeropt" name="k1select">
											<?php
												depthSelect(["K","P"],"K_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="k2select">
											<?php
												depthSelect(["K","P"],"K_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Punter</td>
										<td><select class="form-control playeropt" name="p1select">
											<?php
												depthSelect(["P","K"],"P_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="p2select">
											<?php
												depthSelect(["P","K"],"P_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="returnrow">
								<h4>Returners</h4>
								<table class="table borderless borderless col-md-12">
								<thead>
									<tr>
										<th width="20%">
										</th>
										<th width="40%"></th>
										<th width="40%"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Kick Returner</td>
										<td><select class="form-control playeropt" name="kr1select">
											<?php
												depthSelect(["WR","RB","CB","S"],"KR_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="kr2select">
											<?php
												depthSelect(["WR","RB","CB","S"],"KR_2");
											?>
										</select></td>
									</tr>
									<tr>
										<td>Punt Returner</td>
										<td><select class="form-control playeropt" name="pr1select">
											<?php
												depthSelect(["WR","RB","CB","S"],"PR_1");
											?>
										</select></td>
										<td><select class="form-control playeropt" name="pr2select">
											<?php
												depthSelect(["WR","RB","CB","S"],"PR_2");
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
							<div class="col-sm-12 col-md-3 col-lg-2">
								<div class="playerbox">
									<b>Selected Player:</b><br>
									<b><span class="playername"></span></b>
									<p><span class="playerposition"></span></p>
									<p><b>Rating: <span class="playerrating"></span></b></p>
									<p>Height: <span class="height"></span>, Weight: <span class="weight"></span></p>
									<p><span class="playerhealth"></span></p>
									<button class="btn btn-success save" type="submit" name="savesteams">Save Depth Chart</button>
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
      </div>
	  <div class="row" id="neterror">
		<span><b>No internet connection detected!</b> If you click "Save" right now, changes may not be saved.</span>
	  </div>
    </div>
  </body>
</html>