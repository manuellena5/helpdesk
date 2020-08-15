$(document).ready(function() {
       
//load();

    /*$("#leermail").on("click", function () {
    location.reload(true);
});*/
});
   //setInterval(load, 240000);
   //setInterval(fin, 1.8e+6);

 //function load(){
                
                /*$.ajax({
                  url: '_reademail.php',
                  type: 'POST',
                })
                .done(function() {
                  console.log("success");
                })
                .fail(function() {
                  console.log("error");
                })
                .always(function() {
                  console.log("complete");
                });*/
  
  /* var looad=  $.ajax({
            type: "POST",
            url: "_reademail.php",
        });

}*/
function fin(){
   var lood=  $.ajax({
            type: "POST",
            url: "usuario/logout.php",
        });
   

}

function ver(respuesta){
  //console.log(respuesta);
  if (respuesta.success) {
    var cant_ticket = respuesta.cant_ticket - 1;
    var mail_original = respuesta.data.ticket[cant_ticket];
    var output =  "<div class='container'><div class='row'><div class='col-sm'><h5 class='tituloticket'>" + mail_original["nombre_ticket"] + mail_original['asunto'] + "</h5></div></div></div>";
    var adjunto = "";
    var nombreadj = "";
    var nomadj = "";
    var rutaadj = "";
    var salidaadjuntos = "";
    output += "<div class='container'>";
    output +=   "<div class='row'>";
    output +=     "<div class='col-sm'>";
    output +=       "<b>Fecha:</b>" + mail_original['fecha'];
    output +=     "</div>";
    if( (mail_original['cod_entidad'] == 0) || (mail_original['cod_entidad'] == null) || (mail_original['cod_entidad'] == "")){
      output +=   "<div class='col-sm'>";
      output +=     "<b>De:</b>"+ mail_original['emisor']; 
      output +=   "</div>";
    } else {
      output +=   "<div class='col-sm'>";
      output +=     "<b>De:</b>"+ mail_original['razon']; 
      output +=   "</div>";
    }if ((mail_original['adjunto']=='N') && (mail_original['adjticket']=='N')) {
      adjunto = 'No';
      output +=   "<div class='col-sm'>";
      output +=     "<b>Adjunto:</b>"+ adjunto; 
      output +=   "</div>" + "<br>";
    }else if( (mail_original['adjunto']=='S') || (mail_original['adjticket']=='S') ){
      adjunto = 'Si';
      output +=   "<div id='divadj' class='col-sm'>";
      output +=     "<b>Adjunto:</b>"+ adjunto; 
      output +=   "</div>" + "<br>";
   }if (mail_original["ing_env"] != null && mail_original["ing_env"] != "" ) {
      var tipoingreso = "";
      if (mail_original["ing_env"] == "I") {
        tipoingreso = "Ingreso";
      }else if(mail_original["ing_env"] == "E"){
        tipoingreso = "Envio";
      }
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>Tipo Documentacion:</b>"+ tipoingreso; 
      output +=     "</div>";
      output +=    "</div>";
      output += "</div>";
   }if(mail_original['cc']){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>CC:</b>"+ mail_original["cc"]; 
      output +=     "</div>";
      output +=    "</div>";
      output += "</div>";
  }if(mail_original['cco']){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>CCO:</b>"+ mail_original["cco"]; 
      output +=     "</div>";
      output +=   "</div>";
      output += "</div>";
  }if(mail_original['destinatario'] != "cie@cie.gov.ar" && mail_original['destinatario'] != "" && mail_original["destinatario"] != null){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>Para:</b>"+ mail_original["destinatario"]; 
      output +=     "</div>";
      output +=   "</div>";
      output += "</div>";
  }
    output +=  "</div>";
    output += "</div>";
    output += "<hr>";
    output += "<h5>ACCIONES</h5>";

    $.each(respuesta.data.ticket, function( key, value ) {
    if(value['leido'] == "3" || value['leido'] == "2"){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-5'>";
      output +=       "<b>Fecha de asig.: </b>" + value['fechaasig'];
      output +=     "</div>";
      output +=     "<div class='col-5'>";
      output +=       "<b>Observado por: </b>" + value['quienderi'];
      output +=     "</div>";
      if(value['observacion'] != null && value['observacion'] != ""){
        output +=   "<div class='col-10'>";
        output +=     "<b>Observacion: </b>" + value['observacion'];
        output +=   "</div>";
      } 
      output +=   "</div>";
      output += "</div>";
      output += "<hr>";
    } else{
      output += "<div class='container'>";
        output +=   "<div class='row'>";
      if (value['estadoticket'] == "D") {
        
        output +=     "<div class='col-5'>";
        output +=       "<b>Fecha de asig.: </b>" + value['fechaasig'];
        output +=     "</div>";
        output +=     "<div class='col-3'>";
        output +=       "<b>Derivado por: </b>" + value['quienderi'];
        output +=     "</div>";
        if (value['usuarioasig'] != null && value['usuarioasig'] != 0) {
          output +=     "<div class='col-3'>";
          output +=       "<b>Asignado a: </b>" + value['usuarioasig'];
          output +=     "</div>";
        }if (value['rolasig'] != null && value['rolasig'] != 0) {
          output +=     "<div class='col-3'>";
          output +=       "<b>Asignado a: </b>" + value['rolasig'];
          output +=     "</div>";
        }if(value['observacion'] != null && value['observacion'] != ""){  
          output +=     "<div class='col-10'>";
          output +=       "<b>Observacion: </b>" + value['observacion'];
          output +=     "</div>";    
        } else {
          output +=     "<div class='col-10'>";
          output +=       "<b>Observacion: </b>";
          output +=     "</div>";
        }if(value['nro_mesaent'] != null && value['nro_mesaent'] != "" && value['nro_mesaent'] > 0){
          output +=     "<div class='col-10'>";
          output +=       "<b>Nro Mesa Entrada: </b>" + value['nro_mesaent'];
          output +=     "</div>";
        }
          output +=   "</div>";
          output += "</div><br>";
          output += "<br>";
          output += "<hr>";
          //Fin de Estados D
      }else if (value['estadoticket'] == 'F') {
       /* output += "<div class='container'>";
        output +=   "<div class='row'>";*/
        output +=     "<div class='col-5'>";
        output +=       "<b>Fecha de asignacion: </b>" + value['fechaasig'];
        output +=     "</div>";
        output +=     "<div class='col-5'>";
        output +=       "<b>Finalizado por: </b>" + value['quienresp'] + "<br>";
        output +=     "</div>";
        if(value['observacion'] != null && value['observacion'] != ""){
          output +=     "<div class='col-10'>";
          output +=       "<b>Observacion: </b>" + value['observacion'];
          output +=     "</div>";  
        }if(value['nro_mesaent'] != null && value['nro_mesaent'] != "" && value['nro_mesaent'] > 0){
          output +=     "<div class='col-10'>";
          output +=       "<b>Nro Mesa Entrada: </b>" + value['nro_mesaent'];
          output +=     "</div>";
        }
        output +=   "</div>";
        output += "</div>";
        output += "<br>";
        output += "<hr>";
        //Fin de Estado F
      }else if(value['estadoticket'] == 'C'){
        /*output += "<div class='container'>";
        output +=   "<div class='row'>";*/
        output +=     "<div class='col-5'>";
        output +=       "<b>Fecha: </b>" + value['fecha'];
        output +=     "</div>";
        output +=     "<div class='col-6'>";
        output +=       "<b>Contestado por: </b>" + value['emisor'];
        output +=     "</div>";
        output +=     "<div class='col-10'>";
        output +=       "<b>Mensaje: </b><br>" + value['cuerpo'];
        output +=     "</div>";
        if(value['nro_mesaent'] != null && value['nro_mesaent'] != "" && value['nro_mesaent'] > 0){
          output +=     "<div class='col-10'>";
          output +=       "<b>Nro Mesa Entrada: </b>" + value['nro_mesaent'];
          output +=     "</div>";
        }
        output +=   "</div>";
        output += "</div>";
        output += "<br>";
        output += "<hr>";
        //Fin de Estado C
      }else if(value['estadoticket'] == 'R'){
        /*output += "<div class='container'>";
        output +=   "<div class='row'>";*/
        output +=     "<div class='col-5'>";
        output +=       "<b>Fecha: </b>" + value['fechaasig'];
        output +=     "</div>";
        output +=     "<div class='col-6'>";
        output +=       "<b>Respondido por: </b>" + value['quienresp'];
        output +=     "</div>" + "<br>";
        output +=     "<div class='col-10'>";
        output +=       "<b>Respuesta: </b><br>" + value['respuesta'];
        output +=     "</div>";
        if(value['nro_mesaent'] != null && value['nro_mesaent'] != "" && value['nro_mesaent'] > 0){
          output +=     "<div class='col-10'>";
          output +=       "<b>Nro Mesa Entrada: </b>" + value['nro_mesaent'];
          output +=     "</div>";                   
        }
        output +=   "</div>";
        output += "</div>";
        output += "<br>";
        output += "<hr>";   
        //Fin del Edtado R    
      }else if(value['estadoticket'] == null){
        output +=   "</div>";
        output += "</div>";
        output += "<br>";
        output += "<hr>";
      } 
        output +=   "</div>";
        output += "</div>";
        output += "<br>";
       
}  
               
              

         });

         //document.getElementById("cuerpo").innerHTML = output;
        
        
        output+="<div class='col table-responsive'>";
        output+= mail_original['cuerpo'];
        output+="</div>";
      //if((mail_original['adjunto']=='S' || mail_original['adjticket']=='S') && mail_original["cant"] > 0){
         //respuesta.data.ticket[cant_ticket]
        if (respuesta.data["cantarchivos"] > 0) {
          
            salidaadjuntos += "<div class='container'>";
            salidaadjuntos +=   "<div class='row'>";
          //for (var i = 1; i <= mail_original["cant"]; i++) {
         // for (var i = 0; i <= respuesta.cantarchivos; i++) {
            //if (respuesta.data.ticket[i]["cant"]>0) {
              
              
              $.each(respuesta.data["archivo"], function(index, val) {
                nombreadj  = respuesta.data["archivo"][index]["nombre"];
                rutaadj    = respuesta.data["archivo"][index]["ruta"];
                salidaadjuntos +=     "<div class='col-sm'>";
                salidaadjuntos +=       "<br><a href='"+rutaadj+"' target='_blank'><button type='button' class='btn btn-primary'>";
                salidaadjuntos +=       nombreadj+" <span class='badge badge-light'><i class='fa fa-download' aria-hidden='true'></i></span>";
                salidaadjuntos +=       "</button></a>";
                salidaadjuntos +=     "</div>";
              });
              
            
            //}
          //}
            salidaadjuntos +=   "</div>";
            salidaadjuntos += "</div>";
            salidaadjuntos += "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>";
          document.getElementById("piemodal").innerHTML = salidaadjuntos;



        }else{
          document.getElementById("piemodal").innerHTML = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>";
        }
        document.getElementById("cuerpo").innerHTML = output;
       


      }else{
        console.log(respuesta.data.mensaje);
        output = "<h4>Error</h4>";
          //document.getElementById("cuerpo").innerHTML ="<h4>Error</h4>";

      }
     
}





