function ejecutarAjax(objeto, opcion, parametros)
{
    $.ajax({
        type: 'POST',
        url: '../../../control/ControlFrontal.php',
        dataType: 'JSON',
        data: {
			objeto: objeto,
            opcion: opcion,
            parametros: parametros
        },
        success: function(rta){
			if (rta.destino == 'alert')
				alert(rta.mensaje);
			else if  (rta.destino == 'confirm')
				confirm(rta.mensaje);
			else if  (rta.destino == 'div')
				$('#div_'+rta.id).html(rta.mensaje);
			else if (rta.destino == 'redirect')
				window.location.href = rta.mensaje;
        }
    });
}

$(document).ready(function (){
	$("#salir").click(function(){
		window.open('login.php?des=true', '_parent');
	});
});