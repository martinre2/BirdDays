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
            contenido += '<p>' + amigo.nombre + '</p><p>' + amigo.fecha + '</p></td><td>';
            contLugares = 1;
            $.each(lugares, function(i, lugar){
                if(i >= contInicioLugares) {
                    if(contLugares <= 3) {
                        contenido += '<p><a href="https://foursquare.com/venue/' + lugar.id +'" target="_blank">' + lugar.nombre + '</a></p>';
                        contLugares++;
                    }
                }
            });
            contInicioLugares = contInicioLugares+3;
            contenido += '</td></tr>';
        });
        contenido += '</table>';
        $(contenido).appendTo("#contenido");
        contenido = '<p align="center">¿Cómo quieres que se llame tu calendario de Google? </p>\
            <p>\
            <center>\
            <form>\
            <input type="text" class="elementofrm" value="Calendario BirdDays" name="nombreCal"/>\
            <p></p>\
            <button class="elementofrm" value="submit" id="btnCal">Exportar a Google Calendars</button>\
            </form>\
            </center>\
            </p>';
        $(contenido).appendTo("#contenido");
    }
    else
        $('#contenido').html(contenidoInicio);
});