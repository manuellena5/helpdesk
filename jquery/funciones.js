/*window.alert = function(message){
    $(document.createElement('div'))
        .attr({title: 'Mensaje', 'class': 'alertaa'})
        .html(message)
        .dialog({
            buttons: {OK: function(){$(this).dialog('close');}},
            close: function(){$(this).remove();},
            draggable: true,
            modal: true,
            resizable: false,
            width: 'auto'
        });
};*/
  
  $.ajaxSetup({

      error: function( jqXHR, textStatus, errorThrown ) {

          /*if (jqXHR.status === 0) {

            alert('Hay problemas de conexion a la red.');

          } else*/
           if (jqXHR.status == 404) {

            alert('La pagina no fue encontrada. Error[404]');

          } else if (jqXHR.status == 500) {

            alert('Error de servidor. Error[500].');

          }  else if (textStatus === 'timeout') {

            alert('Error por tiempo excedido.');

          } else if (textStatus === 'abort') {

            alert('Ajax request aborted.');

          } else {

            alert('Uncaught Error: ' + jqXHR.responseText);

          }

        }
});

function mostrarError(textoError){
		window.alert("Error:"+textoError);
}

function refrescarCombo(select){
	//$.uniform.update(select);
}

function formatearFecha(fecha){
	var dia=fecha.getDay();
	var strDia=""+dia;
	if (dia<10){
		strDia="0"+dia;
	}
	var mes=fecha.getMonth();
	var strMes=""+mes;
	if (mes<10){
		strMes="0"+mes;
	}
	var strAnio=fecha.getFullYear();
	return strDia+"/"+strMes+"/"+strAnio;
}

/*
function fechaDatePicker(){
  $( ".fechaClase" ).datepicker({
	  changeMonth: true,
	  changeYear: true,
	  yearRange: "1930:2050",
	  dateFormat: "dd/mm/yy",
	  altFormat: "yy-mm-dd",
	  gotoCurrent: true,
	  autoSize: true,
	  monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ]
  });
  $( ".fechaClase" ).datepicker( "setDate", new Date() );
  $( ".fechaClase" ).datepicker( "option", "altField", "#fechaMySql" );
}

jQuery.extend(jQuery.validator.messages, {
    required: "*",
    remote: "*",
    email: "Ingrese un correo electrónico válido.",
    date: "Ingrese una fecha válida.",
    number: "Ingrese solo números.",
    digits: "Ingrese solo dígitos.",
    equalTo: "Los campos no coinciden.",
    maxlength: jQuery.format("El tamaño máximo es {0}."),
    minlength: jQuery.format("El tamaño mínimo es {0}.")
});
jQuery.validator.setDefaults({
	errorClass: "errores",
    errorPlacement: function(error, element) {
        error.insertAfter('#invalid-' + element.attr('id'));
    }
});*/

$("body").on('focusout',".numeros",function(e) {
      e.stopImmediatePropagation();
      if (!$.isNumeric($(this).val()) && $(this).val().length>0){
	$(this).val("");
	window.alert("Ingrese solo Números");
      }
});

function fieldsetSombra(){
	$("div fieldset").css("box-shadow","2px 2px 4px #888");
	$("div fieldset").css("-moz-box-shadow","2px 2px 4px #888");
	$("div fieldset").css("-webkit-box-shadow","2px 2px 4px #888");
}


function vacios(){
  var varia="0";
  $('.vacios').each(function() {
    if ($("#"+this.id).val().length==0){
      varia=this.id;
		console.log("id="+this.id);
    }
  });
  return varia;
}


function trim (myString)
{
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}
