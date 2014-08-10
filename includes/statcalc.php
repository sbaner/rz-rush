<?php

	function passerRating($att,$comp,$yds,$td,$int) {
		$a = ($comp/$att - 0.3) * 5;
		$b = ($yds/$att - 3) * .25;
		$c = ($td/$att) * 20;
		$d = 2.375 - ($int/$att * 25);
		
		$a = max(0,min($a,2.375));
		$b = max(0,min($b,2.375));
		$c = max(0,min($c,2.375));
		$d = max(0,min($d,2.375));
		
		$rating = (($a+$b+$c+$d)/6)*100;
		
		return $rating;
	}
	
	function anya($att,$yds,$td,$int,$sck,$scky) {
		$anya = ($yds+20*$td-45*$int-$scky)/($att+$sck);
		
		return $anya;
	}
?>