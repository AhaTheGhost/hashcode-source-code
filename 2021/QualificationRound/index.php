<?php
	header('Content-type: text/plain');

	$files = ['a_example', 'b_read_on', 'c_incunabula', 'd_tough_choices', 'e_so_many_books', 'f_libraries_of_the_world'];

	$startTimer = microtime(true);

	$inFile = file_get_contents('a' . ".txt");

	$data = preg_split('/[\ \n]+/', $inFile);
	//unset($inFile);
	array_pop($data);

	$D = $data[0]; // simulation timelaps
	$I = $data[1]; // Intersections
	$S = $data[2]; // Streets - Street index
	$V = $data[3]; //number of cars - car array index
	$bouns = $data[4]; // Intersections

	$streets;
	$carPath;

	$x = 0;
	$handle = fopen("a.txt", "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
	    	$streetData = preg_split('/[\ \n]+/', $line);

	    	if($x >= 1 && $x <= $data[2]){
	    		$streets[] = ['bID' => $streetData[0], 'eID' => $streetData[1], 'name' => $streetData[2], 'L' => $streetData[3]];
	    	}

	    	if($x > $data[2]){
	    		array_pop($streetData);
	    		$carPath[] = $streetData;
	    	}

	    	$x++;
	    }

	    fclose($handle);
	} else {
	    // error opening the file.
	}


	//Algo

	//Get intersections
	$Is[] = -9999999;
	$usedIntersections;

	for($i = 0; $i < sizeof($carPath); $i++){
		for($j = 2; $j < sizeof($carPath[$i]) - 1; $j++){
			for($x = 0; $x < sizeof($streets); $x++){
				if($streets[$x]['name'] == $carPath[$i][$j]){
					if(!in_array($streets[$x]['bID'], $Is)){
						$Is[] = $streets[$x]['bID'];
						$usedIntersections[] = array('is' => $streets[$x]['bID']);
					}

					if(!in_array($streets[$x]['eID'], $Is)){
						$Is[] = $streets[$x]['eID'];
						$usedIntersections[] = array('is' => $streets[$x]['eID']);
					}
				}
			}
		}
	}
	//Get intersections

	//Get intersection income routes

	for ($i = 0; $i < sizeof($usedIntersections); $i++) { 
		foreach ($streets as $key => $value) {
			if($value['eID'] == $usedIntersections[$i]['is']){
				$usedIntersections[$i][] = $value['name'] . ' 1';
			}
		}
	}

	//Get intersection income routes


	$lines = "" . sizeof($usedIntersections);
	for($i = 0; $i < sizeof($usedIntersections); $i++){
		$lines .= PHP_EOL . $usedIntersections[$i]['is'];
		$lines .= PHP_EOL . (sizeof($usedIntersections[$i]) - 1);
		for($j = 0; $j < sizeof($usedIntersections[$i]) - 1; $j++){
			$lines .= PHP_EOL . $usedIntersections[$i][$j];
		}
	}

	echo $lines;
	//Algo



	$EndTimer = microtime(true);
	echo " in " . round((($EndTimer - $startTimer) * 1000)) . " millisesocnds\n";

?>