function cronmenu(nrousuario,rol,accion){
  

  var nrousuario = nrousuario;
  var rol = rol;
  var action = accion;
  var data = new FormData();
  data.append('action',action);
  data.append('nrousuario',nrousuario);
  data.append('rol',rol);
  
  $.ajax({
              url: "funciones.php",
                type: "POST",
                 // Podrías separar las funciones de PHP en un fichero a parte
                data:data,
                dataType: 'json',
                contentType: false,  
                 cache: false,  
                 processData:false,
            })
          .done(function(respuesta) {
              if (respuesta.success) {
              actualizarmenu(respuesta);
              actualizarencabezado(respuesta.cantticketsnoleidos,respuesta.cantticketsobservados);
                          } 
            })
            .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
          console.log("Algo ha fallado: " +  textStatus);
          alert(respuesta.error.mensaje);
      })




}

function actualizarmenu(arreglo){


if ((arreglo.ingresopormail == null) || (arreglo.ingresopormail == "") ) {
  arreglo.ingresopormail = 0;
}
document.getElementById('idingpormail').innerHTML = arreglo.ingresopormail;
document.getElementById("idpapelera").innerHTML = arreglo.papelera;
document.getElementById("idsinaccion").innerHTML = arreglo.sinaccion;
document.getElementById("idenespera").innerHTML = arreglo.enespera;
document.getElementById("idfinalizados").innerHTML = arreglo.finalizados;
document.getElementById("idspam").innerHTML = arreglo.spam;
document.getElementById("idseguimiento").innerHTML = arreglo.seguimiento;


}



