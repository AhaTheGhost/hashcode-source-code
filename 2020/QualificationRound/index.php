<?php
	header('Content-type: text/plain');

	$files = ['a_example', 'b_read_on', 'c_incunabula', 'd_tough_choices', 'e_so_many_books', 'f_libraries_of_the_world'];

	for($fileIndex = 0; $fileIndex < 6; $fileIndex++){
		$startTimer = microtime(true);

		unset($bookScore); unset($booksOfLib); unset($library);

		$inFile = file_get_contents($files[$fileIndex] . ".txt");

		$data = preg_split('/[\ \n]+/', $inFile);
		unset($inFile);
		array_pop($data);
		$numOfBooks = (int) $data[0];
		$NumOfLibs = (int) $data[1];
		$daysOfScan = (int) $data[2];

		for($i = 3; $i < $numOfBooks + 3; $i++){
			$bookScore[] = $data[$i];
		}

		$next = sizeof($bookScore) + 3;

		for($lib = 0; $lib < $NumOfLibs; $lib++){	
			unset($booksOfLib);
			$libBooks = $data[$next]; $next++;
			$signupProcess = $data[$next]; $next++;
			$shipsPerDay = $data[$next]; $next++;
			
			for($i = $next; $i < $libBooks + $next; $i++){
				$booksOfLib[] = $data[$i];
				if($i == $libBooks + $next - 1) { 
					$next = $i + 1;
					break;
				}
			}

			$library[] = [
				'libID' => $lib,
			    'libBooks' => $libBooks,
			    'signupProcess' => $signupProcess,
			    'shipsPerDay' => $shipsPerDay,
			    'booksOfLib' => $booksOfLib
			];
		}
		unset($lib); unset($libBooks); unset($signupProcess); unset($shipsPerDay); unset($booksOfLib); unset($data);
		
		usort($library, function($a, $b) { return $a['signupProcess'] <=> $b['signupProcess']; });

		$temp;
		foreach ($bookScore as $key => $value) { $temp[] = $key; }

		$sbStr = "";
		$lastProcessDay = 0;
		$lastDay = 0;
		$libs = 0;
		for($lib = 0; $lib < $NumOfLibs; $lib++){
			$lastProcessDay += $library[$lib]['signupProcess'];
			$lastDay = $lastProcessDay;
			$scaned = 0;
			$item = 0;
			$shippedBooks = "";
			for($i = $lastDay; $i < $daysOfScan + 1 ; $i++){
				for($sb = 0; $sb < $library[$lib]['shipsPerDay'] && sizeof($library[$lib]['booksOfLib']) > $item; $sb++){
					if(in_array($library[$lib]['booksOfLib'][$item], $temp)){
						$shippedBooks .= $library[$lib]['booksOfLib'][$item] . " ";
						unset($temp[$item]);
						$scaned++;
					}
					$item++;
					$sb--;
				}
			}
			if($scaned != 0){
				$libs++;
				$sbStr .= $library[$lib]['libID'] . " ". $scaned ."\n" . substr($shippedBooks, 0, -1) . "\n";
			}
		}
		unset($NumOfLibs); unset($lib); unset($lastProcessDay); unset($lastDay);
		unset($scaned); unset($shippedBooks); unset($sb); unset($temp);

		$outputStr = substr($libs . "\n$sbStr", 0, -1);

		try {
		    $saveFile = fopen($files[$fileIndex] . "_out.txt", "w");
			fwrite($saveFile, $outputStr);
			fclose($saveFile);
			echo $files[$fileIndex] . "_out.txt Done";
		}
		catch (exception $e) {
		    echo "Error: " . $e;
		}
		unset($saveFile); unset($outputStr); unset($libs); unset($sbStr);

		$EndTimer = microtime(true);
		echo " in " . round((($EndTimer - $startTimer) * 1000)) . " millisesocnds\n";
	}
?>