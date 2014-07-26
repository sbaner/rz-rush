<?php

if(isset($_POST['position'])) {
	$position = $_POST['position'];
	$rating = $_POST['rating'];
	$exp = $_POST['exp'];
	$years = $_POST['years'];
}

switch($position) {
		case "QB":
			if ($rating > 69) {
				$salary = round(750*exp(.1*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "RB":
			if ($rating > 74) {
			$salary = round(7520*pow($rating,2)-1014193*$rating+34518130,-4);
			$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "FB":
			if ($rating > 80) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "WR":
			if ($rating > 77) {
				$salary = round(469746*$rating-35649291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "TE":
			if ($rating > 76) {
				$salary = round(1198*pow($rating,2)+63882*$rating-11053232,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "G":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "C":
			if ($rating > 77) {
				$salary = round(280382*$rating-20881291,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "T":
			if ($rating > 77) {
				$salary = round(8714*pow($rating,2)-1200500*$rating+41384657,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "DE":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "DT":
			if ($rating > 75) {
				$salary = round(9334*pow($rating,2)-1295236*$rating+45245133,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "LB":
			if ($rating > 72) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "CB":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "S":
			if ($rating > 76) {
				$salary = round(1100*exp(.09*$rating),-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "K":
			if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		case "P":
		if ($rating > 76) {
				$salary = round(4781*pow($rating,2)-737820*$rating+29148880,-4);
				$bonus = round($salary/3,-4);
				$discount = pow(1.05,$years-1);
				$salary = round($salary/$discount,-4);
				
				for($i=0;$i<$years;$i++) {
					$year_salary = round($salary*pow(1.13,$i),-4);
					if ($year_salary > 15000000) {
						$year_salary = 15000000;
					}
					$year_array = [$i+1,$year_salary,$bonus];
					$salary_array[]=$year_array;
				}
			} else {
				if ($exp == 0) {
					$salary = 420000;
					$bonus = 0;
				} else if ($exp == 1) {
					$salary = 485000;
					$bonus = 10000;
				} else if ($exp  == 2) {
					$salary = 550000;
					$bonus = 20000;
				} else if ($exp == 3) {
					$salary = 615000;
					$bonus = 30000;
				} else if ($exp >= 4 && $exp <= 6) {
					$salary = 730000;
					$bonus = 30000;
				} else if ($exp >=7 && $exp <=9) {
					$salary = 815000;
					$bonus = 40000;
				} else {
					$salary = 905000;
					$bonus = 50000;
				}	
				$salary_array = [
				  [1,$salary,$bonus],
				  [2,$salary,$bonus],
				  [3,$salary,$bonus],
				  [4,$salary,$bonus],
				  [5,$salary,$bonus]
				  ];
			}
			break;
		default:
			//That's not supposed to happen
	}
	
	echo json_encode($salary_array);
	exit();
?>