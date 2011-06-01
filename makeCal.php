<?php
session_start();

include ("./oauth2callback/createCalendar.php");
include ("./oauth2callback/createEventAtom.php");


define("EVENTS","http://reddementes.com/birddays/manejadorJson.php?eventos");
define("DES","Este evento fue creado por BirdDays");
define("LUGAR","Mexico");
define('PERIODO',20);


function atomEvent($event,$nEvt){
	$atom = "<entry>";
	$atom .= "<batch:id>Insert item".$nEvt."</batch:id>";
	$atom .= "<batch:operation type='insert' />";
	$atom .= "<category scheme='http://schemas.google.com/g/2005#kind' ";
	$atom .= " term='http://schemas.google.com/g/2005#event'></category>";
	$atom .= "<title type='text'>".$event['nombre']."</title>";
	$atom .= "<content type='text'>".DES."</content>";
	$atom .= "<gd:transparency ";
	$atom .= " value='http://schemas.google.com/g/2005#event.opaque'>";
	$atom .= "</gd:transparency>";
	$atom .= "<gd:eventStatus ";
	$atom .= "value='http://schemas.google.com/g/2005#event.confirmed'>";
	$atom .= "</gd:eventStatus>";
	$atom .= "<gd:where valueString='".LUGAR."'></gd:where>";
	$atom .= "<gd:recurrence>DTSTART;VALUE=DATE:".$event['inicio']."\r\n";
	$atom .= "RRULE:FREQ=YEARLY;UNTIL=".untilDate($event['inicio'])."\r\n";
	$atom .= "</gd:recurrence></entry>";



	return $atom;

}

function batch(){
	$bt = "<feed xmlns='http://www.w3.org/2005/Atom' ";
	$bt .= "xmlns:app='http://www.w3.org/2007/app' ";
	$bt .= "xmlns:batch='http://schemas.google.com/gdata/batch' ";
	$bt .= "xmlns:gCal='http://schemas.google.com/gCal/2005' ";
	$bt .= "xmlns:gd='http://schemas.google.com/g/2005'>";
	$bt .= "<category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event' />";

	$eventos = $_SESSION['eventos']['eventos'];

	for ($i = 0 ; $i<sizeof($eventos) ; $i++){
		$bt .= atomEvent($eventos[$i],$i);
	}

	$bt .= "</feed>";

	return $bt;
}

function untilDate($DATE){

	$date = (substr($DATE,0,4)+PERIODO).substr($DATE,4);

	return $date;
}

function id($url){
	$ar = split('/',$url);
	return $ar[5];
}


if (isset($_SESSION['sessionToken'])){

	$title = ($_GET['nombreCal']!="")?$_GET['nombreCal']:'Calendario BirdDays';
	$decode = $_SESSION['eventos'];

	//echo $title;


	if (make($title)){
		events( batch() );
		header('location:https://www.google.com/calendar/embed?src='.id($_SESSION['calendarFeed']).'&mode=AGENDA');
	}


}else
	echo "No has dado permisos para crear calendarios!!"



?>
