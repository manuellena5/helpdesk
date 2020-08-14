<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
?>

	<h4>Enviar Mail</h4>
  <div id="jGrowl-container1" class="bottom-left vOculto"></div>
<form enctype='multipart/form-data' method='post' id='formregistro' name="formregistro">
	<div class='form-group'>
   		<input type='text' name='quienresp' id="quienresp" value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
      <input type='text' class='form-control' id='firma' name='firma' value="<?php echo $_SESSION['firma']; ?>" hidden='hidden'>

   		
      
   		<div class="container "> 
            <div class="row"> 
            <label>Email*:</label>
            </div>
            <div class="row">
              <div class="col-10 sinpaddingleftright">
                 <input type='email' class='form-control' id='mail' name='mail' placeholder="Mail" ><br>
              </div>
              <div class="col sinpaddingleftright">
                <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('mail');"><i class="fas fa-clipboard-list fa-2x"></i></button></span> 
              </div>
            </div>
      </div> <!-- fin div container-->    
      
      <div class="container "> 
          <div class="row">         
            <div class="col-1">
              
                <input class="form-check-input" type="checkbox" name="inpCC" id="inpCC" value="0" >
                <label class="form-check-label">CC:</label>
              
            </div>
              

            <div class="col-10 ">
              <div id="oculCC" class="vOculto">  
                    <input type='email' class='form-control' name='CC' id='CC' placeholder="CC" ><br>
              </div>  
            </div>

              <div class="col-1 sinpaddingleftright">
                    
                <div id="oculCC2" class="vOculto">  
                    <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('cc');"><i class="fas fa-clipboard-list fa-2x"></i></button></span> 
                </div>
              </div>
                
                    
            
          </div>
          <br>
          <div class="row">
              <div class="col-1">
                  <input class="form-check-input" type="checkbox" name="inpCCO" id="inpCCO" value="0">
                  <label class="form-check-label" for="defaultCheck1">
                    CCO:
                  </label>
                  
                
              </div>
              
            <div class="col-10">  
                <div id="oculCCO" class="vOculto">
                    <input type='email' class='form-control' name='CCO' id='CCO' placeholder="CCO"  >
                 </div>
            </div>

            <div class="col-1 sinpaddingleftright"> 
                  <div id="oculCCO2" class="vOculto">
                      <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('cco');"><i class="fas fa-clipboard-list fa-2x"></i></button></span> 
                   </div>   
             </div>
            
           
          </div>
      </div> <!-- fin div container-->    
      
      <br>
        <div class="row">
          <div class="col-5"> 
             
             <select class='form-control' id='etiquetas' name='etiquetas'>
                          <option value="0" disabled selected>Seleccione una etiqueta*</option>

              </select>

          </div>
          
          <div class="vOculto" id="divsecuetiquetas" name="divsecuetiquetas"> 
                        
           <select class="custom-select" multiple id="secuetiquetas" name="secuetiquetas">
                  <option value="0" disabled selected>Seleccione una etiqueta</option>
           </select>
                     
          </div>


        </div>

   		<label>Asunto*:</label>
   		<input type='text' class='form-control' id='asunto' name='asunto' placeholder="Asunto"><br>
   		<label>Mensaje*:</label><br>
   		<textarea class='form-control' id='cuerpo' name='cuerpo' ></textarea><br>
      <div class="row">
        <div class="col-6">
         	<label>Archivos adjuntos (Para seleccion multiple -> tecla CTRL)</label><br>
         	<input type='file' multiple='multiple' class='form-control-file' id='adjunto' name='archivo[]'><br>
        </div>
        <div class="col-6">
          <label>Agregar Expediente:</label><br>
            <select class='form-control' id='expediente' name='expediente'>
              <option value="0" disabled selected>Seleccione un expediente</option>
              <?php $sqql = mysqli_query($con, "SELECT * FROM `fil00par` WHERE `parcod`=8 order by pardesc asc");
              while ($res = mysqli_fetch_array($sqql)) {
                echo "<option value='".$res['parvalor']."'>".$res['pardesc']."</option>";
              }
              ?>
            </select> 
        </div>
      </div>
      <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small><br>

      <button type='button' id='envioform' class='btn btn-primary' onclick="funcionclick();">Enviar</button>
      
	</div>

   
   <div class="vOculto" id="cargador"></div>
   
   

