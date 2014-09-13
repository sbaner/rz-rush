<?php 
function mkseed()
{
    srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff) ;
}   //  function mkseed()

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
			// if ($create) {
			// 	$overall_now = floor(gauss_ms(73,40));
			// 	if ($overall_now > 99) {
			//		$overall_now = 99;
			//	}
			//	$overall_potential = floor(logarithmicWeightedRandom($overall_now,99-$overall_now));
			// } else {
			//	$overall_potential = floor(gauss_ms(73,40));
			//	if ($overall_potential > 99) {
			//		$overall_potential = 99;
			//	}
			//	$growth = floor(gauss_ms(15,16));
			//	$overall_now = $overall_potential - $growth;
			// }
			
			switch ($position) {
				case "QB":
					$height = floor(gaussianWeightedRisingRandom(70,8));
					$weightcoeff = 2.93*(rand(1000,1050)/1000);
					$weight = floor($height*$weightcoeff);
					
						//Strength
						$strength_now = floor(gauss_ms(30,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(40,100));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(15,40));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(50,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(70,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(50,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(70,64));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(70,64));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(70,64));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(70,60));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(70,60));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(20,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(70,88));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
					
					$overall_now = floor($elusive_now*.05+$fw_now*.1+$read_now*.15+$pocket_now*.15+$throw_pow_now*.15+$throw_acc_now*.2+$leadership*.05+$clutch*.05+$toughness*.1)+8;
					$overall_potential = floor($elusive_potential*.05+$fw_potential*.1+$read_potential*.15+$pocket_potential*.15+$throw_pow_potential*.15+$throw_acc_potential*.2+$leadership*.05+$clutch*.05+$toughness*.1)+8;
					break;
				case "RB":
					$height = floor(gaussianWeightedRisingRandom(65,9));
					$weightcoeff = 2.55*(rand(1000,1100)/1000);
					$weight = floor($height*$weightcoeff);
				
						//Strength
						$strength_now = floor(gauss_ms(65,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(73,60));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(50,100));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(60,80));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(40,120));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(50,100));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(60,80));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(50,100));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(40,80));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(30,50));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(40,100));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(30,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(5,8));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(73,60));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.2+$speed_now*.2+$burst_now*.1+$carry_now*.1+$hands_now*.05+$elusive_now*.15+$read_now*.1+$pass_block_now*.1)+16;
					$overall_potential = floor($strength_potential*.2+$speed_potential*.2+$burst_potential*.1+$carry_potential*.1+$hands_potential*.05+$elusive_potential*.15+$read_potential*.1+$pass_block_potential*.1)+16;
					
					break;
				case "FB":
					$height = floor(gaussianWeightedRisingRandom(71,5));
					$weightcoeff = 3.45*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
			
						//Strength
						$strength_now = floor(gauss_ms(70,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(40,100));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(60,52));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(60,80));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(30,80));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(20,40));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(70,64));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(60,80));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(60,64));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(60,64));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(10,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(70,88));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
					$overall_now = floor($pass_block_now*.3+$run_block_now*.3+$read_now*.15+$burst_now*.1+$strength_now*.15)+12;
					$overall_potential = floor($pass_block_potential*.3+$run_block_potential*.3+$read_potential*.15+$burst_potential*.1+$strength_potential*.15)+12;
					break;
				case "WR":
					$height = floor(gaussianWeightedRisingRandom(69,7));
					$weightcoeff = 2.83*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
		
						//Strength
						$strength_now = floor(gauss_ms(50,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(73,60));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(50,100));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(50,100));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(73,60));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(50,100));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(50,100));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(40,60));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(40,60));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(40,60));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(73,60));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(50,100));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(5,8));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($route_now*.2+$burst_now*.1+$hands_now*.15+$strength_now*.15+$speed_now*.2+$jump_now*.1+($height/77*99)*.1)+7;
					$overall_potential = floor($route_potential*.2+$burst_potential*.1+$hands_potential*.15+$strength_potential*.15+$speed_potential*.2+$jump_potential*.1+($height/77*99)*.1)+7;
					break;
				case "TE":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 3.4*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(65,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(50,80));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(60,80));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(50,100));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(50,100));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(40,80));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(50,100));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(40,60));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(55,60));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(55,60));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(50,100));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(40,60));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(5,8));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$rec_overall_now = floor($route_now*.2+$burst_now*.15+$hands_now*.15+$strength_now*.2+$speed_now*.2+$jump_now*.1)+10;
					$rec_overall_potential = floor($route_potential*.2+$burst_potential*.15+$hands_potential*.15+$strength_potential*.2+$speed_potential*.2+$jump_potential*.1)+10;
					$blk_overall_now = floor($strength_now*.2+$run_block_now*.25+$pass_block_now*.25+$burst_now*.2+$read_now*.1);
					$blk_overall_potential = floor($strength_potential*.2+$run_block_potential*.25+$pass_block_potential*.25+$burst_potential*.2+$read_potential*.1);
					
					$overall_now = max($blk_overall_now,$rec_overall_now)+8;
					$overall_potential = max($blk_overall_potential,$rec_overall_potential)+8;
					
					break;
				case "G":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(83,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(20,40));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(73,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(20,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(5,8));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(73,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(50,100));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(73,60));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(73,60));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(10,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.2+$burst_now*.1+$fw_now*.1+$run_block_now*.25+$pass_block_now*.25+$read_now*.1)+2;
					$overall_potential = floor($strength_potential*.2+$burst_potential*.1+$fw_potential*.1+$run_block_potential*.25+$pass_block_potential*.25+$read_potential*.1)+2;
					
					break;
				case "C":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(78,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(20,12));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(73,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,12));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(10,12));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(5,8));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(73,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(73,60));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(60,80));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(60,80));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(10,12));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(70,88));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.1+$burst_now*.1+$fw_now*.1+$run_block_now*.15+$pass_block_now*.15+$read_now*.2+$leadership*.2)+5;
					$overall_potential = floor($strength_potential*.1+$burst_potential*.1+$fw_potential*.1+$run_block_potential*.15+$pass_block_potential*.15+$read_potential*.2+$leadership*.2)+5;
					break;
				case "T":
					$height = floor(gaussianWeightedRisingRandom(78,6));
					$weightcoeff = 4*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(73,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(20,40));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(73,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(20,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(5,8));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(73,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(50,100));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(73,60));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(73,60));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(10,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.2+$burst_now*.15+$fw_now*.15+$run_block_now*.2+$pass_block_now*.2+$read_now*.1)+3;
					$overall_potential = floor($strength_potential*.2+$burst_potential*.15+$fw_potential*.15+$run_block_potential*.2+$pass_block_potential*.2+$read_potential*.1)+3;
					break;
				case "DE":
					$height = floor(gaussianWeightedRisingRandom(72,6));
					$weightcoeff = 3.7*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(73,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(50,80));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(73,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,50));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(10,50));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(30,100));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(73,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(50,100));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(50,100));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(80,60));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(40,100));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(60,80));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($speed_now*.15+$strength_now*.15+$burst_now*.15+$fw_now*.1+$read_now*.05+$block_shed_now*.3+$tackling_now*.1)+5;
					$overall_potential = floor($speed_potential*.15+$strength_potential*.15+$burst_potential*.15+$fw_potential*.1+$read_potential*.05+$block_shed_potential*.3+$tackling_potential*.1)+5;
					break;
				case "DT":
					$height = floor(gaussianWeightedRisingRandom(71,6));
					$weightcoeff = 4.1*(rand(1000,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(80,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(30,100));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(73,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,50));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(10,50));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(10,8));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(60,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(50,100));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(10,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(73,60));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(30,100));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(60,80));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.25+$burst_now*.2+$fw_now*.15+$read_now*.1+$block_shed_now*.15+$tackling_now*.15)+6;
					$overall_potential = floor($strength_potential*.25+$burst_now*.2+$fw_potential*.15+$read_potential*.1+$block_shed_potential*.15+$tackling_potential*.15)+6;
					break;
				case "LB":
					$height = floor(gaussianWeightedRisingRandom(71,4));
					$weightcoeff = 3.3*(rand(975,1025)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(50,80));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(60,60));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(60,80));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(30,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(50,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(50,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(40,60));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(73,60));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(50,100));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(50,100));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(50,100));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(73,60));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(70,88));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$ilb_overall_now = floor($read_now*.2+$strength_now*.15+$speed_now*.15+$burst_now*.1+$block_shed_now*.1+$coverage_now*.05+$tackling_now*.2+$leadership*.05);
					$ilb_overall_potential = floor($read_potential*.2+$strength_potential*.15+$speed_potential*.15+$burst_potential*.1+$block_shed_potential*.1+$coverage_potential*.05+$tackling_potential*.2+$leadership*.05);
					$pr_overall_now = floor($speed_now*.20+$burst_now*.15+$strength_now*.20+$block_shed_now*.25+$tackling_now*.1+$read_now*.1);
					$pr_overall_potential = floor($speed_potential*.20+$burst_potential*.15+$strength_potential*.20+$block_shed_potential*.25+$tackling_potential*.1+$read_potential*.1);
					$cv_overall_now= floor($speed_now*.2+$read_now*.2+$coverage_now*.25+$tackling_now*.2+$strength_now*.15);
					$cv_overall_potential= floor($speed_potential*.2+$read_potential*.2+$coverage_potential*.25+$tackling_potential*.2+$strength_potential*.15);
					
					$overall_now = max($ilb_overall_now,$pr_overall_now,$cv_overall_now)+8;
					$overall_potential = max($ilb_overall_potential,$pr_overall_potential,$cv_overall_potential)+11;
					break;
				case "CB":
					$height = floor(gaussianWeightedRisingRandom(68,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(50,60));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(73,60));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(50,100));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(50,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(50,100));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(30,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(50,100));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(73,60));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(50,100));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(40,100));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(80,60));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(50,100));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.05+$speed_now*.2+$burst_now*.05+$read_now*.2+$jump_now*.05+$coverage_now*.35+$tackling_now*.1)+5;
					$overall_potential = floor($strength_potential*.05+$speed_potential*.2+$burst_potential*.05+$read_potential*.2+$jump_potential*.05+$coverage_potential*.35+$tackling_potential*.1)+5;
					break;
				case "S":
					$height = floor(gaussianWeightedRisingRandom(69,6));
					$weightcoeff = 2.9*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(50,100));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(73,60));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(40,60));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(30,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(50,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(50,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(50,100));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(80,64));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(50,100));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(40,80));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(60,80));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(73,60));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(40,32));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(5,8));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(5,8));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(70,88));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($strength_now*.1+$speed_now*.1+$read_now*.3+$leadership*.1+$tackling_now*.2+$coverage_now*.2)+5;
					$overall_potential = floor($strength_potential*.1+$speed_potential*.1+$read_potential*.3+$leadership*.1+$tackling_potential*.2+$coverage_potential*.2)+5;
					break;
				case "K":
					$height = floor(gaussianWeightedRisingRandom(70,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(30,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(40,100));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(15,40));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(20,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(20,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(20,40));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(5,8));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(20,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(73,60));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(73,60));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(40,52));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($kick_acc_now*.45+$kick_pow_now*.45+$clutch*.1)+4;
					$overall_potential = floor($kick_acc_potential*.45+$kick_pow_potential*.45+$clutch*.1)+4;
					break;
				case "P":
					$height = floor(gaussianWeightedRisingRandom(68,6));
					$weightcoeff = 2.7*(rand(990,1010)/1000);
					$weight = floor($height*$weightcoeff);
						//Strength
						$strength_now = floor(gauss_ms(30,44));
						if ($strength_now > 99) {
							$strength_now = 99;
						} else if ($strength_now < 1) {
							$strength_now = 1;
						}
						$strength_potential = min($strength_now+30,floor(logarithmicWeightedRandom($strength_now,99-$strength_now)));
						
						//Speed
						$speed_now = floor(gauss_ms(40,100));
						if ($speed_now > 99) {
							$speed_now = 99;
						} else if ($speed_now < 1) {
							$speed_now = 1;
						}
						$speed_potential = min($speed_now+30,floor(logarithmicWeightedRandom($speed_now,99-$speed_now)));
						
						//Burst
						$burst_now = floor(gauss_ms(15,40));
						if ($burst_now > 99) {
							$burst_now = 99;
						} else if ($burst_now < 1) {
							$burst_now = 1;
						}
						$burst_potential = min($burst_now+30,floor(logarithmicWeightedRandom($burst_now,99-$burst_now)));
						
						//Carrying
						$carry_now = floor(gauss_ms(20,52));
						if ($carry_now > 99) {
							$carry_now = 99;
						} else if ($carry_now < 1) {
							$carry_now = 1;
						}
						$carry_potential = min($carry_now+30,floor(logarithmicWeightedRandom($carry_now,99-$carry_now)));
						
						//Hands
						$hands_now = floor(gauss_ms(70,52));
						if ($hands_now > 99) {
							$hands_now = 99;
						} else if ($hands_now < 1) {
							$hands_now = 1;
						}
						$hands_potential = min($hands_now+30,floor(logarithmicWeightedRandom($hands_now,99-$hands_now)));
						
						//Elusiveness
						$elusive_now = floor(gauss_ms(20,88));
						if ($elusive_now > 99) {
							$elusive_now = 99;
						} else if ($elusive_now < 1) {
							$elusive_now = 1;
						}
						$elusive_potential = min($elusive_now+30,floor(logarithmicWeightedRandom($elusive_now,99-$elusive_now)));
						
						//Footwork
						$fw_now = floor(gauss_ms(20,40));
						if ($fw_now > 99) {
							$fw_now = 99;
						} else if ($fw_now < 1) {
							$fw_now = 1;
						}
						$fw_potential = min($fw_now+30,floor(logarithmicWeightedRandom($fw_now,99-$fw_now)));
						
						//Read Opposition
						$read_now = floor(gauss_ms(5,8));
						if ($read_now > 99) {
							$read_now = 99;
						} else if ($read_now < 1) {
							$read_now = 1;
						}
						$read_potential = min($read_now+30,floor(logarithmicWeightedRandom($read_now,99-$read_now)));
						
						//Pocket Presence 
						$pocket_now = floor(gauss_ms(5,8));
						if ($pocket_now > 99) {
							$pocket_now = 99;
						} else if ($pocket_now < 1) {
							$pocket_now = 1;
						}
						$pocket_potential = min($pocket_now+30,floor(logarithmicWeightedRandom($pocket_now,99-$pocket_now)));
						
						//Throw Power
						$throw_pow_now = floor(gauss_ms(40,40));
						if ($throw_pow_now > 99) {
							$throw_pow_now = 99;
						} else if ($throw_pow_now < 1) {
							$throw_pow_now = 1;
						}
						$throw_pow_potential = min($throw_pow_now+30,floor(logarithmicWeightedRandom($throw_pow_now,99-$throw_pow_now)));
						
						//Throw Accuracy
						$throw_acc_now = floor(gauss_ms(5,8));
						if ($throw_acc_now > 99) {
							$throw_acc_now = 99;
						} else if ($throw_acc_now < 1) {
							$throw_acc_now = 1;
						}
						$throw_acc_potential = min($throw_acc_now+30,floor(logarithmicWeightedRandom($throw_acc_now,99-$throw_acc_now)));
						
						//Pass Blocking
						$pass_block_now = floor(gauss_ms(5,8));
						if ($pass_block_now > 99) {
							$pass_block_now = 99;
						} else if ($pass_block_now < 1) {
							$pass_block_now = 1;
						}
						$pass_block_potential = min($pass_block_now+30,floor(logarithmicWeightedRandom($pass_block_now,99-$pass_block_now)));
						
						//Run Blocking
						$run_block_now = floor(gauss_ms(5,8));
						if ($run_block_now > 99) {
							$run_block_now = 99;
						} else if ($run_block_now < 1) {
							$run_block_now = 1;
						}
						$run_block_potential = min($run_block_now+30,floor(logarithmicWeightedRandom($run_block_now,99-$run_block_now)));
						
						//Route Running
						$route_now = floor(gauss_ms(10,8));
						if ($route_now > 99) {
							$route_now = 99;
						} else if ($route_now < 1) {
							$route_now = 1;
						}
						$route_potential = min($route_now+30,floor(logarithmicWeightedRandom($route_now,99-$route_now)));
						
						//Jumping
						$jump_now = floor(gauss_ms(20,32));
						if ($jump_now > 99) {
							$jump_now = 99;
						} else if ($jump_now < 1) {
							$jump_now = 1;
						}
						$jump_potential = min($jump_now+30,floor(logarithmicWeightedRandom($jump_now,99-$jump_now)));
						
						//Block Shedding
						$block_shed_now = floor(gauss_ms(5,8));
						if ($block_shed_now > 99) {
							$block_shed_now = 99;
						} else if ($block_shed_now < 1) {
							$block_shed_now = 1;
						}
						$block_shed_potential = min($block_shed_now+30,floor(logarithmicWeightedRandom($block_shed_now,99-$block_shed_now)));
						
						//Coverage
						$coverage_now = floor(gauss_ms(5,8));
						if ($coverage_now > 99) {
							$coverage_now = 99;
						} else if ($coverage_now < 1) {
							$coverage_now = 1;
						}
						$coverage_potential = min($coverage_now+30,floor(logarithmicWeightedRandom($coverage_now,99-$coverage_now)));
						
						//Tackling
						$tackling_now = floor(gauss_ms(30,32));
						if ($tackling_now > 99) {
							$tackling_now = 99;
						} else if ($tackling_now < 1) {
							$tackling_now = 1;
						}
						$tackling_potential = min($tackling_now+30,floor(logarithmicWeightedRandom($tackling_now,99-$tackling_now)));
						
						//Kicking Power
						$kick_pow_now = floor(gauss_ms(73,60));
						if ($kick_pow_now > 99) {
							$kick_pow_now = 99;
						} else if ($kick_pow_now < 1) {
							$kick_pow_now = 1;
						}
						$kick_pow_potential = min($kick_pow_now+30,floor(logarithmicWeightedRandom($kick_pow_now,99-$kick_pow_now)));
						
						//Kicking Accuracy
						$kick_acc_now = floor(gauss_ms(40,52));
						if ($kick_acc_now > 99) {
							$kick_acc_now = 99;
						} else if ($kick_acc_now < 1) {
							$kick_acc_now = 1;
						}
						$kick_acc_potential = min($kick_acc_now+30,floor(logarithmicWeightedRandom($kick_acc_now,99-$kick_acc_now)));
						
						//Punting Accuracy
						$punt_acc_now = floor(gauss_ms(73,60));
						if ($punt_acc_now > 99) {
							$punt_acc_now = 99;
						} else if ($punt_acc_now < 1) {
							$punt_acc_now = 1;
						}
						$punt_acc_potential = min($punt_acc_now+30,floor(logarithmicWeightedRandom($punt_acc_now,99-$punt_acc_now)));
						
						//Leadership
						$leadership = floor(gauss_ms(50,100));
						if ($leadership > 99) {
							$leadership = 99;
						} else if ($leadership < 1) {
							$leadership = 1;
						}
						
						//Clutch
						$clutch = floor(gauss_ms(50,100));
						if ($clutch > 99) {
							$clutch = 99;
						} else if ($clutch < 1) {
							$clutch = 1;
						}
						
						//Durability
						$durability = floor(gauss_ms(50,100));
						if ($durability > 99) {
							$durability = 99;
						} else if ($durability < 1) {
							$durability = 1;
						}
						
						//Toughness
						$toughness = floor(gauss_ms(50,100));
						if ($toughness > 99) {
							$toughness = 99;
						} else if ($toughness < 1) {
							$toughness = 1;
						}
						
					$overall_now = floor($punt_acc_now*.5+$kick_pow_now*.5)+1;
					$overall_potential = floor($punt_acc_potential*.5+$kick_pow_potential*.5)+1;
					break;
			}
			if ($overall_now > 99) {
				$overall_now = 99;
			}
			
			if ($overall_potential > 99) {
				$overall_potential = 99;
			}
			
			$player_insert = mysqli_query($conn, "INSERT INTO `player` (start_year,league,firstname,lastname,position) VALUES ($year,$leagueid,'$firstname','$lastname','$position');");
			$playerid = mysqli_insert_id($conn);
			$att_query = "INSERT INTO `attributes` (player,year,overall_now,overall_pot,height,weight,strength_now,strength_pot,speed_now,speed_pot,burst_now,burst_pot,carry_now,carry_pot,hands_now,hands_pot,elusive_now,elusive_pot,fw_now,fw_pot,read_now,read_pot,pocket_now,pocket_pot,throw_pow_now,throw_pow_pot,throw_acc_now,throw_acc_pot,pass_block_now,pass_block_pot,run_block_now,run_block_pot,route_now,route_pot,jump_now,jump_pot,block_shed_now,block_shed_pot,coverage_now,coverage_pot,tackling_now,tackling_pot,kick_pow_now,kick_pow_pot,kick_acc_now,kick_acc_pot,punt_acc_now,punt_acc_pot,leadership,clutch,durability,toughness) VALUES ($playerid,$year,$overall_now,$overall_potential,$height,$weight,$strength_now,$strength_potential,$speed_now,$speed_potential,$burst_now,$burst_potential,$carry_now,$carry_potential,$hands_now,$hands_potential,$elusive_now,$elusive_potential,$fw_now,$fw_potential,$read_now,$read_potential,$pocket_now,$pocket_potential,$throw_pow_now,$throw_pow_potential,$throw_acc_now,$throw_acc_potential,$pass_block_now,$pass_block_potential,$run_block_now,$run_block_potential,$route_now,$route_potential,$jump_now,$jump_potential,$block_shed_now,$block_shed_potential,$coverage_now,$coverage_potential,$tackling_now,$tackling_potential,$kick_pow_now,$kick_pow_potential,$kick_acc_now,$kick_acc_potential,$punt_acc_now,$punt_acc_potential,$leadership,$clutch,$durability,$toughness)";
			$att_insert = mysqli_query($conn,$att_query);
			if (!$att_insert) {
				printf("Error: %s\n", mysqli_error($conn));
				echo "<br>Query: ".$att_query;
				exit();
			}
		}
}

