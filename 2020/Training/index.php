<?php
	header('Content-type: text/plain');

	$files = ['a_example', 'b_small', 'c_medium', 'd_quite_big', 'e_also_big'];

	for($fileIndex = 0; $fileIndex < 5; $fileIndex++){
		$startTimer = microtime(true);

		unset($pizzaIndex);
		$inFile =  file_get_contents($files[$fileIndex] . ".in");

		$pizza = preg_split('/[\ \n]+/', $inFile);
		array_pop($pizza); //Remove last element which is always newline
		$maxSlices = $pizza[0];
		$maxPizzas = $pizza[1] - 1;

		unset($pizza[0]);
		unset($pizza[1]);
		sort($pizza);

		$totalSlices1 = 0;

		for ($i = $maxPizzas; $i >= 0; $i--) { 
			if(($totalSlices1 + $pizza[$i]) <= $maxSlices){
				$totalSlices1 += $pizza[$i];
				$pizzaIndex[] = $i;
			}
		}

		asort($pizzaIndex);
		$pizzaIndex = array_values($pizzaIndex);

		$pizzaStr = sizeof($pizzaIndex) . "\n";
		for ($i = 0; $i < sizeof($pizzaIndex); $i++) {  $pizzaStr .= $pizzaIndex[$i] . " "; }
		substr($pizzaStr, 0, -1);

		try {
		    $saveFile = fopen($files[$fileIndex] . ".out", "w");
			fwrite($saveFile, $pizzaStr);
			fclose($saveFile);
			echo $files[$fileIndex] . ".out Done";
		}
		catch (exception $e) {
		    echo "Error: " . $e;
		}

		$EndTimer = microtime(true);
		echo " in " . round((($EndTimer - $startTimer) * 1000)) . " millisesocnds\n";
	}
?>