</form>
    



<!-- Modal 

-->
<?php 
$agenda = 1;
include('modal.php'); ?>


  
<script>

  
  var valor;

  $(document).ready(function() {
    cargar_select(0);
    limpiarselect("secuetiquetas");
    $('#cuerpo').Editor();

      $('#cuerpo').Editor('setText', ['']);

      $('#btn-enviar').click(function(e){
        e.preventDefault();
        $('#cuerpo').text($('#cuerpo').Editor('getText'));
        $('#frm-test').submit();        
      });
  });

   $(document).on('change', '#etiquetas', function(event) {

            var valorselect = $("#etiquetas option:selected").val();
            var valorselectsecuetiquetas = $("#secuetiquetas option:selected").val();

            cargar_select(valorselect);

            if ((valorselect == 0) && (valorselectsecuetiquetas == 0)) {

              valor = "no";
              

            }else if((valorselect == 0) && (valorselectsecuetiquetas != 0)){
               
              valor = "no";
              
            
            }else if((valorselect != 0) && (valorselectsecuetiquetas == 0)){

              valor = "si";
              

             }

            ver_elementoHTML(valor,"divsecuetiquetas");  
  });

   


  $("#inpCC").on("click",function() {
    var oculCC = document.getElementById('oculCC');

    if(oculCC.className == 'vVisible'){
           
           $("#oculCC").removeClass("vVisible").addClass("vOculto");
           $("#oculCC2").removeClass("vVisible").addClass("vOculto");
           document.getElementById('inpCC').value = 0;
       }else{
          $("#oculCC").removeClass("vOculto").addClass("vVisible");
          $("#oculCC2").removeClass("vOculto").addClass("vVisible");
          document.getElementById('inpCC').value = 1;
         }
    
  });
  $("#inpCCO").on("click",function() {
    var oculCCO = document.getElementById('oculCCO');

    if(oculCCO.className == 'vVisible'){
           
           $("#oculCCO").removeClass("vVisible").addClass("vOculto");
           $("#oculCCO2").removeClass("vVisible").addClass("vOculto");
           document.getElementById('inpCCO').value = 0;
       }else{
          $("#oculCCO").removeClass("vOculto").addClass("vVisible");
          $("#oculCCO2").removeClass("vOculto").addClass("vVisible");
          document.getElementById('inpCCO').value = 1;
         }
    
  });


  function funcionclick(){
    document.getElementById("envioform").disabled = true;
    if (validacionform_enviarmail()) {
      
        var contadorvalor = "";
        $("#secuetiquetas :selected").each(function(){
          if($(this).val() != "" &&  $(this).val() > 0){
            contadorvalor += $(this).val()+";";
          }
        });
        
        var inputFileImage = document.getElementById("adjunto");
        var file = inputFileImage.files[0];
        var i = 0;
        //var form = $("#formulario").serialize();
        var mail        = document.getElementById("mail").value;
        var asunto      = document.getElementById("asunto").value;
        var cuerpo      = document.getElementById("editor").innerHTML;
        var quienresp   = document.getElementById("quienresp").value;
        var CC          = document.getElementById("CC").value;
        var CCO         = document.getElementById("CCO").value;
        var inpCC       = document.getElementById("inpCC").value;
        var inpCCO      = document.getElementById("inpCCO").value;
        var firma       = document.getElementById("firma").value;
        var etiquetas   = document.getElementById("etiquetas").value;
        
        if(document.getElementById("expediente").selectedIndex != ""){
          var expediente         = document.getElementById("expediente").value;
        } else {
          var expediente         = "0";
        }


        var data = new FormData();
        data.append('nroarchivos',i);
        data.append('mail',mail);
        data.append('asunto',asunto);
        data.append('cuerpo',cuerpo);
        data.append('quienresp',quienresp);
        data.append('CC',CC);
        data.append('CCO',CCO);
        data.append('inpCC',inpCC);
        data.append('inpCCO',inpCCO);
        data.append('firma',firma);
        data.append('secuetiquetas',contadorvalor);
        data.append('etiquetas',etiquetas);
        data.append('expediente',expediente);



        jQuery.each($('#adjunto')[0].files, function(i, file) {
          data.append('archivo'+i, file);
          i = i + 1 ;
          data.append('nroarchivos',i);
        });
        
        //data.append('fileToUpload',file);
        //data.append('form',form);
        //var cargador = document.getElementById("cargador");
        $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
        $("#cargador").removeClass("vOculto").addClass("vVisible");
        


        $.ajax({
           url: "_enviarmail.php",        // Url to which the request is send
          type: "POST",             // Type of request to be send, called as method
          data: data,         // Data sent to servecontar_estadosr, a set of key/value pairs (i.e. form fields and values)
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData:false,        // To send DOMDocument or non processed data file it is set to false
          
        })
        .done(function(respuesta) {
           
          if (respuesta.success) {
          $("#oculCCO").removeClass("vVisible").addClass("vOculto");
          $("#oculCC").removeClass("vVisible").addClass("vOculto");
          $("#cargador").removeClass("vVisible").addClass("vOculto");
          cronmenu(nrousuario,rol,"2");
          document.getElementById("formregistro").reset();
          document.getElementById("editor").innerHTML = "";
          $("#jGrowl-container1").removeClass("vOculto").addClass("vVisible"); 
          ver_elementoHTML("no","divsecuetiquetas");
          
          }else{
             $("#cargador").removeClass("vVisible").addClass("vOculto");
              $("#jGrowl-container1").removeClass("vVisible").addClass("vOculto");
            alert(respuesta.error.mensaje);
          }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $("#cargador").removeClass("vVisible").addClass("vOculto");
           console.log("Algo ha fallado: " +  textStatus);
         
        })
        .always(function() {
           $("#cargador").removeClass("vVisible").addClass("vOculto");
            document.getElementById("envioform").disabled = false;
        });
        
        $('#jGrowl-container1').jGrowl("El mail se envió correctamente.", {
          header: 'Enviar Mail',
          theme:  'manilla',
          glue: 'before'
        });
       }else{
        document.getElementById("envioform").disabled = false;
       }//fin del if si los campos fueron ingresados correctamente 

      }


      function agenda(variable){
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
  };

  $('#seleccion').on('click', function(e){
    var mensaje         = "";
    var separador       = "";
    var emails          = "";
    var rows_selected   = table.column(0).checkboxes.selected();
    if(rows_selected.length > 1){
      separador         = ";"; 
    }
    
    //Iterar sobre todas las casillas de verificación seleccionadas
    $.each(rows_selected, function(index, rowId){
      //Crea un elemento oculto
      row = rowId; 
      mensaje += rowId + separador;
    });

    if(tipo=='cc'){
      var CC            = document.getElementById("CC").value;
      if(CC != ""){
        CC = CC + ";";
      }
      emails = CC+mensaje;
      
      emails = eliminar_puntocoma(emails);
      document.getElementById("CC").value= emails;
    } else if(tipo=='cco'){
      var CCO           = document.getElementById("CCO").value;
      if (CCO != "") {
        CCO = CCO + ";";
      }
      emails = CCO+mensaje;
      
      emails = eliminar_puntocoma(emails);
      document.getElementById("CCO").value=emails;
    } else if(tipo=='mail'){
      var mail           = document.getElementById("mail").value;
      if (mail != "") {
        mail = mail + ";";
      }
      emails = mail+mensaje;
      
      emails = eliminar_puntocoma(emails);
      document.getElementById("mail").value=emails;
    }
   });

  $('#tablaAgenda').on('error.dt', function(e, settings, techNote, message) {
    alert("Ocurrió un error al cargar la tabla");
    console.log('Ocurrió un error al cargar la tabla: ', message);
  });


      
</script>
