<?php
define("SCOPE","https://www.google.com/calendar/feeds/default/owncalendars/full?alt=jsonc");
define("GDATA","GData-Version: 2");


session_start();
header('Content-type: application/json; charset=UTF-8');

if (isset($_SESSION['sessionToken'])) {
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, SCOPE."&oauth_token=".$_SESSION['sessionToken']);
	curl_setopt($handler, CURLOPT_HTTPHEADER, array(GDATA));
	curl_setopt($handler, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true); 	
	curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);	
	$response = curl_exec ($handler);  
	curl_close($handler);  
	$decode=json_decode($response,true);
	//var_dump(json_decode($response,true));
	$items= $decode['data']['items'];
	$json = array(
		'displayName' => $decode['data']['author']['displayName']);
	
	$calendars=array();
	foreach($items as $cal){
		$calendars[] = array('Calendar title' => $cal['author']['displayName']);

	}
	$json['Clendars']=$calendars;

	echo json_encode($json);

}
?>
