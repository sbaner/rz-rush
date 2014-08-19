<?php

if(isset($_POST['position'])) {
	$position = $_POST['position'];
	$rating = $_POST['rating'];
	$exp = $_POST['exp'];
	$years = $_POST['years'];
}

switch($position) {
		case "QB":
				$salary = round(144.82*exp(.1222*$rating),-4);
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
			$salary = round(198.31*exp(.1142*$rating),-4);
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
				$salary = round(264.256*exp(.09433*$rating),-4);
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
				$salary = round(5.8163*exp(.1525*$rating),-4);
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
				$salary = round(198.31*exp(.1142*$rating),-4);
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
				$salary = round(198.31*exp(.1142*$rating),-4);
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
				$salary = round(198.31*exp(.1142*$rating),-4);
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
				$salary = round(36.59*exp(.1356*$rating),-4);
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
				$salary = round(198.31*exp(.1142*$rating),-4);
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
				$salary = round(777.76*exp(.09932*$rating),-4);
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
				$salary = round(390458.1*$rating-28591104.7,-4);
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
				$salary = round(390458.1*$rating-28591104.7,-4);
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