<?php
session_start();
header('content-type: application/x-javascript; charset=UTF-8');
if (isset($_SESSION['cumplesMes'])) {
    echo 'aut = true;';
    if (isset($_SESSION['ubicacion']))
        echo 'ubicacion = \'' . $_SESSION['ubicacion'] . '\'';
}
else
    echo 'aut = false;';
?>
contenidoInicio = '\
<p>¿Eres de los que siempre olvidan los cumpleaños y festejan en el mismo lugar?</p>\
<p>BirdDays te permite te permite crear un calendario de Google con todos los cumpleaños de tus contactos de Facebook automáticamente. Además crea eventos para cada cumpleaños por lo que podrás recibir recordatorios cuando alguien cumpla años. También te recomendará lugares donde puedes festejar.</p>\
<center>\
<form action="manejadorAuthFb.php" method="POST">\
<p></p>\
<button class="elementofrm" value="submit" id="btnComenzar">Comenzar</button>\
</form>\
</center>\
<p></p>\
<div class="contenedorlogos centrado">\
    <div class="imgfondo fblogo"></div>\
    <div class="imgfondo fslogo"></div>\
</div>';

$(document).ready(function() {
    if(aut == true) {
        $.getJSON('manejadorJson.php?cumplesMes', function(data) {
            contenido = '<h3>Este mes cumplen: </h3>';
            $(contenido).appendTo("#contenido");
            $.each(data, function(i, amigo){
                contenido = '<p>' + amigo.nombre + ' el ' + amigo.dia + '<br/>';
                contenido += '<img src="' + amigo.foto + '" alt="' + amigo.nombre + '"/>';
                $(contenido).appendTo("#contenido");
            });
            contenido = '<p align="center">¿Cómo quieres que se llame tu calendario? </p>\
            <p>\
            <center>\
            <form>\
            <input type="text" class="elementofrm" value="Calendario BirdDays" name="nombreCal"/>\
            <p></p>\
            <button class="elementofrm" value="submit" id="btnCal">Exportar a un calendario</button>\
            </form>\
            </center>\
            </p>';
            $(contenido).appendTo("#contenido");
            if(ubicacion) {
                contenido = '<h3>Parece que tu estás en: ' + ubicacion + '</h3>';
                $(contenido).appendTo("#contenido");
            }
        });
    }
    else
        $('#contenido').html(contenidoInicio);
});