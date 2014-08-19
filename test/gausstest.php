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

$mean = $_GET['mean'];
$std = $_GET['std'];

	echo floor(gauss_ms($mean,$std));

?>