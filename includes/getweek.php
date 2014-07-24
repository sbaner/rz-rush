<?php

function getWeek($league) {
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$league_result = mysqli_query($conn,"SELECT calendar FROM league WHERE id=$league");
	$calendarData = mysqli_fetch_array($league_result);
	
	$calendar = $calendarData['calendar'];
	$week = "";
	switch ($calendar){
		case(1):
			$week = "Pre-Draft / Free Agency Day 1";
			break;
		case(2):
			$week = "Pre-Draft / Free Agency Day 2";
			break;
		case(3):
			$week = "Draft";
			break;
		case(4):
			$week = "Preseason Week 1";
			break;
		case(5):
			$week = "Preseason Week 2";
			break;
		case(6):
			$week = "Preseason Week 3";
			break;
		case(7):
			$week = "Preseason Week 4";
			break;
		case(8):
			$week = "Roster Finalization";
			break;
		case(9):
			$week = "Regular Season Week 1";
			break;
		case(10):
			$week = "Regular Season Week 2";
			break;
		case(11):
			$week = "Regular Season Week 3";
			break;
		case(12):
			$week = "Regular Season Week 4";
			break;
		case(13):
			$week = "Regular Season Week 5";
			break;
		case(14):
			$week = "Regular Season Week 6";
			break;
		case(15):
			$week = "Regular Season Week 7";
			break;
		case(16):
			$week = "Regular Season Week 8";
			break;
		case(17):
			$week = "Regular Season Week 9";
			break;
		case(18):
			$week = "Regular Season Week 10";
			break;
		case(19):
			$week = "Regular Season Week 11";
			break;
		case(20):
			$week = "Regular Season Week 12";
			break;
		case(21):
			$week = "Regular Season Week 13";
			break;
		case(22):
			$week = "Regular Season Week 14";
			break;
		case(23):
			$week = "Regular Season Week 15";
			break;
		case(24):
			$week = "Regular Season Week 16";
			break;
		case(25):
			$week = "Playoffs Week 1: Wildcard Round";
			break;
		case(26):
			$week = "Playoffs Week 2: Divisional Round";
			break;
		case(27):
			$week = "Playoffs Week 3: Conference Championships";
			break;
		case(28):
			$week = "Pre-Championship / Pro-Bowl";
			break;
		case(29):
			$week = "League Championship";
			break;
		case(30):
			$week = "Post-Championship / End of Season";
	}
	
	return $week;
}

?>