function actualizarencabezado(respuesta,observados){
  
  var encabezado = "";
  var obser = "";
  var cantnoleidos = $("#idcantidad").val();
  var cantobservados = $("#idcantidadobservados").val();
 
  if (respuesta > "0") {
    if ((cantnoleidos > "0") && (cantnoleidos < respuesta)) {
      document.getElementById('xyz').play();  
    }
    if (respuesta == "1") {
      encabezado = "(" + respuesta + ") " + "Ticket sin leer"; 
    }else{
      encabezado = "(" + respuesta + ") " + "Tickets sin leer";          
    }
  }else{
    encabezado = "No se registran Ticket sin leer";
  }
  if (observados > "0") {
    if ((cantobservados > "0") && (cantobservados < observados)) {
      document.getElementById('xyz').play();
    }                           
    if(observados == "1"){
      obser = "(" + observados + ") " + "Ticket con Observacion";
    } else{
      obser = "(" + observados + ") " + "Tickets con Observacion";
    }
  }else{
    obser = "No se registran Ticket con Observacion";
  }
  if(respuesta > "0" || observados > "0"){
    $("#yaleidos").removeClass("vVisible").addClass("vOculto");
    $("#noleidos").removeClass("vOculto").addClass("vVisible");
    document.getElementById('ticketnoleidos').innerHTML = " " + respuesta + " - " + observados + " ";
    titulopagina =  "(" + respuesta + "-"+observados+")";
    $("#noleidos").css("display","inline");
    $("#idcantidad").val(respuesta);
    $("#idcantidadobservados").val(observados);
  } else {
    $("#yaleidos").removeClass("vOculto").addClass("vVisible");
    $("#noleidos").removeClass("vVisible").addClass("vOculto");
    $("#yaleidos").css("display","inline");
    $("#idcantidad").val(respuesta);
    $("#idcantidadobservados").val(observados);
  }
  
  
  document.getElementById("cantnoleidos").innerHTML = encabezado;
  document.getElementById("cantobservados").innerHTML = obser;
  //es el texto que aparece en el encabezado abajo de la hora ult ingreso






}


