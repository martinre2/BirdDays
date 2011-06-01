<?php
define("SCOPEAT","https://www.google.com/calendar/feeds/default/owncalendars/full");
define("GDATAAT","GData-Version: 2");
define("HEADERAT","Content-type: application/atom+xml");

header('Content-type: application/atom+xml; charset=UTF-8');

function trackGsessionid(){
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, $_SESSION['calendarFeed']."/batch?oauth_token=".$_SESSION['sessionToken']);
	curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($handler);
	$nwurl = curl_getinfo($handler, CURLINFO_EFFECTIVE_URL);
	curl_close($handler);
	
	if (isset($response))
		return $nwurl;
	
	else
		return 0;

}


function events($evt){
		//echo $_SESSION['calendarFeed'];

		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, trackGsessionid());
		curl_setopt($handler, CURLOPT_HTTPHEADER, array(HEADERAT));
		curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handler, CURLOPT_POSTFIELDS, $evt);
		curl_setopt($handler, CURLOPT_POST, true);
		$response = curl_exec($handler);
		
		if($response == false){
			echo 'Error cURL en la peticion POST: ' . curl_error($handler);
		}

		//echo $response;
		//var_dump(json_decode($response));
	}


?>
