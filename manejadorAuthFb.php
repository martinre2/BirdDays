<?php

session_start();
include('configuraciones.php');
$urlApp = 'http://localhost/birddays/';

/*
if (isset($_POST['nombreCal']))
    $_SESSION['nombreCal'] = $_POST['nombreCal'];
*/

if (isset($_SESSION['token_acceso_facebook'])) {
    if (time() >= $_SESSION['expira_token_facebook']) {
        session_unset();
        header('location: ' . URL_MANEJADOR_FB);
    }
    header('location: ' . $urlApp . 'manejadorInfo.php');
} elseif (isset($_GET['code'])) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?client_id=' . FACEBOOK_CLIENT_ID . '&redirect_uri=' . URL_MANEJADOR_FB . '&client_secret=' . FACEBOOK_CLIENT_SECRET . '&code=' . $_GET['code']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $strRespuesta = curl_exec($curl);
    if ($strRespuesta == false)
        echo 'Tenemos problemas, por favor intenta de nuevo';
    parse_str($strRespuesta, $arrRespuestas);
    $_SESSION['token_acceso_facebook'] = $arrRespuestas['access_token'];
    $_SESSION['expira_token_facebook'] = time() + $arrRespuestas['expires'];
    header('location: ' . URL_MANEJADOR_FB);
    return;
}
elseif (isset($_GET['error'])) {
    echo '<h1>El usuario denego el acceso a nuestra aplicacion: </h1>';
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';
    return;
} else {
    $permisos = 'friends_birthday,user_location';
    echo '<script type=\'text/javascript\'>top.location.href = \'https://www.facebook.com/dialog/oauth?client_id=' . FACEBOOK_CLIENT_ID . '&redirect_uri=' . URL_MANEJADOR_FB . '&scope=' . $permisos . '\';</script>';
    return;
}
?>
