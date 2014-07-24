<?php 
function mkseed()
{
    srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff) ;
}   //  function mkseed()




/*
function factorial($in) {
    if ($in == 1) {
        return $in ;
    }
    return ($in * factorial($in - 1.0)) ;
}   //  function factorial()


function factorial($in) {
    $out = 1 ;
    for ($i = 2; $i <= $in; $i++) {
        $out *= $i ;
    }

    return $out ;
}   //  function factorial()
*/




function random_0_1()
{
    //  returns random number using mt_rand() with a flat distribution from 0 to 1 inclusive
    //
    return (float) mt_rand() / (float) mt_getrandmax() ;
}   //  random_0_1()


function random_PN()
{
    //  returns random number using mt_rand() with a flat distribution from -1 to 1 inclusive
    //
    return (2.0 * random_0_1()) - 1.0 ;
}   //  function random_PN()




function gauss()
{
    static $useExists = false ;
    static $useValue ;

    if ($useExists) {
        //  Use value from a previous call to this function
        //
        $useExists = false ;
        return $useValue ;
    } else {
        //  Polar form of the Box-Muller transformation
        //
        $w = 2.0 ;
        while (($w >= 1.0) || ($w == 0.0)) {
            $x = random_PN() ;
            $y = random_PN() ;
            $w = ($x * $x) + ($y * $y) ;
        }
        $w = sqrt((-2.0 * log($w)) / $w) ;

        //  Set value for next call to this function
        //
        $useValue = $y * $w ;
        $useExists = true ;

        return $x * $w ;
    }
}   //  function gauss()


function gauss_ms( $mean,
                   $stddev )
{
    //  Adjust our gaussian random to fit the mean and standard deviation
    //  The division by 4 is an arbitrary value to help fit the distribution
    //      within our required range, and gives a best fit for $stddev = 1.0
    //
    return gauss() * ($stddev/4) + $mean;
}   //  function gauss_ms()


function gaussianWeightedRandom( $LowValue,
                                 $maxRand,
                                 $mean=0.0,
                                 $stddev=2.0 )
{
    //  Adjust a gaussian random value to fit within our specified range
    //      by 'trimming' the extreme values as the distribution curve
    //      approaches +/- infinity
    $rand_val = $LowValue + $maxRand ;
    while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
        $rand_val = floor(gauss_ms($mean,$stddev) * $maxRand) + $LowValue ;
        $rand_val = ($rand_val + $maxRand) / 2 ;
    }

    return $rand_val ;
}   //  function gaussianWeightedRandom()


function bellWeightedRandom( $LowValue,
                             $maxRand )
{
    return gaussianWeightedRandom( $LowValue, $maxRand, 0.0, 1.0 ) ;
}   //  function bellWeightedRandom()


function gaussianWeightedRisingRandom( $LowValue,
                                       $maxRand )
{
    //  Adjust a gaussian random value to fit within our specified range
    //      by 'trimming' the extreme values as the distribution curve
    //      approaches +/- infinity
    //  The division by 4 is an arbitrary value to help fit the distribution
    //      within our required range
    $rand_val = $LowValue + $maxRand ;
    while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
        $rand_val = $maxRand - round((abs(gauss()) / 4) * $maxRand) + $LowValue ;
    }

    return $rand_val ;
}   //  function gaussianWeightedRisingRandom()

function logarithmic($mean=1.0, $lambda=5.0)
{
    return ($mean * -log(random_0_1())) / $lambda ;
}   //  function logarithmic()


function logarithmicWeightedRandom( $LowValue,
                                    $maxRand )
{
    do {
        $rand_val = logarithmic() ;
    } while ($rand_val > 1) ;

    return floor($rand_val * $maxRand) + $LowValue ;
}   //  function logarithmicWeightedRandom()


