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


function gaussianWeightedFallingRandom( $LowValue,
                                        $maxRand )
{
    //  Adjust a gaussian random value to fit within our specified range
    //      by 'trimming' the extreme values as the distribution curve
    //      approaches +/- infinity
    //  The division by 4 is an arbitrary value to help fit the distribution
    //      within our required range
    $rand_val = $LowValue + $maxRand ;
    while (($rand_val < $LowValue) || ($rand_val >= ($LowValue + $maxRand))) {
        $rand_val = floor((abs(gauss()) / 4) * $maxRand) + $LowValue ;
    }

    return $rand_val ;
}   //  function gaussianWeightedFallingRandom()
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

$qbheight = array();
$numovr90 = 0;
for ($i=0; $i<2500; $i++) {
	$height = floor(gauss_ms($_GET['min'],$_GET['rand']));
	array_push($qbheight, $height);
	echo $height."<br>";
	if ($height > 90) {
		$numovr90++;
	}
	
}
$meanheight = array_sum($qbheight) / count($qbheight);
echo "Mean height: ".$meanheight;
echo "<br>".$numovr90." players over 90.";

?>