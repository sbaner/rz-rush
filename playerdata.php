<?php
	if (isset($_POST['playerid'])) {
		$playerid = $_POST['playerid'];
		if ($playerid !=0) {
			$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
			$player_result = mysqli_query($conn,"SELECT player.firstname,player.lastname,player.position,player.health,attributes.overall_now,attributes.height,attributes.weight FROM player JOIN attributes ON attributes.player=player.id WHERE player.id=$playerid");
			if (mysqli_num_rows($player_result)==1) {
				$playerData = mysqli_fetch_array($player_result);
				$name = $playerData['firstname']." ".$playerData['lastname'];
				$position = $playerData['position'];
				$health = $playerData['health'];
				$rating = $playerData['overall_now'];
				$height = $playerData['height'];
				$inches = $height%12;
				$feet = ($height-$inches)/12;
				$player_height = $feet."'".$inches."\"";
				$weight = $playerData['weight']."lbs";
				
				$data_array = [$name,$position,$health,$rating,$player_height,$weight];
				echo json_encode($data_array);
				exit();
			}
		}
	}
	

?>