function veragenda(variable){
    tipo = variable;
    table = $('#tablaAgenda').DataTable({
      destroy: true,
      'ajax': '_listadoagenda.php',
      "language": {
        url:'DataTables/es-ar.json'},
      'columnDefs': [
        {
          'targets': 0,
          'checkboxes': {
            'selectRow': true
          } 
         }
      ],
      'select': {
        'style': 'multi'
      },
      'order': [[1, 'asc']]
   });

     $('#seleccion').on('click', function(e){
    var mensaje         = "";
    var separador       = "";
    var rows_selected   = table.column(0).checkboxes.selected();
    if(rows_selected.length > 1){
      separador         = ";"; 
    }

    //Iterar sobre todas las casillas de verificación seleccionadas
    $.each(rows_selected, function(index, rowId){
      //Crea un elemento oculto
      mensaje += rowId + separador;
    });

    if(tipo=='cc'){
      var CC            = document.getElementById("CC").value;
      if(CC != ""){
        CC = CC + ";";
      }
      document.getElementById("CC").value= CC+mensaje;
    } else if(tipo=='cco'){
      var CCO           = document.getElementById("CCO").value;
      if (CCO != "") {
        CCO = CCO + ";";
      }
      document.getElementById("CCO").value=CCO+mensaje;
    }
   });

  $('#tablaAgenda').on('error.dt', function(e, settings, techNote, message) {
    alert("Ocurrió un error al cargar la tabla");
    console.log('Ocurrió un error al cargar la tabla: ', message);
  });

    
  };



  function cargar_select(valorselect){


       $.ajax({
              url: 'cargar_select_etiquetas.php',
              type: 'POST',
              dataType: 'json',
              data: {valor: valorselect},
            })
            .done(function(respuesta) {
              if (respuesta.success) {


                if (valorselect == 0) {
                    var s=document.formregistro.etiquetas; 
                    limpiarselect("etiquetas");
                }else{

                    var s=document.formregistro.secuetiquetas; 
                    limpiarselect("secuetiquetas");
                }
                 /* var option=document.createElement("option"); 
                  option.value=0; 
                  option.text="Seleccione una opcion";

                  s.appendChild(option);
                   */           
                    for (var i = 0; i < respuesta.cantidad; i++) {
                                  
                        var option=document.createElement("option"); 
                        option.value=respuesta[i][0]; 
                        option.text=respuesta[i][1]; 

                        s.appendChild(option);
                    }

              }else{
                alert("ocurrió un error en el formulario");
              }
            })
            .fail(function() {
              console.log("error");
            })
           

  }




  
  function ver_elementoHTML(valor,elemento){

    var divelemento = document.getElementById(elemento);

        if (valor=="si") {
            if(divelemento.className == 'vOculto')
            {$("#"+elemento+"").removeClass("vOculto").addClass("vVisible");
               }
             
        }else{
             if(divelemento.className == 'vVisible')
                {$("#"+elemento+"").removeClass("vVisible").addClass("vOculto");
               }
            
        
         }





  }


  function limpiarselect(idselect){

      
        var sel = document.getElementById(idselect);
        for (i = sel.length - 1; i >= 1; i--) {
            sel.remove(i);
        }
        sel.length = 1;


  }

  function limpiarselect2(idselect){
        var sel = document.getElementById(idselect);
        for (i = sel.length -1; i >= 1; i--) {
            sel.remove(i);
        }
        sel.length = 1;


  }


function imprimirElemento(div){
  var elemento = document.getElementById(div);
  var ventana = window.open('', 'PRINT', 'height(400),width(600)');
  ventana.document.write('<html><head><title>' + document.title + '</title>');
  ventana.document.write('</head><body >');
  ventana.document.write(elemento.innerHTML);
  ventana.document.write('</body></html>');
  ventana.document.close();
  ventana.focus();
  ventana.print();
  ventana.close();
  return true;
}

 
function verhistorial(nro,idticket,tipo_ticket,nombre_tabla){
  $.ajax({
    url: 'seguimientotickets/_verhistorial.php',
    type: 'GET',
    dataType: 'json',
    data: {nro,idticket,tipo_ticket},
  })
  .done(function(respuesta) {
   ver(respuesta);     
    cronmenu(nrousuario,rol,"2");
    nombre_tabla.ajax.reload(null, false);
  })
  .fail(function(jqXHR, textStatus, errorThrown) {
    console.log("Algo ha fallado: " +  textStatus);
    document.getElementById("cuerpo").innerHTML ="<h4>Error</h4>";
  })
}






