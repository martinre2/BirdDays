<?php
define("SCOPE","https://www.google.com/calendar/feeds/default/owncalendars/full?alt=jsonc");
define("GDATA","GData-Version: 2");
define("HEADER","Content-type: application/json");

session_start();
header('Content-type: application/json; charset=UTF-8');

function trackGsessionid(){
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, SCOPE."&oauth_token=".$_SESSION['sessionToken']);
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


if (isset($_GET['title'])){
	$json = array(
		"data" => array(
			"title" => $_GET['title'],
			"details" => "This Calendar contains the birthdays of your facebook friends",
			"timeZone" => "America/Mexico_City",
			"hidden" => false,
			"color" => "#2952A3",
			"location" => "Mexico"

			)
		);
	//$json = json_encode($json);


	if (isset($_SESSION['sessionToken'])) {

		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, trackGsessionid());
		curl_setopt($handler, CURLOPT_HTTPHEADER, array(HEADER));
		curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($json));
		curl_setopt($handler, CURLOPT_POST, true);
		$response = curl_exec($handler);
		
		if($response == false){
			echo 'Error cURL en la peticion POST: ' . curl_error($handler);
		}

		echo $response;
	}

}else
	echo "You need give a title parameter";

?>
