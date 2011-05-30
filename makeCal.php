<?php
define("EVENTS","http://reddementes.com/birddays/manejadorJson.php?eventos");
include("./oauth2callback/createCalendar.php");

if (isset($_GET['title'])){
	if(make($_GET['title'])){
		echo $_SESSION['calendarFeed'];
		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, EVENTS);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($handler);
		curl_close($handler);
		echo $response;
		echo $_SESSION['eventos'];

	}
}else
	echo "Need a Title";

?>
