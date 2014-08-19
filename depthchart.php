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
		
		$lineupquery = "UPDATE `stlineup` SET K_1=$K_1,K_2=$K_2,P_1=$P_1,P_2=$P_2 WHERE team=$teamid";
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
	<script>document.write('<style>.playerbox { display: none; }</style>');</script>
	<script>
	$( document ).ready(function() {
		$('a[href="#<?php echo $tab;?>"]').tab('show');
		$('#packages').tendina();
		
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
			
			$(".playername").html("<a href=\"player.php?playerid="+value+"\">"+name+"</a>");
			$(".playerposition").html(position);
			$(".playerhealth").html(health);
			$(".playerrating").html(rating);
			$(".height").html(height);
			$(".weight").html(weight);
			
			$('.playerbox').fadeIn();
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
					<a href="#offense" data-toggle="tab">Offense</a>
					<ul>
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
					<a href="#defense" data-toggle="tab">Defense</a>
					<ul>
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
												
												if (mysqli_num_rows($qb1_result)!=0) {
													$qb1Data = mysqli_fetch_array($qb1_result);
													echo "<option value=\"".$currentqb1."\">".$qb1Data['firstname']." ".$qb1Data['lastname']."</option>";
													$qb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$qb1set = false;
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
												
												if (mysqli_num_rows($qb2_result)!=0) {
													$qb2Data = mysqli_fetch_array($qb2_result);
													echo "<option value=\"".$currentqb2."\">".$qb2Data['firstname']." ".$qb2Data['lastname']."</option>";
													$qb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$qb2set = false;
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
												
												if (mysqli_num_rows($qb3_result)!=0) {
													$qb3Data = mysqli_fetch_array($qb3_result);
													echo "<option value=\"".$currentqb3."\">".$qb3Data['firstname']." ".$qb3Data['lastname']."</option>";
													$qb3set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$qb3set = false;
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
												
												if (mysqli_num_rows($lt1_result)!=0) {
													$lt1Data = mysqli_fetch_array($lt1_result);
													echo "<option value=\"".$currentlt1."\">".$lt1Data['firstname']." ".$lt1Data['lastname']."</option>";
													$lt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$lt1set = false;
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
												
												if (mysqli_num_rows($lt1_result)!=0) {
													$lt2Data = mysqli_fetch_array($lt2_result);
													echo "<option value=\"".$currentlt2."\">".$lt2Data['firstname']." ".$lt2Data['lastname']."</option>";
													$lt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$lt2set = false;
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
												
												if (mysqli_num_rows($lg1_result)!=0) {
													$lg1Data = mysqli_fetch_array($lg1_result);
													echo "<option value=\"".$currentlg1."\">".$lg1Data['firstname']." ".$lg1Data['lastname']."</option>";
													$lg1set = true;
												} else {
													 echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													 $lg1set = false;
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
												
												if (mysqli_num_rows($lg1_result)!=0) {
													$lg2Data = mysqli_fetch_array($lg2_result);
													echo "<option value=\"".$currentlg2."\">".$lg2Data['firstname']." ".$lg2Data['lastname']."</option>";
													$lg2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lg2set = false;
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
												
												if (mysqli_num_rows($c1_result)!=0) {
													$c1Data = mysqli_fetch_array($c1_result);
													echo "<option value=\"".$currentc1."\">".$c1Data['firstname']." ".$c1Data['lastname']."</option>";
													$c1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$c1set = false;
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
												
												if (mysqli_num_rows($c2_result)!=0) {
													$c2Data = mysqli_fetch_array($c2_result);
													echo "<option value=\"".$currentc2."\">".$c2Data['firstname']." ".$c2Data['lastname']."</option>";
													$c2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$c2set = false;
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
												
												if (mysqli_num_rows($rg1_result)!=0) {
													$rg1Data = mysqli_fetch_array($rg1_result);
													echo "<option value=\"".$currentrg1."\">".$rg1Data['firstname']." ".$rg1Data['lastname']."</option>";
													$rg1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rg1set = false;
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
												
												if (mysqli_num_rows($rg2_result)!=0) {
													$rg2Data = mysqli_fetch_array($rg2_result);
													echo "<option value=\"".$currentrg2."\">".$rg2Data['firstname']." ".$rg2Data['lastname']."</option>";
													$rg2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rg2set = false;
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
												
												if (mysqli_num_rows($rt1_result)!=0) {
													$rt1Data = mysqli_fetch_array($rt1_result);
													echo "<option value=\"".$currentrt1."\">".$rt1Data['firstname']." ".$rt1Data['lastname']."</option>";
													$rt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rt1set = false;
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
												
												if (mysqli_num_rows($rt2_result)!=0) {
													$rt2Data = mysqli_fetch_array($rt2_result);
													echo "<option value=\"".$currentrt2."\">".$rt2Data['firstname']." ".$rt2Data['lastname']."</option>";
													$rt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rt2set = false;
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
												
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
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
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set = false;
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
												
												if (mysqli_num_rows($fb1_result)!=0) {
													$fb1Data = mysqli_fetch_array($fb1_result);
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb1set = false;
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
												
												if (mysqli_num_rows($fb2_result)!=0) {
													$fb2Data = mysqli_fetch_array($fb2_result);
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb2set = false;
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
												
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
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
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												
												if (mysqli_num_rows($te21_result)!=0) {
													$te21Data = mysqli_fetch_array($te21_result);
													echo "<option value=\"".$currentte21."\">".$te21Data['firstname']." ".$te21Data['lastname']."</option>";
													$te21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te21set = false;
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
												
												if (mysqli_num_rows($te22_result)!=0) {
													$te22Data = mysqli_fetch_array($te22_result);
													echo "<option value=\"".$currentte22."\">".$te22Data['firstname']." ".$te22Data['lastname']."</option>";
													$te22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te22set = false;
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
									<tr>
										<td>Tight End #3 (Goal Line only)</td>
										<td><select class="form-control playeropt" name="te3-1select">
											<?php
												$currentte31 = $lineup['TE3_1'];
												$te31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte31");
												
												if (mysqli_num_rows($te31_result)!=0) {
													$te31Data = mysqli_fetch_array($te31_result);
													echo "<option value=\"".$currentte31."\">".$te31Data['firstname']." ".$te31Data['lastname']."</option>";
													$te31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te31set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte31) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te3-2select">
											<?php
												$currentte32 = $lineup['TE3_2'];
												$te32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte32");
												
												if (mysqli_num_rows($te32_result)!=0) {
													$te32Data = mysqli_fetch_array($te32_result);
													echo "<option value=\"".$currentte32."\">".$te32Data['firstname']." ".$te32Data['lastname']."</option>";
													$te32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te32set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte32) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
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
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr31Data = mysqli_fetch_array($wr31_result);
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr31set = false;
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
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr32Data = mysqli_fetch_array($wr32_result);
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr32set = false;
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
												
												if (mysqli_num_rows($wr41_result)!=0) {
													$wr41Data = mysqli_fetch_array($wr41_result);
													echo "<option value=\"".$currentwr41."\">".$wr41Data['firstname']." ".$wr41Data['lastname']."</option>";
													$wr41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr41set = false;
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
												
												if (mysqli_num_rows($wr42_result)!=0) {
													$wr42Data = mysqli_fetch_array($wr42_result);
													echo "<option value=\"".$currentwr42."\">".$wr42Data['firstname']." ".$wr42Data['lastname']."</option>";
													$wr42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr42set = false;
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
												
												if (mysqli_num_rows($wr51_result)!=0) {
													$wr51Data = mysqli_fetch_array($wr51_result);
													echo "<option value=\"".$currentwr51."\">".$wr51Data['firstname']." ".$wr51Data['lastname']."</option>";
													$wr51set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr51set = false;
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
												
												if (mysqli_num_rows($wr52_result)!=0) {
													$wr52Data = mysqli_fetch_array($wr52_result);
													echo "<option value=\"".$currentwr52."\">".$wr52Data['firstname']." ".$wr52Data['lastname']."</option>";
													$wr52set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr52set = false;
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
						$lineup22 = mysqli_fetch_array($lineup22_result);
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
												$currenthb1 = $lineup22['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup22['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentfb1 = $lineup22['FB_1'];
												$fb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb1");
												
												if (mysqli_num_rows($fb1_result)!=0) {
													$fb1Data = mysqli_fetch_array($fb1_result);
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb1set = false;
												}
												foreach($fbArray as $playerarray) {
													if ($playerarray[0]!=$currentfb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												$currentfb2 = $lineup22['FB_2'];
												$fb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb2");
												
												if (mysqli_num_rows($fb2_result)!=0) {
													$fb2Data = mysqli_fetch_array($fb2_result);
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb2set = false;
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
												$currentte11 = $lineup22['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup22['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												$currentte21 = $lineup22['TE2_1'];
												$te21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte21");
												
												if (mysqli_num_rows($te21_result)!=0) {
													$te21Data = mysqli_fetch_array($te21_result);
													echo "<option value=\"".$currentte21."\">".$te21Data['firstname']." ".$te21Data['lastname']."</option>";
													$te21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te21set = false;
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
												$currentte22 = $lineup22['TE2_2'];
												$te22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte22");
												
												if (mysqli_num_rows($te22_result)!=0) {
													$te22Data = mysqli_fetch_array($te22_result);
													echo "<option value=\"".$currentte22."\">".$te22Data['firstname']." ".$te22Data['lastname']."</option>";
													$te22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te22set = false;
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
												$currentwr11 = $lineup22['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup22['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
						$lineup21 = mysqli_fetch_array($lineup21_result);
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
												$currenthb1 = $lineup21['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup21['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentfb1 = $lineup21['FB_1'];
												$fb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb1");
												
												if (mysqli_num_rows($fb1_result)!=0) {
													$fb1Data = mysqli_fetch_array($fb1_result);
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb1set = false;
												}
												foreach($fbArray as $playerarray) {
													if ($playerarray[0]!=$currentfb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												$currentfb2 = $lineup21['FB_2'];
												$fb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb2");
												
												if (mysqli_num_rows($fb2_result)!=0) {
													$fb2Data = mysqli_fetch_array($fb2_result);
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb2set = false;
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
												$currentte11 = $lineup21['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup21['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												$currentwr11 = $lineup21['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup21['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup21['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup21['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
						$lineup20 = mysqli_fetch_array($lineup20_result);
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
												$currenthb1 = $lineup20['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup20['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentfb1 = $lineup20['FB_1'];
												$fb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb1");
												
												if (mysqli_num_rows($fb1_result)!=0) {
													$fb1Data = mysqli_fetch_array($fb1_result);
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb1set = false;
												}
												foreach($fbArray as $playerarray) {
													if ($playerarray[0]!=$currentfb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												$currentfb2 = $lineup20['FB_2'];
												$fb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb2");
												
												if (mysqli_num_rows($fb2_result)!=0) {
													$fb2Data = mysqli_fetch_array($fb2_result);
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb2set = false;
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
												$currentwr11 = $lineup20['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup20['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup20['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup20['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
												$currentwr31 = $lineup20['WR3_1'];
												$wr31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr31");
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr31Data = mysqli_fetch_array($wr31_result);
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr31set = false;
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
												$currentwr32 = $lineup20['WR3_2'];
												$wr32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr32");
												if (mysqli_num_rows($wr32_result)!=0) {
													$wr32Data = mysqli_fetch_array($wr32_result);
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr32set = false;
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
						$lineup12 = mysqli_fetch_array($lineup12_result);
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
												$currenthb1 = $lineup12['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup12['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentte11 = $lineup12['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup12['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												$currentte21 = $lineup12['TE2_1'];
												$te21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte21");
												
												if (mysqli_num_rows($te21_result)!=0) {
													$te21Data = mysqli_fetch_array($te21_result);
													echo "<option value=\"".$currentte21."\">".$te21Data['firstname']." ".$te21Data['lastname']."</option>";
													$te21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te21set = false;
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
												$currentte22 = $lineup12['TE2_2'];
												$te22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte22");
												
												if (mysqli_num_rows($te22_result)!=0) {
													$te22Data = mysqli_fetch_array($te22_result);
													echo "<option value=\"".$currentte22."\">".$te22Data['firstname']." ".$te22Data['lastname']."</option>";
													$te22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te22set = false;
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
												$currentwr11 = $lineup12['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup12['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup12['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup12['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
						$lineup11 = mysqli_fetch_array($lineup11_result);
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
												$currenthb1 = $lineup11['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup11['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentte11 = $lineup11['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup11['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												$currentwr11 = $lineup11['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup11['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup11['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup11['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
												$currentwr31 = $lineup11['WR3_1'];
												$wr31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr31");
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr31Data = mysqli_fetch_array($wr31_result);
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr31set = false;
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
												$currentwr32 = $lineup11['WR3_2'];
												$wr32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr32");
												if (mysqli_num_rows($wr32_result)!=0) {
													$wr32Data = mysqli_fetch_array($wr32_result);
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr32set = false;
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
						$lineup10 = mysqli_fetch_array($lineup10_result);
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
												$currenthb1 = $lineup10['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup10['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentwr11 = $lineup10['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup10['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup10['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup10['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
												$currentwr31 = $lineup10['WR3_1'];
												$wr31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr31");
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr31Data = mysqli_fetch_array($wr31_result);
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr31set = false;
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
												$currentwr32 = $lineup10['WR3_2'];
												$wr32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr32");
												if (mysqli_num_rows($wr32_result)!=0) {
													$wr32Data = mysqli_fetch_array($wr32_result);
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr32set = false;
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
												$currentwr41 = $lineup10['WR4_1'];
												$wr41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr41");
												
												if (mysqli_num_rows($wr41_result)!=0) {
													$wr41Data = mysqli_fetch_array($wr41_result);
													echo "<option value=\"".$currentwr41."\">".$wr41Data['firstname']." ".$wr41Data['lastname']."</option>";
													$wr41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr41set = false;
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
												$currentwr42 = $lineup10['WR4_2'];
												$wr42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr42");
												
												if (mysqli_num_rows($wr42_result)!=0) {
													$wr42Data = mysqli_fetch_array($wr42_result);
													echo "<option value=\"".$currentwr42."\">".$wr42Data['firstname']." ".$wr42Data['lastname']."</option>";
													$wr42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr42set = false;
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
						$lineup00 = mysqli_fetch_array($lineup00_result);
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
												$currentwr11 = $lineup00['WR1_1'];
												$wr11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr11");
												
												if (mysqli_num_rows($wr11_result)!=0) {
													$wr11Data = mysqli_fetch_array($wr11_result);
													echo "<option value=\"".$currentwr11."\">".$wr11Data['firstname']." ".$wr11Data['lastname']."</option>";
													$wr11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr11set = false;
												}
												foreach($wrArray as $playerarray) {
													if ($playerarray[0]!=$currentwr11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wr11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wr1-2select">
											<?php
												$currentwr12 = $lineup00['WR1_2'];
												$wr12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr12");
												
												if (mysqli_num_rows($wr12_result)!=0) {
													$wr12Data = mysqli_fetch_array($wr12_result);
													echo "<option value=\"".$currentwr12."\">".$wr12Data['firstname']." ".$wr12Data['lastname']."</option>";
													$wr12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr12set = false;
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
												$currentwr21 = $lineup00['WR2_1'];
												$wr21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr21");
												
												if (mysqli_num_rows($wr21_result)!=0) {
													$wr21Data = mysqli_fetch_array($wr21_result);
													echo "<option value=\"".$currentwr21."\">".$wr21Data['firstname']." ".$wr21Data['lastname']."</option>";
													$wr21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr21set = false;
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
												$currentwr22 = $lineup00['WR2_2'];
												$wr22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr22");
												
												if (mysqli_num_rows($wr22_result)!=0) {
													$wr22Data = mysqli_fetch_array($wr22_result);
													echo "<option value=\"".$currentwr22."\">".$wr22Data['firstname']." ".$wr22Data['lastname']."</option>";
													$wr22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr22set = false;
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
												$currentwr31 = $lineup00['WR3_1'];
												$wr31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr31");
												
												if (mysqli_num_rows($wr31_result)!=0) {
													$wr31Data = mysqli_fetch_array($wr31_result);
													echo "<option value=\"".$currentwr31."\">".$wr31Data['firstname']." ".$wr31Data['lastname']."</option>";
													$wr31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr31set = false;
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
												$currentwr32 = $lineup00['WR3_2'];
												$wr32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr32");
												if (mysqli_num_rows($wr32_result)!=0) {
													$wr32Data = mysqli_fetch_array($wr32_result);
													echo "<option value=\"".$currentwr32."\">".$wr32Data['firstname']." ".$wr32Data['lastname']."</option>";
													$wr32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr32set = false;
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
												$currentwr41 = $lineup00['WR4_1'];
												$wr41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr41");
												
												if (mysqli_num_rows($wr41_result)!=0) {
													$wr41Data = mysqli_fetch_array($wr41_result);
													echo "<option value=\"".$currentwr41."\">".$wr41Data['firstname']." ".$wr41Data['lastname']."</option>";
													$wr41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr41set = false;
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
												$currentwr42 = $lineup00['WR4_2'];
												$wr42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr42");
												
												if (mysqli_num_rows($wr42_result)!=0) {
													$wr42Data = mysqli_fetch_array($wr42_result);
													echo "<option value=\"".$currentwr42."\">".$wr42Data['firstname']." ".$wr42Data['lastname']."</option>";
													$wr42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr42set = false;
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
												$currentwr51 = $lineup00['WR5_1'];
												$wr51_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr51");
												
												if (mysqli_num_rows($wr51_result)!=0) {
													$wr51Data = mysqli_fetch_array($wr51_result);
													echo "<option value=\"".$currentwr51."\">".$wr51Data['firstname']." ".$wr51Data['lastname']."</option>";
													$wr51set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr51set = false;
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
												$currentwr52 = $lineup00['WR5_2'];
												$wr52_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentwr52");
												
												if (mysqli_num_rows($wr52_result)!=0) {
													$wr52Data = mysqli_fetch_array($wr52_result);
													echo "<option value=\"".$currentwr52."\">".$wr52Data['firstname']." ".$wr52Data['lastname']."</option>";
													$wr52set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wr52set = false;
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
						$lineup23 = mysqli_fetch_array($lineup23_result);
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
												$currenthb1 = $lineup23['HB_1'];
												$hb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb1");
												if (mysqli_num_rows($hb1_result)!=0) {
													$hb1Data = mysqli_fetch_array($hb1_result);
													echo "<option value=\"".$currenthb1."\">".$hb1Data['firstname']." ".$hb1Data['lastname']."</option>";
													$hb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb1set = false;
												}
												foreach($hbArray as $playerarray) {
													if ($playerarray[0]!=$currenthb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($hb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="hb2select">
											<?php
												$currenthb2 = $lineup23['HB_2'];
												$hb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currenthb2");
												
												if (mysqli_num_rows($hb2_result)!=0) {
													$hb2Data = mysqli_fetch_array($hb2_result);
													echo "<option value=\"".$currenthb2."\">".$hb2Data['firstname']." ".$hb2Data['lastname']."</option>";
													$hb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$hb2set= false;
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
												$currentfb1 = $lineup23['FB_1'];
												$fb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb1");
												
												if (mysqli_num_rows($fb1_result)!=0) {
													$fb1Data = mysqli_fetch_array($fb1_result);
													echo "<option value=\"".$currentfb1."\">".$fb1Data['firstname']." ".$fb1Data['lastname']."</option>";
													$fb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb1set = false;
												}
												foreach($fbArray as $playerarray) {
													if ($playerarray[0]!=$currentfb1) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fb2select">
											<?php
												$currentfb2 = $lineup23['FB_2'];
												$fb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentfb2");
												
												if (mysqli_num_rows($fb2_result)!=0) {
													$fb2Data = mysqli_fetch_array($fb2_result);
													echo "<option value=\"".$currentfb2."\">".$fb2Data['firstname']." ".$fb2Data['lastname']."</option>";
													$fb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fb2set = false;
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
												$currentte11 = $lineup23['TE1_1'];
												$te11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte11");
												if (mysqli_num_rows($te11_result)!=0) {
													$te11Data = mysqli_fetch_array($te11_result);
													echo "<option value=\"".$currentte11."\">".$te11Data['firstname']." ".$te11Data['lastname']."</option>";
													$te11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te11set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte11) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te1-2select">
											<?php
												$currentte12 = $lineup23['TE1_2'];
												$te12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte12");
												
												if (mysqli_num_rows($te12_result)!=0) {
													$te12Data = mysqli_fetch_array($te12_result);
													echo "<option value=\"".$currentte12."\">".$te12Data['firstname']." ".$te12Data['lastname']."</option>";
													$te12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te12set = false;
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
												$currentte21 = $lineup23['TE2_1'];
												$te21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte21");
												
												if (mysqli_num_rows($te21_result)!=0) {
													$te21Data = mysqli_fetch_array($te21_result);
													echo "<option value=\"".$currentte21."\">".$te21Data['firstname']." ".$te21Data['lastname']."</option>";
													$te21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te21set = false;
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
												$currentte22 = $lineup23['TE2_2'];
												$te22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte22");
												
												if (mysqli_num_rows($te22_result)!=0) {
													$te22Data = mysqli_fetch_array($te22_result);
													echo "<option value=\"".$currentte22."\">".$te22Data['firstname']." ".$te22Data['lastname']."</option>";
													$te22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te22set = false;
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
									<tr>
										<td>Tight End #3</td>
										<td><select class="form-control playeropt" name="te3-1select">
											<?php
												$currentte31 = $lineup23['TE3_1'];
												$te31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte31");
												
												if (mysqli_num_rows($te31_result)!=0) {
													$te31Data = mysqli_fetch_array($te31_result);
													echo "<option value=\"".$currentte31."\">".$te31Data['firstname']." ".$te31Data['lastname']."</option>";
													$te31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te31set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte31) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="te3-2select">
											<?php
												$currentte32 = $lineup23['TE3_2'];
												$te32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$currentte32");
												
												if (mysqli_num_rows($te32_result)!=0) {
													$te32Data = mysqli_fetch_array($te32_result);
													echo "<option value=\"".$currentte32."\">".$te32Data['firstname']." ".$te32Data['lastname']."</option>";
													$te32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$te32set = false;
												}
												foreach($teArray as $playerarray) {
													if ($playerarray[0]!=$currentte32) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($te32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup = mysqli_fetch_array($deflineup_result);
						
						?>
						<h4>Defense</h4>
							<div class="row well playerrow" id="dlrow">
								<h4>Defensive Line</h4>
								<p>This is the general defensive depth chart. Changes here will affect all packages.</p>
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
												$current = $deflineup['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$lde1set = false;
												}
												while($deData = mysqli_fetch_array($active_de_result)) {
													$playerid = $deData['id'];
													$name = $deData['firstname']." ".$deData['lastname'];
													$deArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												while($dtData = mysqli_fetch_array($active_dt_result)) {
													$playerid = $dtData['id'];
													$name = $dtData['firstname']." ".$dtData['lastname'];
													$dtArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Nose Tackle</td>
										<td><select class=\"form-control playeropt\" name=\"nt1select\">";
										
										$current = $deflineup['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											
										echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"nt2select\">";
										
										$current = $deflineup['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										
										echo "</select></td>
									</tr>";
									} ?>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$wlb1set = false;
												}
												while($lbData = mysqli_fetch_array($active_lb_result)) {
													$playerid = $lbData['id'];
													$name = $lbData['firstname']." ".$lbData['lastname'];
													$lbArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Jack Linebacker (Outside LB, only in 3-4)</td>
										<td><select class=\"form-control playeropt\" name=\"jlb1select\">";
											
										$current = $deflineup['JLB_1'];
										$jlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($jlb1_result)!=0) {
											$jlb1Data = mysqli_fetch_array($jlb1_result);
											echo "<option value=\"".$current."\">".$jlb1Data['firstname']." ".$jlb1Data['lastname']."</option>";
											$jlb1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$jlb1set = false;
										}
										foreach($lbArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($jlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
									echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"jlb2select\">";
										
										$current = $deflineup['JLB_2'];
										$jlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($jlb2_result)!=0) {
											$jlb2Data = mysqli_fetch_array($jlb2_result);
											echo "<option value=\"".$current."\">".$jlb2Data['firstname']." ".$jlb2Data['lastname']."</option>";
											$jlb2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$jlb2set = false;
										}
										foreach($lbArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($jlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
									echo "</select></td>
									</tr>
									<tr>
										<td>Extra Linebacker (Outside LB, only in 3-5-3)</td>
										<td><select class=\"form-control playeropt\" name=\"xlb1select\">";
										
										$current = $deflineup['XLB_1'];
										$xlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($xlb1_result)!=0) {
											$xlb1Data = mysqli_fetch_array($xlb1_result);
											echo "<option value=\"".$current."\">".$xlb1Data['firstname']." ".$xlb1Data['lastname']."</option>";
											$xlb1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$xlb1set = false;
										}
										foreach($lbArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($xlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										
									echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"xlb2select\">";
										
										$current = $deflineup['XLB_2'];
										$xlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($xlb2_result)!=0) {
											$xlb2Data = mysqli_fetch_array($xlb2_result);
											echo "<option value=\"".$current."\">".$xlb2Data['firstname']." ".$xlb2Data['lastname']."</option>";
											$xlb2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$xlb2set = false;
										}
										foreach($lbArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($xlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										
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
												$current = $deflineup['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												while($cbData = mysqli_fetch_array($active_cb_result)) {
													$playerid = $cbData['id'];
													$name = $cbData['firstname']." ".$cbData['lastname'];
													$cbArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												$current = $deflineup['CB4_1'];
												$cb41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb41_result)!=0) {
													$cb41Data = mysqli_fetch_array($cb41_result);
													echo "<option value=\"".$current."\">".$cb41Data['firstname']." ".$cb41Data['lastname']."</option>";
													$cb41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb41set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb41set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												$current = $deflineup['CB4_2'];
												$cb42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb42_result)!=0) {
													$cb42Data = mysqli_fetch_array($cb42_result);
													echo "<option value=\"".$current."\">".$cb42Data['firstname']." ".$cb42Data['lastname']."</option>";
													$cb42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb42set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb42set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											
											?>
										</select></td>
									</tr>
									<?php if ($premium=="y") {
									echo "
									<tr>
										<td>Cornerback #5</td>
										<td><select class=\"form-control playeropt\" name=\"cb5-1select\">";
											$current = $deflineup['CB5_1'];
												$cb51_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb51_result)!=0) {
													$cb51Data = mysqli_fetch_array($cb51_result);
													echo "<option value=\"".$current."\">".$cb51Data['firstname']." ".$cb51Data['lastname']."</option>";
													$cb51set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb51set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb51set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										echo "</select></td>
										<td><select class=\"form-control playeropt\" name=\"cb5-2select\">";
											$current = $deflineup['CB5_2'];
												$cb52_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb52_result)!=0) {
													$cb52Data = mysqli_fetch_array($cb52_result);
													echo "<option value=\"".$current."\">".$cb52Data['firstname']." ".$cb52Data['lastname']."</option>";
													$cb52set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb52set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb52set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										echo "</select></td>
									</tr>
									";
									} ?>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												while($sData = mysqli_fetch_array($active_s_result)) {
													$playerid = $sData['id'];
													$name = $sData['firstname']." ".$sData['lastname'];
													$sArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												$current = $deflineup['SS2_1'];
												$ss21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss21_result)!=0) {
													$ss21Data = mysqli_fetch_array($ss21_result);
													echo "<option value=\"".$current."\">".$ss21Data['firstname']." ".$ss21Data['lastname']."</option>";
													$ss21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss21set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												$current = $deflineup['SS2_2'];
												$ss22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss22_result)!=0) {
													$ss22Data = mysqli_fetch_array($ss22_result);
													echo "<option value=\"".$current."\">".$ss22Data['firstname']." ".$ss22Data['lastname']."</option>";
													$ss22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss22set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												$current = $deflineup['RS_1'];
												$rs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs1_result)!=0) {
													$rs1Data = mysqli_fetch_array($rs1_result);
													echo "<option value=\"".$current."\">".$rs1Data['firstname']." ".$rs1Data['lastname']."</option>";
													$rs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												$current = $deflineup['RS_2'];
												$rs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs2_result)!=0) {
													$rs2Data = mysqli_fetch_array($rs2_result);
													echo "<option value=\"".$current."\">".$rs2Data['firstname']." ".$rs2Data['lastname']."</option>";
													$rs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup434 = mysqli_fetch_array($deflineup434_result);
						
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
												$current = $deflineup434['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup434['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup434['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup434['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup434['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup434['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup434['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup434['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup434['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup434['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup434['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup434['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup434['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup434['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup434['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup434['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup434['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup434['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup434['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup434['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup434['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup434['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup425 = mysqli_fetch_array($deflineup425_result);
						
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
												$current = $deflineup425['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup425['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup425['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup425['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup425['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup425['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup425['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup425['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup425['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup425['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup425['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup425['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup425['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup425['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup425['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup425['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup425['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup425['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup425['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup425['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												$current = $deflineup425['SS2_1'];
												$ss21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss21_result)!=0) {
													$ss21Data = mysqli_fetch_array($ss21_result);
													echo "<option value=\"".$current."\">".$ss21Data['firstname']." ".$ss21Data['lastname']."</option>";
													$ss21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss21set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												$current = $deflineup425['SS2_2'];
												$ss22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss22_result)!=0) {
													$ss22Data = mysqli_fetch_array($ss22_result);
													echo "<option value=\"".$current."\">".$ss22Data['firstname']." ".$ss22Data['lastname']."</option>";
													$ss22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss22set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup344 = mysqli_fetch_array($deflineup344_result);
						
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
												$current = $deflineup344['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup344['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup344['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup344['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup344['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup344['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup344['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup344['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup344['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup344['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup344['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup344['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												$current = $deflineup344['JLB_1'];
												$jlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb1_result)!=0) {
													$jlb1Data = mysqli_fetch_array($jlb1_result);
													echo "<option value=\"".$current."\">".$jlb1Data['firstname']." ".$jlb1Data['lastname']."</option>";
													$jlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												$current = $deflineup344['JLB_2'];
												$jlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb2_result)!=0) {
													$jlb2Data = mysqli_fetch_array($jlb2_result);
													echo "<option value=\"".$current."\">".$jlb2Data['firstname']." ".$jlb2Data['lastname']."</option>";
													$jlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup344['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup344['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup344['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup344['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup344['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup344['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup344['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup344['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup335 = mysqli_fetch_array($deflineup335_result);
						
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
												$current = $deflineup335['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup335['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup335['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup335['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup335['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup335['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup335['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup335['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup335['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup335['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup335['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup335['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup335['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup335['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup335['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup335['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup335['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup335['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup335['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup335['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2 (Nickel)</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												$current = $deflineup335['SS2_1'];
												$ss21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss21_result)!=0) {
													$ss21Data = mysqli_fetch_array($ss21_result);
													echo "<option value=\"".$current."\">".$ss21Data['firstname']." ".$ss21Data['lastname']."</option>";
													$ss21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss21set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												$current = $deflineup335['SS2_2'];
												$ss22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss22_result)!=0) {
													$ss22Data = mysqli_fetch_array($ss22_result);
													echo "<option value=\"".$current."\">".$ss22Data['firstname']." ".$ss22Data['lastname']."</option>";
													$ss22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss22set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup443 = mysqli_fetch_array($deflineup443_result);
						
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
												$current = $deflineup443['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup443['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup443['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup443['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup443['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup443['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup443['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup443['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup443['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup443['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup443['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup443['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup443['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup443['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												$current = $deflineup443['JLB_1'];
												$jlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb1_result)!=0) {
													$jlb1Data = mysqli_fetch_array($jlb1_result);
													echo "<option value=\"".$current."\">".$jlb1Data['firstname']." ".$jlb1Data['lastname']."</option>";
													$jlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												$current = $deflineup443['JLB_2'];
												$jlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb2_result)!=0) {
													$jlb2Data = mysqli_fetch_array($jlb2_result);
													echo "<option value=\"".$current."\">".$jlb2Data['firstname']." ".$jlb2Data['lastname']."</option>";
													$jlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup443['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup443['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup443['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup443['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup443['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup443['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup353 = mysqli_fetch_array($deflineup353_result);
						
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
												$current = $deflineup353['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup353['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup353['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup353['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup353['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup353['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup353['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup353['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup353['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup353['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup353['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup353['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Jack Linebacker</td>
										<td><select class="form-control playeropt" name="jlb1select">
											<?php
												$current = $deflineup353['JLB_1'];
												$jlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb1_result)!=0) {
													$jlb1Data = mysqli_fetch_array($jlb1_result);
													echo "<option value=\"".$current."\">".$jlb1Data['firstname']." ".$jlb1Data['lastname']."</option>";
													$jlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="jlb2select">
											<?php
												$current = $deflineup353['JLB_2'];
												$jlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($jlb2_result)!=0) {
													$jlb2Data = mysqli_fetch_array($jlb2_result);
													echo "<option value=\"".$current."\">".$jlb2Data['firstname']." ".$jlb2Data['lastname']."</option>";
													$jlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$jlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Extra Linebacker</td>
										<td><select class="form-control playeropt" name="xlb1select">
											<?php
												$current = $deflineup353['XLB_1'];
												$xlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($xlb1_result)!=0) {
													$xlb1Data = mysqli_fetch_array($xlb1_result);
													echo "<option value=\"".$current."\">".$xlb1Data['firstname']." ".$xlb1Data['lastname']."</option>";
													$xlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$xlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($xlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="xlb2select">
											<?php
												$current = $deflineup353['XLB_2'];
												$xlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($xlb2_result)!=0) {
													$xlb2Data = mysqli_fetch_array($xlb2_result);
													echo "<option value=\"".$current."\">".$xlb2Data['firstname']." ".$xlb2Data['lastname']."</option>";
													$xlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$xlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($jlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup353['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup353['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup353['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup353['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup353['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup353['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup425n = mysqli_fetch_array($deflineup425n_result);
						
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
												$current = $deflineup425n['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup425n['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup425n['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup425n['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup425n['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup425n['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup425n['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup425n['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup425n['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup425n['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup425n['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup425n['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup425n['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup425n['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup425n['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup425n['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup425n['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup425n['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup425n['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup425n['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup425n['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup425n['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup335n = mysqli_fetch_array($deflineup335n_result);
						
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
												$current = $deflineup335n['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup335n['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup335n['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup335n['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup335n['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup335n['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup335n['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup335n['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup335n['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup335n['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup335n['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup335n['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup335n['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup335n['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup335n['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup335n['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup335n['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup335n['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup335n['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup335n['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup335n['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup335n['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup416 = mysqli_fetch_array($deflineup416_result);
						
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
												$current = $deflineup416['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup416['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup416['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup416['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup416['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup416['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup416['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup416['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup416['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup416['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup416['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup416['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup416['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup416['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup416['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup416['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												$current = $deflineup416['CB4_1'];
												$cb41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb41_result)!=0) {
													$cb41Data = mysqli_fetch_array($cb41_result);
													echo "<option value=\"".$current."\">".$cb41Data['firstname']." ".$cb41Data['lastname']."</option>";
													$cb41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb41set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb41set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												$current = $deflineup416['CB4_2'];
												$cb42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb42_result)!=0) {
													$cb42Data = mysqli_fetch_array($cb42_result);
													echo "<option value=\"".$current."\">".$cb42Data['firstname']." ".$cb42Data['lastname']."</option>";
													$cb42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb42set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb42set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup416['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup416['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup416['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup416['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup326 = mysqli_fetch_array($deflineup326_result);
						
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
												$current = $deflineup326['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup326['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup326['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup326['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup326['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup326['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup326['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup326['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup326['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup326['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup326['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup326['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup326['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup326['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup326['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup326['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												$current = $deflineup326['CB4_1'];
												$cb41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb41_result)!=0) {
													$cb41Data = mysqli_fetch_array($cb41_result);
													echo "<option value=\"".$current."\">".$cb41Data['firstname']." ".$cb41Data['lastname']."</option>";
													$cb41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb41set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb41set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												$current = $deflineup326['CB4_2'];
												$cb42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb42_result)!=0) {
													$cb42Data = mysqli_fetch_array($cb42_result);
													echo "<option value=\"".$current."\">".$cb42Data['firstname']." ".$cb42Data['lastname']."</option>";
													$cb42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb42set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb42set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup326['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup326['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup326['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup326['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup317 = mysqli_fetch_array($deflineup317_result);
						
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
												$current = $deflineup317['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup317['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Nose Tackle</td>
										
										<td><select class="form-control playeropt" name="nt1select">";
										<?php
										$current = $deflineup317['NT_1'];
										$nt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt1_result)!=0) {
											$nt1Data = mysqli_fetch_array($nt1_result);
											echo "<option value=\"".$current."\">".$nt1Data['firstname']." ".$nt1Data['lastname']."</option>";
											$nt1set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt1set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>	
										</select></td>
										<td><select class="form-control playeropt" name="nt2select">
										<?php
										$current = $deflineup317['NT_2'];
										$nt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
										
										if (mysqli_num_rows($nt2_result)!=0) {
											$nt2Data = mysqli_fetch_array($nt2_result);
											echo "<option value=\"".$current."\">".$nt2Data['firstname']." ".$nt2Data['lastname']."</option>";
											$nt2set = true;
										} else {
											echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
											$nt2set = false;
										}
										foreach($dtArray as $playerarray) {
											if ($playerarray[0]!=$current) {
												echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
											}
										}
										if ($nt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
										?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup317['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup317['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup317['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup317['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup317['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup317['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #2</td>
										<td><select class="form-control playeropt" name="cb2-1select">
											<?php
												$current = $deflineup317['CB2_1'];
												$cb21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb21_result)!=0) {
													$cb21Data = mysqli_fetch_array($cb21_result);
													echo "<option value=\"".$current."\">".$cb21Data['firstname']." ".$cb21Data['lastname']."</option>";
													$cb21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb21set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb2-2select">
											<?php
												$current = $deflineup317['CB2_2'];
												$cb22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb22_result)!=0) {
													$cb22Data = mysqli_fetch_array($cb22_result);
													echo "<option value=\"".$current."\">".$cb22Data['firstname']." ".$cb22Data['lastname']."</option>";
													$cb22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb22set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #3</td>
										<td><select class="form-control playeropt" name="cb3-1select">
											<?php
												$current = $deflineup317['CB3_1'];
												$cb31_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb31_result)!=0) {
													$cb31Data = mysqli_fetch_array($cb31_result);
													echo "<option value=\"".$current."\">".$cb31Data['firstname']." ".$cb31Data['lastname']."</option>";
													$cb31set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb31set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb31set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb3-2select">
											<?php
												$current = $deflineup317['CB3_2'];
												$cb32_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb32_result)!=0) {
													$cb32Data = mysqli_fetch_array($cb32_result);
													echo "<option value=\"".$current."\">".$cb32Data['firstname']." ".$cb32Data['lastname']."</option>";
													$cb32set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb32set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb32set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #4</td>
										<td><select class="form-control playeropt" name="cb4-1select">
											<?php
												$current = $deflineup317['CB4_1'];
												$cb41_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb41_result)!=0) {
													$cb41Data = mysqli_fetch_array($cb41_result);
													echo "<option value=\"".$current."\">".$cb41Data['firstname']." ".$cb41Data['lastname']."</option>";
													$cb41set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb41set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb41set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb4-2select">
											<?php
												$current = $deflineup317['CB4_2'];
												$cb42_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb42_result)!=0) {
													$cb42Data = mysqli_fetch_array($cb42_result);
													echo "<option value=\"".$current."\">".$cb42Data['firstname']." ".$cb42Data['lastname']."</option>";
													$cb42set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb42set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb42set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Cornerback #5</td>
										<td><select class="form-control playeropt" name="cb5-1select">
											<?php
												$current = $deflineup317['CB5_1'];
												$cb51_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb51_result)!=0) {
													$cb51Data = mysqli_fetch_array($cb51_result);
													echo "<option value=\"".$current."\">".$cb51Data['firstname']." ".$cb51Data['lastname']."</option>";
													$cb51set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb51set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb51set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb5-2select">
											<?php
												$current = $deflineup317['CB5_2'];
												$cb52_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb52_result)!=0) {
													$cb52Data = mysqli_fetch_array($cb52_result);
													echo "<option value=\"".$current."\">".$cb52Data['firstname']." ".$cb52Data['lastname']."</option>";
													$cb52set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb52set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb52set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup317['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup317['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup317['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup317['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup623 = mysqli_fetch_array($deflineup623_result);
						
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
												$current = $deflineup623['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup623['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup623['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup623['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup623['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup623['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup623['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup623['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup623['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup623['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup623['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup623['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup623['CB1_1'];
												$cb11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb11_result)!=0) {
													$cb11Data = mysqli_fetch_array($cb11_result);
													echo "<option value=\"".$current."\">".$cb11Data['firstname']." ".$cb11Data['lastname']."</option>";
													$cb11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$cb11set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="cb1-2select">
											<?php
												$current = $deflineup623['CB1_2'];
												$cb12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($cb12_result)!=0) {
													$cb12Data = mysqli_fetch_array($cb12_result);
													echo "<option value=\"".$current."\">".$cb12Data['firstname']." ".$cb12Data['lastname']."</option>";
													$cb12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$cb12set = false;
												}
												foreach($cbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($cb12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Free Safety</td>
										<td><select class="form-control playeropt" name="fs1select">
											<?php
												$current = $deflineup623['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup623['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup623['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup623['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												$current = $deflineup623['SS2_1'];
												$ss21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss21_result)!=0) {
													$ss21Data = mysqli_fetch_array($ss21_result);
													echo "<option value=\"".$current."\">".$ss21Data['firstname']." ".$ss21Data['lastname']."</option>";
													$ss21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss21set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												$current = $deflineup623['SS2_2'];
												$ss22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss22_result)!=0) {
													$ss22Data = mysqli_fetch_array($ss22_result);
													echo "<option value=\"".$current."\">".$ss22Data['firstname']." ".$ss22Data['lastname']."</option>";
													$ss22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss22set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												$current = $deflineup623['RS_1'];
												$rs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs1_result)!=0) {
													$rs1Data = mysqli_fetch_array($rs1_result);
													echo "<option value=\"".$current."\">".$rs1Data['firstname']." ".$rs1Data['lastname']."</option>";
													$rs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												$current = $deflineup623['RS_2'];
												$rs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs2_result)!=0) {
													$rs2Data = mysqli_fetch_array($rs2_result);
													echo "<option value=\"".$current."\">".$rs2Data['firstname']." ".$rs2Data['lastname']."</option>";
													$rs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						$deflineup632 = mysqli_fetch_array($deflineup632_result);
						
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
												$current = $deflineup632['LDE_1'];
												$lde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde1_result)!=0) {
													$lde1Data = mysqli_fetch_array($lde1_result);
													echo "<option value=\"".$current."\">".$lde1Data['firstname']." ".$lde1Data['lastname']."</option>";
													$lde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="lde2select">
											<?php
												$current = $deflineup632['LDE_2'];
												$lde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($lde2_result)!=0) {
													$lde2Data = mysqli_fetch_array($lde2_result);
													echo "<option value=\"".$current."\">".$lde2Data['firstname']." ".$lde2Data['lastname']."</option>";
													$lde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$lde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($lde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Left Defensive Tackle</td>
										<td><select class="form-control playeropt" name="ldt1select">
											<?php
												$current = $deflineup632['LDT_1'];
												$ldt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt1_result)!=0) {
													$ldt1Data = mysqli_fetch_array($ldt1_result);
													echo "<option value=\"".$current."\">".$ldt1Data['firstname']." ".$ldt1Data['lastname']."</option>";
													$ldt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$ldt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ldt2select">
											<?php
												$current = $deflineup632['LDT_2'];
												$ldt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ldt2_result)!=0) {
													$ldt2Data = mysqli_fetch_array($ldt2_result);
													echo "<option value=\"".$current."\">".$ldt2Data['firstname']." ".$ldt2Data['lastname']."</option>";
													$ldt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ldt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ldt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive Tackle</td>
										<td><select class="form-control playeropt" name="rdt1select">
											<?php
												$current = $deflineup632['RDT_1'];
												$rdt1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt1_result)!=0) {
													$rdt1Data = mysqli_fetch_array($rdt1_result);
													echo "<option value=\"".$current."\">".$rdt1Data['firstname']." ".$rdt1Data['lastname']."</option>";
													$rdt1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt1set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rdt2select">
											<?php
												$current = $deflineup632['RDT_2'];
												$rdt2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rdt2_result)!=0) {
													$rdt2Data = mysqli_fetch_array($rdt2_result);
													echo "<option value=\"".$current."\">".$rdt2Data['firstname']." ".$rdt2Data['lastname']."</option>";
													$rdt2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rdt2set = false;
												}
												foreach($dtArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rdt2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Right Defensive End</td>
										<td><select class="form-control playeropt" name="rde1select">
											<?php
												$current = $deflineup632['RDE_1'];
												$rde1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde1_result)!=0) {
													$rde1Data = mysqli_fetch_array($rde1_result);
													echo "<option value=\"".$current."\">".$rde1Data['firstname']." ".$rde1Data['lastname']."</option>";
													$rde1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde1set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rde2select">
											<?php
												$current = $deflineup632['RDE_2'];
												$rde2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rde2_result)!=0) {
													$rde2Data = mysqli_fetch_array($rde2_result);
													echo "<option value=\"".$current."\">".$rde2Data['firstname']." ".$rde2Data['lastname']."</option>";
													$rde2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rde2set = false;
												}
												foreach($deArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rde2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup632['WLB_1'];
												$wlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb1_result)!=0) {
													$wlb1Data = mysqli_fetch_array($wlb1_result);
													echo "<option value=\"".$current."\">".$wlb1Data['firstname']." ".$wlb1Data['lastname']."</option>";
													$wlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$wlb1set = false;
												}
												while($lbData = mysqli_fetch_array($active_lb_result)) {
													$playerid = $lbData['id'];
													$name = $lbData['firstname']." ".$lbData['lastname'];
													$lbArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($wlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="wlb2select">
											<?php
												$current = $deflineup632['WLB_2'];
												$wlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($wlb2_result)!=0) {
													$wlb2Data = mysqli_fetch_array($wlb2_result);
													echo "<option value=\"".$current."\">".$wlb2Data['firstname']." ".$wlb2Data['lastname']."</option>";
													$wlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$wlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($wlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Middle Linebacker</td>
										<td><select class="form-control playeropt" name="mlb1select">
											<?php
												$current = $deflineup632['MLB_1'];
												$mlb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb1_result)!=0) {
													$mlb1Data = mysqli_fetch_array($mlb1_result);
													echo "<option value=\"".$current."\">".$mlb1Data['firstname']." ".$mlb1Data['lastname']."</option>";
													$mlb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="mlb2select">
											<?php
												$current = $deflineup632['MLB_2'];
												$mlb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($mlb2_result)!=0) {
													$mlb2Data = mysqli_fetch_array($mlb2_result);
													echo "<option value=\"".$current."\">".$mlb2Data['firstname']." ".$mlb2Data['lastname']."</option>";
													$mlb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$mlb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($mlb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strongside Linebacker</td>
										<td><select class="form-control playeropt" name="slb1select">
											<?php
												$current = $deflineup632['SLB_1'];
												$slb1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb1_result)!=0) {
													$slb1Data = mysqli_fetch_array($slb1_result);
													echo "<option value=\"".$current."\">".$slb1Data['firstname']." ".$slb1Data['lastname']."</option>";
													$slb1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb1set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="slb2select">
											<?php
												$current = $deflineup632['SLB_2'];
												$slb2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($slb2_result)!=0) {
													$slb2Data = mysqli_fetch_array($slb2_result);
													echo "<option value=\"".$current."\">".$slb2Data['firstname']." ".$slb2Data['lastname']."</option>";
													$slb2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$slb2set = false;
												}
												foreach($lbArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($slb2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
												$current = $deflineup632['FS_1'];
												$fs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs1_result)!=0) {
													$fs1Data = mysqli_fetch_array($fs1_result);
													echo "<option value=\"".$current."\">".$fs1Data['firstname']." ".$fs1Data['lastname']."</option>";
													$fs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$fs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="fs2select">
											<?php
												$current = $deflineup632['FS_2'];
												$fs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($fs2_result)!=0) {
													$fs2Data = mysqli_fetch_array($fs2_result);
													echo "<option value=\"".$current."\">".$fs2Data['firstname']." ".$fs2Data['lastname']."</option>";
													$fs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$fs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($fs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #1</td>
										<td><select class="form-control playeropt" name="ss1-1select">
											<?php
												$current = $deflineup632['SS1_1'];
												$ss11_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss11_result)!=0) {
													$ss11Data = mysqli_fetch_array($ss11_result);
													echo "<option value=\"".$current."\">".$ss11Data['firstname']." ".$ss11Data['lastname']."</option>";
													$ss11set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss11set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss11set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss1-2select">
											<?php
												$current = $deflineup632['SS1_2'];
												$ss12_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss12_result)!=0) {
													$ss12Data = mysqli_fetch_array($ss12_result);
													echo "<option value=\"".$current."\">".$ss12Data['firstname']." ".$ss12Data['lastname']."</option>";
													$ss12set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss12set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss12set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Strong Safety #2</td>
										<td><select class="form-control playeropt" name="ss2-1select">
											<?php
												$current = $deflineup632['SS2_1'];
												$ss21_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss21_result)!=0) {
													$ss21Data = mysqli_fetch_array($ss21_result);
													echo "<option value=\"".$current."\">".$ss21Data['firstname']." ".$ss21Data['lastname']."</option>";
													$ss21set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss21set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss21set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="ss2-2select">
											<?php
												$current = $deflineup632['SS2_2'];
												$ss22_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($ss22_result)!=0) {
													$ss22Data = mysqli_fetch_array($ss22_result);
													echo "<option value=\"".$current."\">".$ss22Data['firstname']." ".$ss22Data['lastname']."</option>";
													$ss22set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$ss22set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($ss22set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
									<tr>
										<td>Rover Safety (Goal Line)</td>
										<td><select class="form-control playeropt" name="rs1select">
											<?php
												$current = $deflineup632['RS_1'];
												$rs1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs1_result)!=0) {
													$rs1Data = mysqli_fetch_array($rs1_result);
													echo "<option value=\"".$current."\">".$rs1Data['firstname']." ".$rs1Data['lastname']."</option>";
													$rs1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs1set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
										<td><select class="form-control playeropt" name="rs2select">
											<?php
												$current = $deflineup632['RS_2'];
												$rs2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($rs2_result)!=0) {
													$rs2Data = mysqli_fetch_array($rs2_result);
													echo "<option value=\"".$current."\">".$rs2Data['firstname']." ".$rs2Data['lastname']."</option>";
													$rs2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$rs2set = false;
												}
												foreach($sArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($rs2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
						<form method="POST" action="depthchart.php?teamid=<?php echo $teamid;?>" id="specteams">
						<div class="col-md-9 col-sm-12">
						<?php
						$stlineup_result = mysqli_query($conn,"SELECT * FROM `stlineup` WHERE team=$teamid");
						$stlineup = mysqli_fetch_array($stlineup_result);
						
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
										<td></td>
										<td><select class="form-control playeropt" name="k1select">
											<?php
												$current = $stlineup['K_1'];
												$k1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($k1_result)!=0) {
													$k1Data = mysqli_fetch_array($k1_result);
													echo "<option value=\"".$current."\">".$k1Data['firstname']." ".$k1Data['lastname']."</option>";
													$k1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$k1set = false;
												}
												while($kData = mysqli_fetch_array($active_k_result)) {
													$playerid = $kData['id'];
													$name = $kData['firstname']." ".$kData['lastname'];
													$kArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												while($pData = mysqli_fetch_array($active_p_result)) {
													$playerid = $pData['id'];
													$name = $pData['firstname']." ".$pData['lastname'];
													$pArray[] = [$playerid,$name];
													if ($playerid!=$current) {
														echo "<option value=\"".$playerid."\">".$name."</option>";
													}
												}
												if ($k1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="k2select">
											<?php
												$current = $stlineup['K_2'];
												$k2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($k2_result)!=0) {
													$k2Data = mysqli_fetch_array($k2_result);
													echo "<option value=\"".$current."\">".$k2Data['firstname']." ".$k2Data['lastname']."</option>";
													$k2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$k2set = false;
												}
												foreach($kArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												foreach($pArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($k2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
											?>
										</select></td>
									</tr>
								</tbody>
								</table>
							</div>
							<div class="row well playerrow" id="prow">
								<h4>Punter</h4>
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
										<td></td>
										<td><select class="form-control playeropt" name="p1select">
											<?php
												$current = $stlineup['P_1'];
												$p1_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($p1_result)!=0) {
													$p1Data = mysqli_fetch_array($p1_result);
													echo "<option value=\"".$current."\">".$p1Data['firstname']." ".$p1Data['lastname']."</option>";
													$p1set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>";
													$p1set = false;
												}
												foreach($pArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												foreach($kArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($p1set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>";  }
												
											?>
										</select></td>
										<td><select class="form-control playeropt" name="p2select">
											<?php
												$current = $stlineup['P_2'];
												$p2_result = mysqli_query($conn,"SELECT firstname,lastname FROM player WHERE id=$current");
												
												if (mysqli_num_rows($p2_result)!=0) {
													$p2Data = mysqli_fetch_array($p2_result);
													echo "<option value=\"".$current."\">".$p2Data['firstname']." ".$p2Data['lastname']."</option>";
													$p2set = true;
												} else {
													echo "<option value=\"0\" class=\"autooption\">Auto</option>"; 
													$p2set = false;
												}
												foreach($pArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												foreach($kArray as $playerarray) {
													if ($playerarray[0]!=$current) {
														echo "<option value=\"".$playerarray[0]."\">".$playerarray[1]."</option>";
													}
												}
												if ($p2set) { echo "<option value=\"0\" class=\"autooption\">Auto</option>"; }
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
    </div>
  </body>
</html>