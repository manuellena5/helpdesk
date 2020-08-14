<?php
session_start();
include ("connect.php");
include("funciones.php");
salir();
?>
<div id="jGrowl-container1" class="bottom-left vOculto"></div>
<h4>Generar Ticket Interno</h4>
            <form  enctype='multipart/form-data' method='post' id='formregistro' name="formregistro">
              <div class='form-group'>
                  <label>Titulo*:</label>
                    <input type='test' class='form-control' id='titulo' name='titulo' placeholder="Titulo" ><br>

                  <label>Mensaje*:</label><br>
                    <textarea class='form-control' id='mensaje' name='mensaje' ></textarea><br>

                  <div class="container">
                    <div class="row">
                        <div class="sinpaddingleftright col-6">
                          <label>Archivos adjuntos (Para seleccion multiple -> tecla CTRL)</label><br>
                            <input type='file' multiple='multiple' class='form-control-file' id='adjunto' name='archivo[]'>
                        </div>
                        <div class="sinpaddingleftright col-6">
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
                  </div>
                  <br>
                  <div class="container">
                    <div class="row">
                      <div class="sinpaddingleftright col-sm">
                        <label for="exampleFormControlTextarea1">Observacion:</label>
                          <textarea class="form-control" id="observacion" name="observacion" value="" rows="3"></textarea><br>

                      </div>
                    </div>
                  </div>
                  <div class="container">
                    <div class="row">
                      <div class="sinpaddingleftright col-sm">
                        <select class='form-control' id='dusuario' name='dusuario'>
                          <option value="">Seleccione Usuarios*</option>
                            <?php
                            $users = mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg order by nombre asc");
                            while($ruser = mysqli_fetch_array($users)){
                              echo "<option value='".$ruser['nrousuario']."'>".$ruser['nombre']."</option>";
                            }
                            ?>
                        </select>
                      </div>
                      <div class="sinpaddingleftright col-sm">
                        
                        <select class='form-control' id='drol' name='drol'>
                          <option value="">Seleccione Rol</option>
                            <?php
                            $rols = mysqli_query($con, "SELECT pardesc,parvalor FROM fil00par WHERE parcod=1 order by pardesc asc");
                            while($rrol = mysqli_fetch_array($rols)){
                              echo "<option value='".$rrol['parvalor']."'>".$rrol['pardesc']."</option>";
                            }
                            ?>
                        </select>

                      </div>
                    </div>
                  </div><br>
                  <div class="container">
                    <div class="row">
                      <div class="sinpaddingleftright col-5"> 
                        
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
                  </div>  
                  
                <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small><br>
               <button type='button' onclick="funcionclick();"  value='submit' id='envioform' class='btn btn-primary'>Ingresar</button>

              </div>

               
            </form>      

<script type="text/javascript">
  var valor;

  $(document).ready(function() {
    cargar_select(0);
        limpiarselect("secuetiquetas");
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




  

function funcionclick () {
  document.getElementById("envioform").disabled = true;
if(validacionform_interno()){

   var contadorvalor = "";
        $("#secuetiquetas :selected").each(function(){
          if($(this).val() != "" && $(this).val() > 0){
            contadorvalor += $(this).val()+";";
          }
        });
        
  var inputFileImage = document.getElementById("adjunto");
  var file = inputFileImage.files[0];
  var i = 0;

  var observacion   = document.getElementById("observacion").value;
  var titulo           = document.getElementById("titulo").value;
  var etiquetas           = document.getElementById("etiquetas").value;
  var drol              = document.getElementById("drol").value;
  var dusuario          = document.getElementById("dusuario").value;
  var mensaje           = document.getElementById("mensaje").value;

  if(document.getElementById("expediente").selectedIndex != ""){
    var expediente         = document.getElementById("expediente").value;
  } else {
    var expediente         = "0";
  }
   var data = new FormData();
      data.append('nroarchivos',i);
      data.append('titulo',titulo);
      data.append('mensaje',mensaje);
      data.append('observacion',observacion);
      data.append('secuetiquetas',contadorvalor);
      data.append('etiquetas',etiquetas);
      data.append('drol',drol);
      data.append('dusuario',dusuario);
      data.append('expediente',expediente);

    jQuery.each($('#adjunto')[0].files, function(i, file) {
      data.append('archivo'+i, file);
      i = i + 1 ;
      data.append('nroarchivos',i);
    });




  $.ajax({
      url: "_interno.php",        // Url to which the request is send
      type: "POST",               // Type of request to be send, called as method
      data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
      processData:false,          // To send DOMDocument or non processed data file it is set to false
  })
  .done(function(respuesta) {
    if(respuesta.success){
        $("#jGrowl-container1").removeClass("vOculto").addClass("vVisible");
        cronmenu(nrousuario,rol,"2");
        document.getElementById("formregistro").reset();
        
        

        ver_elementoHTML("no","divsecuetiquetas");

      } else {
        alert(respuesta.error.mensaje);
      }
    
  })
  .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
        console.log("Algo ha fallado: " +  textStatus);
        alert(respuesta.error.mensaje);
  })
  .always(function() {
    document.getElementById("envioform").disabled = false;
  });
  

$('#jGrowl-container1').jGrowl("Se registraron los datos correctamente.", {
  header: 'Generar Ticket Interno',
  theme:  'manilla',
  glue: 'before'
});
}else{
  document.getElementById("envioform").disabled = false;
}
} 


  

     






</script>
