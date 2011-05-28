<?php

session_start();
include('configuraciones.php');

$usuario = llamaApiFacebook('https://graph.facebook.com/me');
$idUsuario = $usuario['id'];
if(isset($usuario['location']))
    $_SESSION['ubicacion'] = $usuario['location'];
$consulta = "SELECT uid,name,birthday_date FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $idUsuario) ORDER BY birthday_date";
$consulta = urlencode($consulta);
$resultados = llamaApiFacebook('https://api.facebook.com/method/fql.query?query=' . $consulta . '&format=JSON', true);
$eventos = array('nombre_calendario'=> $_SESSION['nombreCal'], 'eventos'=> array());
$cumplesMes = array();
foreach ($resultados as $resultado) {
    if (isset($resultado['birthday_date'])) {
        $dia = substr($resultado['birthday_date'], 0, 2);
        $mes = substr($resultado['birthday_date'], 3, 2);
        $eventos['eventos'][] = array (
                            'nombre' => 'Cumpleaños de ' . $resultado['name'],
                            'inicio' => date('Y') . $mes . $dia . 'T000000',
                            'fin' => date('Y') . $mes . $dia . 'T235959'
                            );
        if($mes == date('m'))
            $cumplesMes[] = array(
                                'nombre' => $resultado['name'],
                                'dia' => $dia,
                                'foto' => 'http://graph.facebook.com/' . number_format($resultado['uid'], 0, '.', '') . '/picture?type=normal'
                                );
    }
}
$_SESSION['eventos'] = $eventos;
$_SESSION['cumplesMes'] = $cumplesMes;
header('location: ' . URL_BASE);

function llamaApiFacebook($url, $parametrosAntes = false) {
    $curl = curl_init();
    if ($parametrosAntes == false)
        curl_setopt($curl, CURLOPT_URL, $url . '?access_token=' . $_SESSION['token_acceso_facebook']);
    else
        curl_setopt($curl, CURLOPT_URL, $url . '&access_token=' . $_SESSION['token_acceso_facebook']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $respuesta = curl_exec($curl);
    if ($respuesta == false)
        echo 'Tenemos problemas, por favor intenta de nuevo';
    $respuesta = json_decode($respuesta, true);
    return $respuesta;
}

?>
