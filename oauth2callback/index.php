<?php

require('conf.php');
define("scope","https://www.google.com/calendar/feeds/");
define("next","http://birddays.reddementes.com/oauth2callback");

session_start();

function getCode(){
	echo "Adrento";
	echo "<a href='https://accounts.google.com/o/oauth2/auth?client_id=".CLIENT_ID. "&redirect_uri=" .next. "&scope=" .scope. "&response_type=code'>LogIn</a>";
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

}

if(! isset($_SESSION['sessionToken']) && isset($_GET['token'])) {
	/*AuthSubSessionToken*/
	$handler = curl_init();
	curl_setopt($handler, CURLOPT_URL, "https://www.google.com/accounts/AuthSubSessionToken");
	curl_setopt($handler, CURLOPT_HTTPHEADER, array("Authorization: AuthSub token=\"".$_SESSION['sessionToken']."\""));     
	$response = curl_exec ($handler);  
	curl_close($handler);  
	echo $response;  

	$_SESSION['sessionToken'] = $_GET['token'];

	    echo "---->".$_SESSION['sessionToken'];
}

if (isset($_GET['error'])){
	echo "<H1>ERROR:". $_GET['error']."</H1> ";
}

if (isset($_GET['code'])&& !isset($_SESSION['sessionToken'])){
	getToken($_GET['code']);
}


if (isset($_SESSION['sessionToken'])) {
	    echo ("<span>Your tokenID is".$_SESSION['sessionToken']."</span>");
}
else{
	getCode();
	    
}
?>
