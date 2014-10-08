<?php
	require_once 'includes/functions.php';
	date_default_timezone_set('America/New_York');
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	class Player {
		//Attributes
		public $playerid;
		public $playername;
		public $overall;
		public $height;
		public $weight;
		public $strength;
		public $speed;
		public $burst;
		public $carry;
		public $hands;
		public $elusive;
		public $fw;
		public $read;
		public $pocket;
		public $throw_pow;
		public $throw_acc;
		public $pass_block;
		public $run_block;
		public $route;
		public $jump;
		public $block_shed;
		public $coverage;
		public $tackling;
		public $kick_pow;
		public $kick_acc;
		public $punt_acc;
		public $leadership;
		public $clutch;
		public $durability;
		public $toughness;
		public $x=0;
		public $y=0;
		
		public function __construct($playerid){
			global $conn;
			$this->playerid=$playerid;
			//Get player data
			$player_result = mysqli_query($conn,"SELECT player.firstname,player.lastname,attributes.* FROM player JOIN attributes ON attributes.player=player.id WHERE player.id=$playerid");
			$playerData = mysqli_fetch_array($player_result);
			
			$this->playername = $playerData['firstname']." ".$playerData['lastname'];
			$this->overall = $playerData['overall_now'];
			$this->height = $playerData['height'];
			$this->weight = $playerData['weight'];
			$this->strength = $playerData['strength_now'];
			$this->speed = $playerData['speed_now'];
			$this->burst = $playerData['burst_now'];
			$this->carry = $playerData['carry_now'];
			$this->hands = $playerData['hands_now'];
			$this->elusive = $playerData['elusive_now'];
			$this->fw = $playerData['fw_now'];
			$this->read = $playerData['read_now'];
			$this->pocket = $playerData['pocket_now'];
			$this->throw_pow = $playerData['throw_pow_now'];
			$this->throw_acc = $playerData['throw_acc_now'];
			$this->pass_block = $playerData['pass_block_now'];
			$this->run_block = $playerData['run_block_now'];
			$this->route = $playerData['route_now'];
			$this->jump = $playerData['jump_now'];
			$this->block_shed = $playerData['block_shed_now'];
			$this->coverage = $playerData['coverage_now'];
			$this->tackling = $playerData['tackling_now'];
			$this->kick_pow = $playerData['kick_pow_now'];
			$this->kick_acc = $playerData['kick_acc_now'];
			$this->punt_acc = $playerData['punt_acc_now'];
			$this->leadership = $playerData['leadership'];
			$this->clutch = $playerData['clutch'];
			$this->durability = $playerData['durability'];
			$this->toughness = $playerData['toughness'];
		}
	}
	
	function simGame($gameid) {
		global $conn;
		$game_result = mysqli_query($conn,"SELECT home,away FROM games WHERE id=$gameid");
		$gameData = mysqli_fetch_array($game_result);
		$home = $gameData['home'];
		$away = $gameData['away'];
		//Team data
		$home_result = mysqli_query($conn,"SELECT * FROM team WHERE id=$home");
		$homeData = mysqli_fetch_array($home_result);
		$home_name = $homeData['location']." ".$homeData['teamname'];
		$home_abbrev = $homeData['abbrev'];
		
		$away_result = mysqli_query($conn,"SELECT * FROM team WHERE id=$away");
		$awayData = mysqli_fetch_array($away_result);
		$away_name = $awayData['location']." ".$awayData['teamname'];
		$away_abbrev = $awayData['abbrev'];
		
		//Find lineup
		function getOffLineup ($team,$personnel) {
			global $conn;
			$lineup_query = "SELECT QB_1,QB_2,QB_3,LT_1,LT_2,LG_1,LG_2,C_1,C_2,RG_1,RG_2,RT_1,RT_2,HB_1,HB_2,FB_1,FB_2,WR1_1,WR1_2,WR2_1,WR2_2,WR3_1,WR3_2,WR4_1,WR4_2,WR5_1,WR5_2,TE1_1,TE1_2,TE2_1,TE2_2,TE3_1,TE3_2 FROM offlineup WHERE team=$team AND personnel=$personnel";
			$lineup_result = mysqli_query($conn,$lineup_query);
			$lineup = mysqli_fetch_array($lineup_result);
			return $lineup;
		}
		
		function getDefLineup ($team,$personnel) {
			global $conn;
			$lineup_query = "SELECT LDE_1,LDE_2,LDT_1,LDT_2,RDT_1,RDT_2,RDE_1,RDE_2,NT_1,NT_2,WLB_1,WLB_2,MLB_1,MLB_2,SLB_1,SLB_2,JLB_1,JLB_2,XLB_1,XLB_2,CB1_1,CB1_2,CB2_1,CB2_2,CB3_1,CB3_2,CB4_1,CB4_2,CB5_1,CB5_2,FS_1,FS_2,SS1_1,SS1_2,SS2_1,SS2_2,RS_1,RS_2 FROM deflineup WHERE team=$team AND personnel=$personnel";
			$lineup_result = mysqli_query($conn,$lineup_query);
			$lineup = mysqli_fetch_array($lineup_result);
			return $lineup;
		}
		
		function getStLineup ($team) {
			global $conn;
			$lineup_query = "SELECT K_1,K_2,P_1,P_2,KR_1,KR_2,PR_1,PR_2 FROM stlineup WHERE team=$team";
			$lineup_result = mysqli_query($conn,$lineup_query);
			$lineup = mysqli_fetch_array($lineup_result);
			return $lineup;
		}
		
		function kickOff ($kick_lineup,$rec_lineup,$quarter,$time,$ydline) {
			$time_elapsed=0;
			$minute = floor($time/60);
			$second = $time % 60;
			$second = sprintf("%02s", $second);
			echo "<b>Q".$quarter." ".$minute.":".$second."</b> ";
			$kicker = new Player($kick_lineup["K_1"]);
			$returner = new Player($rec_lineup["KR_1"]);
			//Kick
			$kick_distance = ($kicker->kick_pow*.421+33.3)*10;
			$kick_distance = mt_rand($kick_distance-50,$kick_distance+50);
			$kick_distance = round($kick_distance/10,1);
			$bally = round($ydline-$kick_distance,1);
			echo "K ".$kicker->playername." kicks it off ".$kick_distance." yards from his ".(100-$ydline)." to the opposing ".$bally." yard line. "."<br>";
			
			//Return
			
			if ($bally <= -5) {
				$touchback = true;
			} else if ($baly<0 && mt_rand(0,1)==1){
				$touchback = true;
			} else {
				$touchback = false;
			}
			if ($touchback) {
				echo "Touchback.<br>";
				$bally = 20;
			} else {
				$return_distance = 0;
				//Return until contact
				$return_distance = $return_distance + (mt_rand(150,250))/10;
				//Yards after contact - usually 0, can be more if random roll falls favorably
				$yac_factor = (((($returner->speed+$returner->elusive+$returner->strength+$returner->read)/2.5/100)-1)/1.25)+1;
				if ((mt_rand(0,100)*$yac_factor)>70) {
					if ((mt_rand(0,100))>=97) { //Kickoff TD
						$touchdown = true;
						$return_distance = 100-$bally;
					} else {
						$touchdown = false;
						$yac = round($yac_factor*logarithmicWeightedRandom(0,50),1);
						$return_distance = $return_distance+$yac;
					}
				}
				$time_elapsed = $time_elapsed+round($return_distance*.15,0);
				echo $returner->playername." returns it ".$return_distance." yards";
				if ($touchdown) {
					echo " for a touchdown!";
					$bally = 100;
				} else {
					echo " to the ".($bally+$return_distance)." yard line.";
					$bally = $bally+$return_distance;
				}
				echo " ".$time_elapsed." seconds elapsed.<br>";
			}
			echo "<hr>";
			$result_array = ["bally"=>$bally,"time_elapsed"=>$time_elapsed];
			return $result_array;
		}
		
		$home_off_lineup_22 = getOffLineup($home,"22");
		$home_off_lineup_21 = getOffLineup($home,"21");
		$home_off_lineup_20 = getOffLineup($home,"20");
		$home_off_lineup_12 = getOffLineup($home,"12");
		$home_off_lineup_11 = getOffLineup($home,"11");
		$home_off_lineup_10 = getOffLineup($home,"10");
		$home_off_lineup_00 = getOffLineup($home,"00");
		$home_off_lineup_23 = getOffLineup($home,"23");
		
		$home_def_lineup_434 = getDefLineup($home,"434");
		$home_def_lineup_425 = getDefLineup($home,"425");
		$home_def_lineup_344 = getDefLineup($home,"344");
		$home_def_lineup_335 = getDefLineup($home,"335");
		$home_def_lineup_443 = getDefLineup($home,"445");
		$home_def_lineup_353 = getDefLineup($home,"353");
		$home_def_lineup_425n = getDefLineup($home,"425n");
		$home_def_lineup_335n = getDefLineup($home,"335n");
		$home_def_lineup_416 = getDefLineup($home,"416");
		$home_def_lineup_326 = getDefLineup($home,"326");
		$home_def_lineup_317 = getDefLineup($home,"317");
		$home_def_lineup_623 = getDefLineup($home,"623");
		$home_def_lineup_632 = getDefLineup($home,"632");
		
		$home_st_lineup = getStLineup($home);
		
		$away_off_lineup_22 = getOffLineup($away,"22");
		$away_off_lineup_21 = getOffLineup($away,"21");
		$away_off_lineup_20 = getOffLineup($away,"20");
		$away_off_lineup_12 = getOffLineup($away,"12");
		$away_off_lineup_11 = getOffLineup($away,"11");
		$away_off_lineup_10 = getOffLineup($away,"10");
		$away_off_lineup_00 = getOffLineup($away,"00");
		$away_off_lineup_23 = getOffLineup($away,"23");
		
		$away_def_lineup_434 = getDefLineup($away,"434");
		$away_def_lineup_425 = getDefLineup($away,"425");
		$away_def_lineup_344 = getDefLineup($away,"344");
		$away_def_lineup_335 = getDefLineup($away,"335");
		$away_def_lineup_443 = getDefLineup($away,"445");
		$away_def_lineup_353 = getDefLineup($away,"353");
		$away_def_lineup_425n = getDefLineup($away,"425n");
		$away_def_lineup_335n = getDefLineup($away,"335n");
		$away_def_lineup_416 = getDefLineup($away,"416n");
		$away_def_lineup_326 = getDefLineup($away,"326");
		$away_def_lineup_317 = getDefLineup($away,"317");
		$away_def_lineup_623 = getDefLineup($away,"623");
		$away_def_lineup_632 = getDefLineup($away,"632");
		
		$away_st_lineup = getStLineup($away);
		
		//Start sim
		$time=900;
		$quarter = 1;
		$down = 1;
		$distance = 10;
		$finished = false;
		//Ball position. Field  is a 53 x 100 grid. Endzones are -10 to 0 and 100 to 110. Offense tries to go from 0 to 100.
		$bally = 0;
		if (mt_rand(0,1)==1) {
			$offense = $home;
			$off_str="home";
			$def_str="away";
		} else {
			$offense = $away;
			$off_str="away";
			$def_str="home";
		}
		while (!$finished) {
			$minute = floor($time/60);
			$second = $time % 60;
			$second = sprintf("%02s", $second);
			if ($time==900&&$quarter==1) {
				echo "Welcome to this game between the ".$home_name." (".$home_abbrev.") and the ".$away_name." (".$away_abbrev.")!<br>";
			}
			echo "Down: ".$down.", ".$distance." yards to go.<br>";
			$play = kickOff($home_st_lineup,$away_st_lineup,$quarter,$time,65);
			$time = $time-$play["time_elapsed"];
			$bally = $play["bally"];
			echo "Ball is on the ".$bally." yard line.<br>";
			if ($time<=0) {
				echo "End of quarter.<br>";
				$time=900;
				$quarter = $quarter + 1;
			}
			if ($quarter==5) {
				$finished = true;
				echo "End of game.<br>";
			}
		}
	}
	
	simGame(33);
?>