function enviar_form_alta(direccion){


   $.ajax({
        url: direccion,
        type: 'POST',
        dataType: 'json',
        data: $("#formulario").serialize(),
      })
      .done(function(respuesta) {
        
        if (respuesta.success) { 
          $("#jGrowl-container1").removeClass("vOculto").addClass("vVisible"); 
          cargarPagina(respuesta.url);
        }else{
          $("#jGrowl-container1").removeClass("vVisible").addClass("vOculto");
          alert(respuesta.error.mensaje);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("Algo ha fallado: " +  textStatus);
        alert("Algo ha fallado");
      })

}


function enviar_form(direccion){


   $.ajax({
        url: direccion,
        type: "POST",               // Type of request to be send, called as method
        dataType: 'json',
        data: $("#formulario").serialize(),
      })
      .done(function(respuesta) {
        
        if (respuesta.success) { 
          $("#jGrowl-container1").removeClass("vOculto").addClass("vVisible"); 
          cargarPagina(respuesta.url);
        }else{
          $("#jGrowl-container1").removeClass("vVisible").addClass("vOculto");
          alert(respuesta.error.mensaje);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("Algo ha fallado: " +  textStatus);
        alert("Algo ha fallado");
      })

}





    
 //Solo permite introducir numeros.
function soloNumeros(e){
        var key = e.charCode;
        return key >= 48 && key <= 57;
    }


function validar_cuit (cuit)
   {
     var vec=new Array(10);
    esCuit=false;
    cuit_rearmado="";
    errors = ''
    for (i=0; i < cuit.length; i++) {   
        caracter=cuit.charAt( i);
        if ( caracter.charCodeAt(0) >= 48 && caracter.charCodeAt(0) <= 57 )     {
            cuit_rearmado +=caracter;
        }
    }
    cuit=cuit_rearmado;
    if ( cuit.length != 11) {  // si to estan todos los digitos
        esCuit=false;
        //errors = 'Cuit <11 ';
        //alert( "CUIT Menor a 11 Caracteres" );
    } else {
        x=i=dv=0;
        // Multiplico los dígitos.
        vec[0] = cuit.charAt(  0) * 5;
        vec[1] = cuit.charAt(  1) * 4;
        vec[2] = cuit.charAt(  2) * 3;
        vec[3] = cuit.charAt(  3) * 2;
        vec[4] = cuit.charAt(  4) * 7;
        vec[5] = cuit.charAt(  5) * 6;
        vec[6] = cuit.charAt(  6) * 5;
        vec[7] = cuit.charAt(  7) * 4;
        vec[8] = cuit.charAt(  8) * 3;
        vec[9] = cuit.charAt(  9) * 2;
                    
        // Suma cada uno de los resultado.
        for( i = 0;i<=9; i++) {
            x += vec[i];
        }
        dv = (11 - (x % 11)) % 11;
        if ( dv == cuit.charAt( 10) ) {
            esCuit=true;
        }
    }
    if ( !esCuit ) {
        //alert( "CUIT Invalido" );
        //document.frmClientes.cuit.focus();
        //errors = 'Cuit Invalido ';
    return "";
    }
  cuit_rearmado = cuit_rearmado.substring(0, 2) + "-" + cuit_rearmado.substring(2, 10) + "-" + cuit_rearmado.substring(10, 11);
  return cuit_rearmado;
}


function global_tables(funtable){
  var counter = 0;
  var ta_fun = funtable;
  //console.log(ta_fun);
  var looper = setInterval( function () {ta_fun.ajax.reload(null, false);
    counter++;
    //console.log("Counter is: " + counter);

    if (counter >= 10)
    {
        clearInterval(looper);
    }
  }, 90000 );
  /*console.log("looper is: " + looper);
  console.log("Counter is: " + counter);
  console.log("funtable is: " +funtable);
  console.log("ta_fun is: " +ta_fun);
  */
}




function eliminar_puntocoma(email){

  var caracter = email.substring(email.length,email.length-1);
      if ( caracter ==";") {
        email = email.substring(0, email.length - 1);
      }
  return email;



}




function mostrar_nro_mesaentrada(valor){

  var num = document.getElementById('num');

  
  
  if (valor) {

    $("#num").removeClass("vOculto").addClass("vVisible");
    document.getElementById('NUMMESAENT').value=1;
    

    var NUMMESAENT = "NUMMESAENT";
    var action = "numero_mesa_entrada";
    var daa = new FormData();
    daa.append('NUMMESAENT',NUMMESAENT);
    daa.append('action',action);
    $.ajax({
      url: "funciones.php",
      type: "POST",
      data:daa,
      dataType: "json",
      contentType: false,  
      cache: false,  
      processData:false,
    })
    .done(function(res) {
      document.getElementById('num').innerHTML = res.NUMMESAENT;
    })
    .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
      console.log("Algo ha fallado2: " +  textStatus);
      alert(respuesta.error.mensaje);
    });
    }else{

      $("#num").removeClass("vVisible").addClass("vOculto");
      document.getElementById('NUMMESAENT').value=0;

    }

}


/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/