function genplayers($num,$year,$leagueid,$position,$create) {
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	for($i=0;$i<$num;$i++) {
			$firstname_query = mysqli_query($conn,"SELECT name FROM ref_census_firstnames_male n ORDER BY (RAND() * (n.freq + .01)) LIMIT 0,1;");
			$firstnameArray = mysqli_fetch_array($firstname_query);
			$firstname = $firstnameArray['name'];
			$firstname = ucfirst($firstname);
			
			$lastname_query = mysqli_query($conn,"SELECT name FROM ref_census_surnames n ORDER BY (RAND() * (n.freq + .01)) LIMIT 0,1;");
			$lastnameArray = mysqli_fetch_array($lastname_query);
			$lastname = $lastnameArray['name'];
			$lastname = ucfirst($lastname);
			if ($create) {
				$overall_now = floor(gauss_ms(73,40));
				if ($overall_now > 99) {
					$overall_now = 99;
				}
				$overall_potential = floor(logarithmicWeightedRandom($overall_now,99-$overall_now));
			} else {
				$overall_potential = floor(gauss_ms(73,40));
				if ($overall_potential > 99) {
					$overall_potential = 99;
				}
				$growth = floor(gauss_ms(15,16));
				$overall_now = $overall_potential - $growth;
			}
			
			switch ($position) {
				case "QB":
					$height = floor(gaussianWeightedRisingRandom(70,8));
					$weightcoeff = 2.93*(rand(1000,1050)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "RB":
					$height = floor(gaussianWeightedRisingRandom(65,9));
					$weightcoeff = 2.55*(rand(1000,1100)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "FB":
					$height = floor(gaussianWeightedRisingRandom(71,5));
					$weightcoeff = 3.45*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "WR":
					$height = floor(gaussianWeightedRisingRandom(69,7));
					$weightcoeff = 2.83*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
					break;
				case "TE":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 3.4*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "G":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "C":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "T":
					$height = floor(gaussianWeightedRisingRandom(73,6));
					$weightcoeff = 4*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "DE":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 3.7*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "DT":
					$height = floor(gaussianWeightedRisingRandom(71,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "LB":
					$height = floor(gaussianWeightedRisingRandom(71,4));
					$weightcoeff = 3.3*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "CB":
					$height = floor(gaussianWeightedRisingRandom(68,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "S":
					$height = floor(gaussianWeightedRisingRandom(69,6));
					$weightcoeff = 2.9*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "K":
					$height = floor(gaussianWeightedRisingRandom(70,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				case "P":
					$height = floor(gaussianWeightedRisingRandom(68,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
					break;
				default:
					//That's not supposed to happen
			}
			mysqli_query($conn, "INSERT INTO `player` (start_year,league,firstname,lastname,position,overall_now,overall_potential,height,weight) VALUES ($year,$leagueid,'$firstname','$lastname','$position',$overall_now,$overall_potential,$height,$weight);");
		}
}

function create_salaries($playerid,$year,$team,$position,$rating) {
	switch($position) {
		case "QB":
			if ($rating > 69) {
				$salary = round(750*exp(.1*$rating),-4);
				$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "RB":
			if ($rating > 74) {
			$salary = round(7520*pow($rating,2)-1014193*$rating+34518130,-4);
			$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "FB":
			if ($rating > 80) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "WR":
			if ($rating > 77) {
				$salary = round(469746*$rating-35649291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "TE":
			if ($rating > 76) {
				$salary = round(1198*pow($rating,2)+63882*$rating-11053232,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "G":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "C":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "T":
			if ($rating > 77) {
				$salary = round(8714*pow($rating,2)-1200500*$rating+41384657,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DE":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DT":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "LB":
			if ($rating > 72) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "CB":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "S":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "K":
			if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "P":
		if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		default:
			//That's not supposed to happen
	}
	
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	for ($i=0;$i<4;$i++) {
		$contract_year = $year+$i;
		mysqli_query($conn, "INSERT INTO contract (player,year,team,bonus,base,caphit) VALUES ($playerid,$contract_year,$team,$bonus,$salary,$bonus)");
	}
	
}

function fa_demand($position,$rating) {
	
	switch($position) {
		case "QB":
			if ($rating > 69) {
				$salary = round(750*exp(.1*$rating),-4);
				$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "RB":
			if ($rating > 74) {
			$salary = round(7520*pow($rating,2)-1014193*$rating+34518130,-4);
			$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "FB":
			if ($rating > 80) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "WR":
			if ($rating > 77) {
				$salary = round(469746*$rating-35649291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "TE":
			if ($rating > 76) {
				$salary = round(1198*pow($rating,2)+63882*$rating-11053232,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "G":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "C":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "T":
			if ($rating > 77) {
				$salary = round(8714*pow($rating,2)-1200500*$rating+41384657,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DE":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DT":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "LB":
			if ($rating > 72) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "CB":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "S":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "K":
			if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "P":
		if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		default:
			//That's not supposed to happen
	}
	
	$demand = ["salary" => $salary, "bonus" => $bonus,];
	return $demand;
}

?>