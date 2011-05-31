<?php
session_start();

require('conf.php');
define("scope","https://www.google.com/calendar/feeds/");
define("next","http://reddementes.com/birddays/oauth2callback/");
define("BASE","http://reddementes.com/birddays/");



function getCode(){

	$nwurl= "https://accounts.google.com/o/oauth2/auth?client_id=".CLIENT_ID. "&redirect_uri=" .next. "&scope=" .scope. "&response_type=code";

	echo '<script type=\'text/javascript\'>top.location.href = \'' . $nwurl .  '\';</script>';
	return;

}

function goBase(){

	echo '<script type=\'text/javascript\'>top.location.href = \'' . BASE .  '\';</script>';
	return;

}


function getToken($code){
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
	curl_setopt($handler, CURLOPT_POST, true);
	curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
	$fields="code=".$code.
		"&client_id=".CLIENT_ID.
		"&client_secret=".CLIENT_SECRET.
		"&redirect_uri=".next.
		"&grant_type=authorization_code";
	curl_setopt($handler, CURLOPT_POSTFIELDS, $fields);
	$response = curl_exec($handler);
	
	if($response == false){
		echo 'Error cURL en la peticion POST: ' . curl_error($handler);
	}
	curl_close($handler);
	$decode = json_decode($response, true);

	$_SESSION['sessionToken'] = $decode['access_token'];

	goBase();

	return;

}
if (isset($_SESSION['sessionToken'])) {
	        header('location: '.BASE."?ssid=".session_id());
}

elseif (isset($_GET['code'])){

	$nwurl= next.'/?selfcode='. $_GET['code'];

	echo '<script type=\'text/javascript\'>top.location.href = \'' . $nwurl .  '\';</script>';
	return;
	
}

elseif (isset($_GET['error'])){
	echo "<H1>ERROR:". $_GET['error']."</H1> ";
	return;
}

elseif(isset($_GET['selfcode'])){
	getToken($_GET['selfcode']);
}

else{
	getCode();
}
?>
