<?php

session_start();
include('funcionesLlamadasApis.php');

$usuario = llamaApiFacebook('https://graph.facebook.com/me');
$idUsuario = $usuario['id'];
$ubicacionUsuario = null;
if (isset($usuario['location'])) {
    $idUbicacion = $usuario['location']['id'];
    $ubicacion = llamaApiFacebook('https://graph.facebook.com/' . $idUbicacion);
    if (isset($ubicacion['location']['latitude']) && isset($ubicacion['location']['latitude']))
        $ubicacionUsuario = array('latitud' => $ubicacion['location']['latitude'], 'longitud' => $ubicacion['location']['longitude']);
}
$consulta = "SELECT uid,name,birthday_date FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $idUsuario) ORDER BY birthday_date";
$consulta = urlencode($consulta);
$resultados = llamaApiFacebook('https://api.facebook.com/method/fql.query?query=' . $consulta . '&format=JSON', true);
$eventos = array('nombre_calendario'=> '', 'eventos'=> array());
$cumplesMes = array();
foreach ($resultados as $resultado) {
    if (isset($resultado['birthday_date'])) {
        $mes = substr($resultado['birthday_date'], 0, 2);
        $dia = substr($resultado['birthday_date'], 3, 2);
        $eventos['eventos'][] = array (
                            'nombre' => 'Cumpleaños de ' . $resultado['name'],
			    //'inicio' => date('Y') . $mes . $dia . 'T000000',
			    'inicio' => date('Y') . $mes . $dia,
                            'fin' => date('Y') . $mes . $dia . 'T235959'
                            );
        if($mes == date('m'))
            $cumplesMes[] = array(
                                'nombre' => $resultado['name'],
                                'fecha' => $dia . '/' . $mes,
                                'foto' => 'http://graph.facebook.com/' . number_format($resultado['uid'], 0, '.', '') . '/picture?type=normal',
                                'perfil' => 'http://www.facebook.com/profile.php?id=' . number_format($resultado['uid'], 0, '.', '')
            );
    }
}
$recomendaciones = generaLugares(count($cumplesMes), $ubicacionUsuario);
$_SESSION['eventos'] = $eventos;
$_SESSION['cumplesMes'] = $cumplesMes;
$_SESSION['lugares'] = $recomendaciones;
header('location: ' . URL_BASE);

function generaLugares($numPersonas, $ubicacionUsuario) {
    if(isset($ubicacionUsuario)) {
        $lat = $ubicacionUsuario['latitud'];
        $lon = $ubicacionUsuario['longitud'];
    }
    else {
        $lat = LAT_DEFECTO;
        $lon = LON_DEFECTO;
    }
    $tiposLugar = array('food', 'drinks', 'coffee');
    $resultados = array ();
    foreach ($tiposLugar as $tipoLugar) {
        $lugares = llamaApiFoursquare('https://api.foursquare.com/v2/venues/explore?limit=' . ($numPersonas * NUM_RECOMENDACIONES_TIPOLUGAR) . '&radius=' . RADIO_BUSQUEDA . '&section=' . $tipoLugar . '&ll=' . $lat . ',' . $lon, true);
        $lugares = $lugares['response']['groups'][0]['items'];
        foreach ($lugares as $lugar) {
            $lugar = $lugar['venue'];
            $resultados[] = array('id' => $lugar['id'], 'nombre' => $lugar['name'], 'perfil' => 'https://foursquare.com/venue/' . $lugar['id']);
        }
    }
    return $resultados;
}

?>
