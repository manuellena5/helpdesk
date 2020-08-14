/* 20179346584  Tiene domicilio vacio */	
  


   var arreglo;
    var valor;
      

    $(document).ready(function() {

    	/*
        limpiarformulario();
        valor="no";
        mostrarelemento(valor,'divbtnws');
        mostrarelemento(valor,'divseleccion');
        mostrarelemento(valor,'divbotones');
        mostrarelemento(valor,'div_comitente_sel');
        $('#campocuit').val("");
        document.getElementById("campocuit").readOnly = false;
        document.getElementById("domicilio").readOnly = false;
        document.getElementById("localidad").readOnly = false;
        */
        inicializar_campos();

    });

    formulario = document.querySelector('#frmcomitentenuevo');
    formulario.campocuit.addEventListener('keypress', function (e){
      if (!soloNumeros(event)){
        e.preventDefault();
      }
    })

    //Solo permite introducir numeros.
    function soloNumeros(e){
        var key = e.charCode;
        return key >= 48 && key <= 57;
    }
 

 
    function inicializar_campos(){

    	 limpiarformulario();
        valor="no";
        mostrarelemento(valor,'divbtnws');
        mostrarelemento(valor,'divseleccion');
        mostrarelemento(valor,'divbotones');
        mostrarelemento(valor,'div_comitente_sel');
        $('#campocuit').val("");
        document.getElementById("campocuit").readOnly = false;
        document.getElementById("domicilio").readOnly = true;
        document.getElementById("localidad").readOnly = true;
        document.getElementById("razonsocial").readOnly = true;
        document.getElementById("provincia").readOnly = true;
        
		

    }

	  function buscar_cuit(opcionboton){
        
        valor = "no";
        mostrarelemento(valor,'divbtnws');
        mostrarelemento(valor,'divbotones');
        mostrarelemento(valor,'divseleccion');
        limpiarformulario();
        limpiarselect();

        
        var cuit=validar_cuit(document.getElementById("campocuit").value);
      if (cuit.length<=0){
         window.alert("CUIT Inválido");


         return false;
      }

	  	//var campocuit = $("#campocuit").val();
      var campocuit = $("#campocuit").val();
      var opcion = opcionboton;
      
    
      $("#cargador").html('<div class="loading"><img src="afip/imagenes/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
      $("#cargador").removeClass("vOculto").addClass("vVisible");


     	$.ajax({
     		url: 'afip/traer_comitente.php',
     		type: 'POST',
     		dataType: 'json',
     		data: {campocuit: campocuit,opcion:opcion},
     	})
     	.done(function(respuesta) {
          //console.log(respuesta);
          if (respuesta.success) {
            $("#cargador").removeClass("vVisible").addClass("vOculto");

            if ((respuesta.ws==false) && (respuesta.cantidad==1)){
                      valor = "si";
                      mostrarelemento(valor,'divbotones');
                      mostrarelemento(valor,'divbtnws');
                   }else if (!respuesta.ws) {
                      valor = "si";
                      mostrarelemento(valor,'divbtnws');
                     
                   }



            if (respuesta.cantidad > 1) {

                          arreglo = respuesta;
                         

                          var s = document.getElementById("empresa"); 
                          valor = "si";
                          mostrarelemento(valor,'divseleccion');

                          for (var i = 0; i < respuesta.cantidad; i++) {
                              
                              var option=document.createElement("option"); 
                              option.value=respuesta[i]["id_comitente"]; 
                              option.text=respuesta[i]["nombre_comitente"]; 

                              s.appendChild(option);
                          }
                          
                  }else{
                      
                      valor = "no";
                      mostrarelemento(valor,'divseleccion');
                      
                      cargarformulario(respuesta);
                      
                      if ((respuesta.ws) && (!respuesta.cuitvalido)) {

                            document.getElementById("mensaje").innerHTML="Informacion extraida de AFIP, ante cualquier duda, consulte con Administracion";
                          }else if ((respuesta.ws) && (respuesta.cuitvalido)) {
                              document.getElementById("campocuit").readOnly = true;
                              
                              habilitar_domic_loc();
                              
                              valor = "si";
                              mostrarelemento(valor,'divbotones');
                              
                              document.getElementById("mensaje").innerHTML="Informacion extraida de AFIP, ante cualquier duda, consulte con Administracion";
                        }  
                  }




            
          }else{
                 $("#cargador").removeClass("vVisible").addClass("vOculto");
                  alert(respuesta.error.mensaje);
          }

     	})
     	.fail(function(jqXHR,textStatus,errorThrown) {
            $("#cargador").removeClass("vVisible").addClass("vOculto");
     		console.log("error " + textStatus  + "  " + errorThrown + "  " + jqXHR.status);
     	})
     	.always(function() {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
          
      });





	  }
      

      $(document).on('change', '#empresa', function(event) {
            limpiarformulario();
            valor = "si";
            mostrarelemento(valor,'divbotones');
            $('#id_com').val($("#empresa option:selected").val());
            var eleccion = $('#id_com').val();
            for (var i = 0; i < arreglo.cantidad; i++) {
                if (arreglo[i]["id_comitente"] == eleccion) {
                    cargarformulario(arreglo[i]);
                    break;
                }    
            }


        });


      function cargarformulario(empseleccionada){
            
           
            $('#localidad').val(empseleccionada.localidad);
        if (empseleccionada.ws) {
             
             $('#razonsocial').val(empseleccionada.razonSocial);
            $('#domicilio').val(empseleccionada.direccion);
            $('#provincia').val(empseleccionada.idProvincia);
            $('#id_tipodoc').val(empseleccionada.id_tipodoc);
        }else{
            
             $('#razonsocial').val(empseleccionada.nombre_comitente);
            $('#domicilio').val(empseleccionada.domicilio_comitente);
            $('#provincia').val(empseleccionada.nombre);
            $('#id_com').val(empseleccionada.id_comitente);
        }
      


      }

      function limpiarformulario(){
        
       

        $('#id_com').val("");
        $('#razonsocial').val("");
        $('#localidad').val("");
        $('#domicilio').val("");
        $('#provincia').val("");
        $('#id_tipodoc').val("");
        document.getElementById("mensaje").innerHTML="";

      }

      function limpiarselect(){
        var sel = document.getElementById("empresa");
        for (i = sel.length - 1; i >= 1; i--) {
            sel.remove(i);
        }
        //sel.length = 1;


      }




      function mostrarelemento(valor,elemento){

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

     function cargar_seleccionado(){

        $("#id_comitente_sel").val($('#id_com').val());

        $("#nombre_comitente_sel").val($('#razonsocial').val());
        valor="si";
        mostrarelemento(valor,'div_comitente_sel');
        //document.getElementById("nombre_comitente_sel").style.display = "block";
     }

     function guardarNuevoComitente(){


      var formulario = $("#frmcomitentenuevo");
      if (validar_campos()) {
          //if (true) {
      $.ajax({
        url: 'afip/comitente_guardar.php',
        type: 'POST',
        dataType: 'json',
        data: formulario.serialize(),
        
      })
      .done(function(respuesta) {
        //console.log(respuesta);
        if (respuesta.success) {

        $("#id_comitente_sel").val(respuesta.id_comitente);
        $("#nombre_comitente_sel").val(respuesta.razonSocial);
        valor="si";
        mostrarelemento(valor,'div_comitente_sel');
        //console.log(respuesta);
        }else{
          alert(respuesta.mensaje);
        }
      })
      .fail(function(jqXHR,textStatus,errorThrown) {
        console.log("error " + textStatus  + "  " + errorThrown + "  " + jqXHR.status);
      })

      }





     }

     function validar_campos(){

      var cuit =  document.getElementById("campocuit").value;
      var razonsocial =  document.getElementById("razonsocial").value;
      var localidad =  document.getElementById("localidad").value;
      var domicilio =  document.getElementById("domicilio").value;


      if (cuit == null || cuit.length == 0 ||  /^\s+$/.test(cuit)) {
        document.getElementById("campocuit").focus();
        alert("Debe ingresar un cuit");
        return false;
      }else if(razonsocial == null || razonsocial.length == 0 ||  /^\s+$/.test(razonsocial)){

        document.getElementById("razonsocial").focus();
        alert("Faltan datos, vuelva a intentar");
        return false;

      }else if((document.getElementById("localidad").readOnly == false) && (localidad == null || localidad.length == 0 ||  /^\s+$/.test(localidad))){

        document.getElementById("localidad").focus();
        alert("Faltan datos, vuelva a intentar");
        return false;

      }else if((document.getElementById("domicilio").readOnly == false) && (domicilio == null || domicilio.length == 0 ||  /^\s+$/.test(domicilio))){

        document.getElementById("domicilio").focus();
        alert("Faltan datos, vuelva a intentar");
        return false;

      }
      return true;




     }


     function habilitar_domic_loc(){


      var localidad =  document.getElementById("localidad").value;
      var domicilio =  document.getElementById("domicilio").value;


      if (localidad == null || localidad.length == 0 ||  /^\s+$/.test(localidad)) {
        document.getElementById("localidad").readOnly = false;

      }

      if(domicilio == null || domicilio.length == 0 ||  /^\s+$/.test(domicilio)){

        document.getElementById("domicilio").readOnly = false;

      }



     }
