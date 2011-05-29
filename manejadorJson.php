<?php

session_start();
header('Content-type: application/json; charset=UTF-8');
include('funcionesLlamadasApis.php');


if (isset($_GET['cumplesMes'])) {
    echo json_encode($_SESSION['cumplesMes']);
}

if (isset($_GET['eventos'])) {
    if(isset($_GET['nombreCal']))
        $nombreCal = html_entity_decode($_GET['nombreCal']);
    else
        $nombreCal = 'Calendario de cumpleaños BirdDays';
    $_SESSION['eventos']['nombre_calendario'] = $nombreCal;
    echo json_encode($_SESSION['eventos']);
}
if (isset($_GET['lugares'])) {
    echo json_encode($_SESSION['lugares']);
}
?>
