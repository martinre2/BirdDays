<?php
session_start();

echo '//'.session_id().";\n";
if (isset($_SESSION['cumplesMes'])) {
    echo "aut = true;\n";
    echo 'numResultados = ' . (count($_SESSION['lugares']) / count($_SESSION['cumplesMes'])) . ";\n";
    if (isset($_SESSION['sessionToken']))
    	echo "OAuth = true;\n";
    else
    	echo "OAuth = false;\n";
}
else
    echo "aut = false;\n";
?>
contenidoInicio = '\
<p>¿Eres de los que siempre olvidan los cumpleaños y festejan en el mismo lugar?</p>\
<p>BirdDays te permite te permite crear un calendario de Google con todos los cumpleaños de tus contactos de Facebook automáticamente. Además crea eventos para cada cumpleaños por lo que podrás recibir recordatorios cuando alguien cumpla años. También te recomendará lugares donde puedes festejar.</p>\
<center>\
<form action="manejadorAuthFb.php" method="POST">\
<p></p>\
<button class="elementofrm" value="submit" id="btnComenzar">Comenzar</button>\
</form>\
</center>';

$(document).ready(function() {
    if(aut == true) {
        amigos = $.parseJSON($.ajax({
            url: "manejadorJson.php?cumplesMes",
            async: false
        }).responseText);
        lugares = $.parseJSON($.ajax({
            url: "manejadorJson.php?lugares",
            async: false
        }).responseText);
        contenido = '<table width="500"><tr><td><h4>Cumplen años este mes</h4></td><td><h4>Pueden celebrarlo en</h4></td></tr>';
        contInicioLugares = 0;
        $.each(amigos, function(i, amigo){
            contenido += '<tr><td><img src="' + amigo.foto + '" alt="' + amigo.nombre + '"/>';
            contenido += '<p><a href="' + amigo.perfil + '" target="_blank">' + amigo.nombre + '</a></p><p>' + amigo.fecha + '</p></td><td>';
            contLugares = 1;
            $.each(lugares, function(i, lugar){
                if(i >= contInicioLugares) {
                    if(contLugares <= numResultados) {
                        contenido += '<p><a href="' + lugar.perfil +'" target="_blank">' + lugar.nombre + '</a></p>';
                        contLugares++;
                    }
                }
            });
            contInicioLugares += numResultados;
            contenido += '</td></tr>';
        });
        contenido += '</table>';
        $(contenido).appendTo("#contenido");
        contenido ='<center>\
            <form action="./oauth2callback/" method="GET">\
            <p></p>\
            <button class="elementofrm" value="submit" id="btnCal">Exportar a Google Calendars</button>\
            </form>\
            </center>\
            </p>';
	contenidoAuth = '<p align="center">¿Cómo quieres que se llame tu calendario de Google? </p>\
	            <p>\
		    <center>\
		    <form action="makeCal.php" method="GET">\
		    <input type="text" class="elementofrm" value="Calendario BirdDays" name="nombreCal"/>\
		    <p></p>\
		    <button class="elementofrm" value="submit" id="btnCal">Exportar a Google Calendars</button>\
		    </form>\
		    </center>\
		    </p>';
	
	val = (OAuth)?contenidoAuth:contenido;
	$(val).appendTo("#contenido");
    }
    else
        $('#contenido').html(contenidoInicio);
});
