<?php

if(isset($_POST['position'])) {
	$position = $_POST['position'];
	$rating = $_POST['rating'];
	$exp = $_POST['exp'];
	$years = $_POST['years'];
}

switch($position) {
		case "QB":
				if ($rating < 95) {
				$totsal = round(717333*$rating-50241667,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
				} else if ($rating > 94) {
					$rating = 94;
					$totsal = round(717333*$rating-50241667,-4);
					$bonus = round($totsal/4,-4);
					$salary = $totsal - $bonus;
				}
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
			if ($rating < 95) {
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
			}
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
			if ($rating < 95) {
				$totsal = round(186667*$rating-14733333,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			} else if ($rating > 94) {
				$rating = 94;
				$totsal = round(186667*$rating-14733333,-4);
				$bonus = round($totsal/4,-4);
				$salary = $totsal - $bonus;
			}
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
			if ($rating < 95) {
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
			if ($rating < 95) {
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
			}
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
			}
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
			}
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
			if ($rating < 95) {
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
			if ($rating < 95) {
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
			if ($rating < 95) {
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
			if ($rating < 95) {
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
			}
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
			if ($rating < 95) {
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
			}
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
				$salary = round(20236*exp(.05*$rating),-4);
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
				$salary = round(20236*exp(.05*$rating),-4);
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
	
	echo json_encode($salary_array);
	exit();
?>