/*
function validar_form_alta(form,url){

  direccion = 'parametrizacion/' + url;
  
  switch(form) {
      
      case 'entidad':
        if (validacion_alta_entidades()) {
          enviar_form_alta(direccion);
        }else{
          break;
        }
        break;
      case 'usuario':
        if (validacion_alta_usuario()) {
          enviar_form_alta(direccion);
        }else{
          break;
        }
        break;
      case 'franjahora':
        if (validacion_alta_hora()) {
          enviar_form_alta(direccion);
        }else{
          break;
        }
        break;
      case 'etiquetas':
        if (validacion_alta_rol_etiquetas()) {
          enviar_form_alta(direccion);
        }else{
          break;
        }
        break;
      case 'rol':
        if (validacion_alta_rol_etiquetas()) {
          enviar_form_alta(direccion);
        }else{
          break;
        }
        break;
      default:
        break;
}

}
*/

function validar_form_parametrizacion(form,modo,url){

  direccion = 'parametrizacion/' + url;
  
  switch(form) {
      
      case 'entidad':
        if (validacionform_entidades()) {
            if (modo = "modificacion") {               
                
                enviar_form(direccion);       
        }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
        }
        }else{break;}
        
        break;
      case 'usuario':
        if (validacionform_usuario()) {
          if (modo = "modificacion") {               
                
                enviar_form(direccion);       
          }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{
          break;
        }
        break;
      case 'franjahora':
        if (validacionfrom_hora()) {
          if (modo = "modificacion") {               
                
                enviar_form(direccion);       
          }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{break;}
        break;
      case 'etiquetas':
        if (validacionform_rol_etiquetas()) {
          if (modo = "modificacion") {               
                
                enviar_form(direccion);       
          }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{break;}
        break;
      case 'expedientes':
        if (validacionform_rol_etiquetas()) {
          if (modo = "modificacion") {               
                
                enviar_form(direccion);       
          }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{break;}
        break;
      case 'rol':
        if (validacionform_rol_etiquetas()) {
          if (modo = "modificacion") {               
                
                enviar_form(direccion);       
          }else if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{break;}
        break;
        case 'mesa':
        if (validacionform_mesa()) {
             if(modo = "alta"){
                              
                 enviar_form(direccion);           
          }
        }else{break;}
        break;
      default:
        break;
}

}







function validacionform_usuario(){

    var usuario             = document.getElementById("usuario").value;
    var pass                = document.getElementById("pass").value;
    var pass2               = document.getElementById("pass2").value;
    var nombre              = document.getElementById("nombre").value;
    var roles               = document.getElementById("roles").value;
    var dia                 = document.getElementById("dia").value; 
    var hora                = document.getElementById("hora").value;
    var firma                = document.getElementById("firma").value;
    //selectedIndex


    if( usuario == null || usuario.length == 0 || /^\s+$/.test(usuario) ) {
      document.getElementById("usuario").focus();
      alert("Debe ingresar un usuario");
      return false;
    }else if( pass == null || pass.length == 0 ) {
      document.getElementById("pass").focus();
      alert("Debe ingresar una contraseña");
      return false;
    }else if( pass2 == null || pass2.length == 0) {
      document.getElementById("pass2").focus();
      alert("Debe repetir la contraseña.");
      return false;
    }else if( pass != pass2) {
      document.getElementById("pass").focus();
      alert("Las contraseñas ingresadas no coinciden");
      return false;
    }else if( nombre == null || nombre.length == 0 || /^\s+$/.test(nombre) ) {
      document.getElementById("nombre").focus();
      alert("Debe ingresar un nombre");
      return false;
    }else if(roles == null || roles == 0 ) {
      document.getElementById("roles").focus();
      alert("Debe seleccionar un rol.");
      return false;
    }else if(dia == null || dia == 0 ) {
      document.getElementById("dia").focus();
      alert("Debe seleccionar un dia.");
      return false;
    }else if(hora == null || hora == 0 ) {
      document.getElementById("hora").focus();
      alert("Debe seleccionar una hora.");
      return false;
    }else if(firma == null || firma == 0 ) {
      document.getElementById("firma").focus();
      alert("Debe poner una firma.");
      return false;
    }
      return true;





}


function validacionform_entidades(){

    //var razon             = document.getElementById("razon").value;
    /*var domicilio         = document.getElementById("domicilio").value;
    var localidad         = document.getElementById("localidad").value;
    var cuit              = validar_cuit(document.getElementById("cuit").value);
    var telefono          = document.getElementById("telefono").value;
    var mail              = document.getElementById("mail").value; */
    
    //expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;




    /*if( razon == null || razon.length == 0 || /^\s+$/.test(razon) ) {
      document.getElementById("razon").focus();
      alert("Debe ingresar una Razon Social");
      return false;
    }/*else if( domicilio == null || domicilio.length == 0 || /^\s+$/.test(domicilio) ) {
      document.getElementById("domicilio").focus();
      alert("Debe ingresar un domicilio");
      return false;
    }else if( localidad == null || localidad.length == 0 || /^\s+$/.test(localidad) ) {
      document.getElementById("localidad").focus();
      alert("Debe ingresar una localidad");
      return false;
    }else if( cuit == null || cuit.length == 0 || /^\s+$/.test(cuit) ) {
      document.getElementById("cuit").focus();
      alert("Debe ingresar un cuit correcto");
      return false;
    }else if( telefono == null || telefono.length == 0 || /^\s+$/.test(telefono) ) {
      document.getElementById("telefono").focus();
      alert("Debe ingresar un telefono");
      return false;
   }else  if(!expr.test(mail)) {
      document.getElementById("mail").focus();
      alert("Debe ingresar un mail correcto");
      return false;
    }*/
      return true;





}

function validacionform_mesa(){
   
    var mesa          = document.getElementById("numero").value;

  if( numero == null || numero.length == 0 || /^\s+$/.test(numero) ) {
      document.getElementById("numero").focus();
      alert("Debe ingresar un numero.");
      return false;
    }
    return true;

}


function validacionform_rol_etiquetas(){


    
    var pardesc          = document.getElementById("pardesc").value;

  if( pardesc == null || pardesc.length == 0 || /^\s+$/.test(pardesc) ) {
      document.getElementById("pardesc").focus();
      alert("Debe ingresar un descripcion");
      return false;
    }
    return true;

}

function validacionfrom_hora(){

  var dhora             = document.getElementById("dhora").value;
  var dminutos          = document.getElementById("dminutos").value;
  var hhora             = document.getElementById("hhora").value;
  var hminutos          = document.getElementById("hminutos").value;
  //selectedIndex

  if(dhora == null || dhora == "") {
      document.getElementById("dhora").focus();
      alert("Debe seleccionar una hora.");
      return false;
    }else if(dminutos == null || dminutos == "" ) {
      document.getElementById("dminutos").focus();
      alert("Debe seleccionar los minutos.");
      return false;
    }else if(hhora == null || hhora == "" ) {
      document.getElementById("hhora").focus();
      alert("Debe seleccionar una hora.");
      return false;
    }else if(hminutos == null || hminutos == "" ) {
      document.getElementById("hminutos").focus();
      alert("Debe seleccionar los minutos.");
      return false;
    }
    else if((dhora == hhora && dminutos==hminutos) || (dhora == hhora && dminutos>=hminutos) || (dhora > hhora) ) {
      
      alert("HORA HASTA no puede ser menor o igual a HORA DESDE .");
      return false;
    }
   
      
    return true;

}







 expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  function validacionform_ingresosvarios(){

    var titulo            = document.getElementById("titulo").value;
    var entidades         = document.getElementById("entidades").value;
    var etiquetas         = document.getElementById("etiquetas").value;
    //selectedIndex
    
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if( titulo == null || titulo.length == 0 || /^\s+$/.test(titulo) ) {
      document.getElementById("titulo").focus();
      alert("Debe ingresar un titulo");
      return false;
    }else if(entidades == "" || entidades == 0 || entidades == null) {
      document.getElementById("entidades").focus();
      alert("Debe ingresar una entidad");
      return false;
    }else if(etiquetas == "" || etiquetas == 0 || etiquetas == null) {
      document.getElementById("etiquetas").focus();
      alert("Debe seleccionar una etiqueta");
      return false;
    }
      return true;




  }


  function validacionform_enviarmail(){

    var asunto            = document.getElementById("asunto").value;
    var mensaje           = document.getElementById("editor").innerHTML;
    var etiquetas         = document.getElementById("etiquetas").selectedIndex;
    var CC                = document.getElementById("CC").value;
    var CCO               = document.getElementById("CCO").value;   
    var email             = document.getElementById("mail").value;
    var inpCC             = document.getElementById('inpCC').value;
    var inpCCO            = document.getElementById('inpCCO').value;

 
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    
    if(email == null || email.length == 0 || /^\s+$/.test(email) || (!validar_mail("mail"))) {
      document.getElementById("mail").focus();
      alert('Debe ingresar un e-mail valido'+"\n"+'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).');
      return false;
    }else if(inpCC == 1) {
      if(CC == null || /^\s+$/.test(CC) || (!validar_mail("CC"))){
        document.getElementById("CC").focus();
        alert('Debe ingresar un e-mail valido'+"\n"+'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).');
        return false;
      }
    }else if(inpCCO == 1) {
      if(CCO == null || /^\s+$/.test(CCO) || (!validar_mail("CCO"))){
        document.getElementById("CCO").focus();
        alert('Debe ingresar un e-mail valido'+"\n"+'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).');
        return false;
      }
    }else if (etiquetas == null || etiquetas == 0 ) {
      alert("Debe seleccionar una etiqueta");
      return false;
    }else if( asunto == null || asunto.length == 0 || /^\s+$/.test(asunto) ) {
      document.getElementById("asunto").focus();
      alert("Debe ingresar un asunto");
      return false;
    }else if( mensaje == null || mensaje.length == 0 || /^\s+$/.test(mensaje) ) {
      document.getElementById("cuerpo").focus();
      alert("Debe ingresar un mensaje");
      return false;
    }

      return true;




  }
  
  function validacionform_interno(){

    var titulo            = document.getElementById("titulo").value;
    var mensaje           = document.getElementById("mensaje").value;
    var dusuario          = document.getElementById("dusuario").selectedIndex;
    var drol              = document.getElementById("drol").selectedIndex; 
    var etiquetas          = document.getElementById("etiquetas").selectedIndex;

    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if( titulo == null || titulo.length == 0 || /^\s+$/.test(titulo) ) {
      document.getElementById("titulo").focus();
      alert("Debe ingresar un titulo");
      return false;
    }else if( mensaje == null || mensaje.length == 0 || /^\s+$/.test(mensaje) ) {
      document.getElementById("mensaje").focus();
      alert("Debe ingresar un mensaje");
      return false;
    }else if(etiquetas == null || etiquetas == 0 ) {
      alert("Debe seleccionar una Etiqueta.");
      return false;
    }else if( (dusuario == null || dusuario == 0 ) && (drol == null || drol == 0)) {
      alert("Debe seleccionar un usuario o rol a derivar");
      return false;
    }else if( (dusuario != "") && (drol != "")) {
      alert("Solo puede seleccionar un usuario o rol a derivar");
      return false;
    }
      return true;




  }


  function validacionform_responderticket(){

    
    var cuerpo          = document.getElementById("editor").innerHTML;
    var receptor        = document.getElementById("receptor").value;
    var CC              = document.getElementById("CC").value;
    var CCO             = document.getElementById("CCO").value;
    var nombreticket    = document.getElementById("nombreticket_asunto").value;
    var inpCC             = document.getElementById('inpCC').value;
    var inpCCO            = document.getElementById('inpCCO').value;

    
    if (nombre_ticket == "") {
       var etiquetas     = document.getElementById("etiquetas").selectedIndex;
    }

    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

   if( cuerpo == null || cuerpo.length == 0 || /^\s+$/.test(cuerpo) ) {
      document.getElementById("cuerpo").focus();
      alert("Debe ingresar un mensaje");
      return false;
    }else if(receptor == null || /^\s+$/.test(receptor) || !validar_mail("receptor")) {
      alert("El mail al que se desea responder no posee un formato correcto");
      return false;
    }else if(inpCC == 1) {
      if(CC == null || /^\s+$/.test(CC) || (!validar_mail("CC"))){
        document.getElementById("CC").focus();
        alert('Debe ingresar un e-mail valido'+"\n"+'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).');
        return false;
      }
    }else if(inpCCO == 1) {
      if(CCO == null || /^\s+$/.test(CCO) || (!validar_mail("CCO"))){
        document.getElementById("CCO").focus();
        alert('Debe ingresar un e-mail valido'+"\n"+'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).');
        return false;
      }
    }else if(nombreticket == null || nombreticket.length == 0 || /^\s+$/.test(nombreticket) ) {
       if(etiquetas == null || etiquetas == 0 ) {
        document.getElementById("etiquetas").focus();
        alert("Debe seleccionar una etiqueta");
        return false;
      }
    }
      return true;




  }

function validar_mail(email){
  theElement = document.getElementById(email);
  valido = true; //Variable que indicará el estado de la validación
  if(theElement.value.length != 0 && !/^\s+$/.test(theElement.value)){
    theElement.value = eliminar_puntocoma(theElement.value);
    var emails = theElement.value.split(";"); //Creo un array con los e-mails ingresados y separados por comas
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    for (var i = 0, l = emails.length; i < l; i++){ //Recorro el array
      if (!filter.test(emails[i])){ //Si el e-mail no es válido
        valido = false; //El estado de la validación será false
        return false;
        break; //Salgo del bucle
      }
    }
  } 
  if (valido || !theElement.value.length){ //Si el estado de la validación es true o si no se ingresaron datos
    return true;
  }
}

function Validacionfrom_contactos(){

    var nombre            = document.getElementById("nombre").value;
    

    if( nombre == null || nombre.length == 0 || /^\s+$/.test(nombre) ) {
      document.getElementById("nombre").focus();
      alert("Debe ingresar un nombre");
      return false;
    }
      return true;
}

function validacionform_notificacion(){

    
    var cuerpo          = document.getElementById("editor").innerHTML;
    var titulo        = document.getElementById("titulo").value;
    
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

   if( cuerpo == null || cuerpo.length == 0 || /^\s+$/.test(cuerpo) ) {
      document.getElementById("cuerpo").focus();
      alert("Debe ingresar un mensaje");
      return false;
    }else if(titulo == null || /^\s+$/.test(titulo) || titulo.length == 0) {
      alert("Debe completar el Titulo.").focus();
      return false;
    }
      return true;
  }

  function validacionform_archivar(){
        
    var etiquetas     = document.getElementById("etiquetas").selectedIndex;

    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if(etiquetas == null || etiquetas == 0 ) {
      document.getElementById("etiquetas").focus();
      alert("Debe seleccionar una etiqueta");
      return false;
    }
    
    return true;
  }

/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/
/***************************VALIDACIONES FORMULARIOS***********************************/

