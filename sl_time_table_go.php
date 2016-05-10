<?php

$str = file_get_contents('http://api.sl.se/api2/realtimedepartures.json?key=d4b86662a79c47678342b698bd238887&siteid=1026&timewindow=60');
	$json = json_decode($str, true);

	$text = (isset($json['ResponseData']['StopPointDeviations'][0]['Deviation']['Text'])) ?: '';

	if (!empty($text)) {
		$cancelled1 = "Inställd";
		}
	
	 else if (!empty($json['ResponseData']['Buses'][0]['Destination']))
	{	
	
		for ($i=0; $i < 3; $i++) {
		
			$destination = $json['ResponseData']['Buses'][$i]['Destination'];		
		
				if ($destination ==	"Centralen")
			{
				$timeAvgång = $json['ResponseData']['Buses'][$i]['ExpectedDateTime'];
				break 1;
				
			}
		
			else{
				$destination = $json['ResponseData']['Buses'][$i]['Destination'];
				
				}

		}
		$date=date_create($timeAvgång);

		$h_bus=date_format($date, 'G');
		$m_bus=date_format($date, 'i');
		$s_bus=date_format($date, 's');

	}

	else {
		$error1 = "Error";
	}
	$str2 = file_get_contents('http://api.sl.se/api2/realtimedepartures.json?key=d4b86662a79c47678342b698bd238887&siteid=314&timewindow=60');

	$json2 = json_decode($str2, true);
	$text2 = (isset($json2['ResponseData']['StopPointDeviations'][0]['Deviation']['Text'])) ?: '';

	if (!empty($text2)) {
		$cancelled2 = "Inställd";

	} else if (!empty($json2['ResponseData']['Ships'][0]['Destination'])) 
	{	error_log('has destination!');
	for ($j=0; $j < 3; $j++) {
			
	$destination2 = $json2['ResponseData']['Ships'][$j]['Destination'];		
			
	if ($destination2 == "Slussen")
	{
	$timeAvgång2 = $json2['ResponseData']['Ships'][$j]['ExpectedDateTime'];
		break 1;
	}
	else{
			$destination2 = $json2['ResponseData']['Ships'][$j]['Destination'];
				
		}

		}
		$date2=date_create($timeAvgång2);

		$h_boat=date_format($date2, 'G');
		$m_boat=date_format($date2, 'i');
		$s_boat=date_format($date2, 's');

	
		
	}
	else {
		$error2="Error";

	}


echo json_encode([
	'cancelled1' => $cancelled1,
	'bus_hour' => $h_bus,
	'bus_minute' => $m_bus,
	'bus_second' => $s_bus,
	'cancelled2' => $cancelled2,
	'boat_hour' => $h_boat,
	'boat_minute' => $m_boat,
	'boat_second' => $s_boat,
	'error1' => $error1,
	'error2' => $error2
	]);

?>