function create_salaries($playerid,$year,$team,$position,$rating) {
	switch($position) {
		case "QB":
			if ($rating > 70 && $rating < 95) {
				$totsal = round(717333*$rating-50241667,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(717333*$rating-50241667,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "RB":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "FB":
			if ($rating > 83 && $rating < 95) {
				$totsal = round(186667*$rating-14733333,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(186667*$rating-14733333,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "WR":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(612000*$rating-45140000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(612000*$rating-45140000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "TE":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(362000*$rating-26390000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(362000*$rating-26390000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "G":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "C":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "T":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DE":
			if ($rating > 72 && $rating < 95) {
				$totsal = round(532174*$rating-37556522,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(532174*$rating-37556522,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "DT":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(612000*$rating-45140000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(612000*$rating-45140000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "LB":
			if ($rating > 69 && $rating < 95) {
				$totsal = round(393846*$rating-26415385,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(393846*$rating-26415385,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "CB":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "S":
			if ($rating > 76 && $rating < 95) {
				$totsal = round(486316*$rating-36200000,-4);
				if ($rating > 90) {
					$bonus = round($totsal/2,-4);
				} else if ($rating > 80) {
					$bonus = round($totsal/3,-4);
				} else {
					$bonus = round($totsal/4,-4);
				}
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(486316*$rating-36200000,-4);
				$bonus = round($totsal/2,-4);
				$salary = $totsal - $bonus;
			} else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "K":
			if ($rating > 76) {
				$salary = round(20236*exp(.05*$rating),-4);
				$bonus = round($salary/3,-4);
			}
			else {
				$salary = 730000;
				$bonus = 30000;
			}
			break;
		case "P":
		if ($rating > 76) {
				$salary = round(20236*exp(.05*$rating),-4);
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
	$caphit = $salary + $bonus;
	for ($i=0;$i<4;$i++) {
		$contract_year = $year+$i;
		
		mysqli_query($conn, "INSERT INTO contract (player,year,team,bonus,base,caphit) VALUES ($playerid,$contract_year,$team,$bonus,$salary,$caphit)");
	}
	
}

function fa_demand($position,$rating,$exp,$years) {
	switch($position) {
		case "QB":
			
				$salary = round(750*exp(.1*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "RB":
			$salary = round(7520*pow($rating,2)-1014193*$rating+34518130,-4);
			$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "FB":
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "WR":
				$salary = round(469746*$rating-35649291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "TE":
				$salary = round(1198*pow($rating,2)+63882*$rating-11053232,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "G":
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "C":
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "T":
				$salary = round(8714*pow($rating,2)-1200500*$rating+41384657,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "DE":
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "DT":
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "LB":
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "CB":
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "S":
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "K":
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		case "P":
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				if ($exp == 0 && $salary < 420000) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1 && $salary < 485000) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2 && $salary < 550000) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3 && $salary < 615000) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6 && $salary < 730000) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9 && $salary < 815000) {
					$salary = 815000;
					$bonus = 40000;
				} else if ($exp > 9 && $salary < 905000) {
					$salary = 905000;
					$bonus = 50000;
				}	
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			break;
		default:
			//That's not supposed to happen
	}
	
	return $salary_array;
}

?>