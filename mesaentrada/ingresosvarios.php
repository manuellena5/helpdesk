<?php
session_start();
include ("../connect.php");
include("../funciones.php");
salir();
?>

<div>
	<h4>Ingresos Varios</h4>
  <div id="jGrowl-container1" class="bottom-left vOculto"></div>
  <form  enctype='multipart/form-data' method='post' id='formregistro' name="formregistro">
      <div class="rounded float-right">
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="ingreso" value="I" checked>
        <label for="ingreso">Ingreso Documentación</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="envio" value="E">
        <label for="envio">Envio Documentación</label>
      </div>
    </div><br>
  	<div class='form-group'>
     		<input type='text' name='quienenvia' id="quienenvia" value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
        <label>Titulo*:</label>
        <input type='test' class='form-control' id='titulo' name='titulo' placeholder="Titulo"  ><br>
        <div class="container">
          <div class="row">
            <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='entidades' name='entidades'>
                <option value="0">Seleccione Entidades*</option>
                  <?php
                  $users = mysqli_query($con, "SELECT `codigo`,`razon` FROM `fil01ent` order by razon asc");
                  while($ruser = mysqli_fetch_array($users)){
                    echo "<option value='".$ruser['codigo']."'>".$ruser['razon']."</option>";
                  }
                  ?>
              </select>
            </div><br>
            <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='documento' name='documento'>
                <option value="">Seleccione Tipo Documento</option>
                  <?php
                  $rols = mysqli_query($con, "SELECT `parvalor`,`pardesc` FROM `fil00par` WHERE `parcod`=7 order by pardesc asc");
                  while($rrol = mysqli_fetch_array($rols)){
                    echo "<option value='".$rrol['parvalor']."'>".$rrol['pardesc']."</option>";
                  }
                  ?>
              </select>
            </div>
          </div>
        </div><br>
        <label>Mensaje:</label><br>
        <textarea class='form-control' id='mensaje' name='mensaje' ></textarea><br>
        <div class="container">
          <div class="row" style="align-items: baseline;">
            <div class="col-6 sinpaddingleftright">
              <label>Email:</label>
              <input type='email' class='form-control' id='email' name='email' placeholder="Email" >
            </div>
            <div class="col-1 sinpaddingleftright"> 
                <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda();"><i class="fas fa-clipboard-list fa-2x"></i></button></span> 
              </div>
            <div class="col">
              <label>Archivos adjuntos (Para seleccion multiple -> tecla CTRL)</label>
              <input type='file' multiple='multiple' class='form-control-file' id='adjunto' name='archivo[]'><br>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-sm sinpaddingleftright">
              <label for="observacion">Observacion:</label>
                <textarea class="form-control" id="observacion" name="observacion" value="" rows="3"></textarea><br>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='dusuario' name='dusuario'>
                <option value="">Seleccione Usuarios*</option>
                  <?php
                  $users = mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg order by nombre asc");
                  while($ruser = mysqli_fetch_array($users)){
                    echo "<option value='".$ruser['nrousuario']."'>".$ruser['nombre']."</option>";
                  }
                  ?>
              </select>
            </div><br>
            <div class="col-sm sinpaddingleftright">
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
        <div class="container sinpaddingleftright">
          <div class="row">
            <div class="col-5"> 
              
               <select class='form-control' id='etiquetas' name='etiquetas'>
                
                <option value="0" disabled selected>Seleccione una etiqueta*</option>

                </select>

            </div>
            
            <div class="vOculto" id="divsecuetiquetas" name="divsecuetiquetas"> 
              <select class="custom-select" multiple id="secuetiquetas" name="secuetiquetas">
                <option value="0" disabled >Seleccion multiple de etiqueta</option>
              </select>
                           
            </div>

          </div>
        </div>
        <br>
        <div class="container">
          <div class="row">
            <div class="col">
              <div class="form-check form-inline" >
                <label class="form-check-label" for="defaultCheck1">
                Numero mesa de entrada: <div id='num' class='vVisible'></div>
                </label>
                <input class="form-check-input" type="checkbox" id="NUMMESAENT" name="NUMMESAENT" value="1" checked>
              </div>
            </div>
          </div>
        </div> 
        <div class="container">
          <div class="row">
            <div class="col">
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

         <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small><br>
     <div class="vOculto" id="cargador"></div>
     <button type='button' onclick="funcionclick();"  value='submit' id='envioform' class='btn btn-primary'>Ingresar</button>

  	</div>
    
  </form>
</div>


<!-- Modal -->
<?php 
$agenda = 1;
include('../modal.php'); ?>

<script type="text/javascript">

  $("#NUMMESAENT").on("click", function(){

    if($("input[type='checkbox']").is(':checked') === true){
        mostrar_nro_mesaentrada(true);
      
      }else{
      
        mostrar_nro_mesaentrada(false);
      
      }     

  });

  $(document).on('change', '#NUMMESAENT', function(event) {

      if($("input[type='checkbox']").is(':checked') === true){
        mostrar_nro_mesaentrada(true);
      
      }else{
      
        mostrar_nro_mesaentrada(false);
      
      }   

});
  
$(document).ready(function() {   
      cargar_select(0);
      limpiarselect("secuetiquetas");
      if($("input[type='checkbox']").is(':checked') === true){
        mostrar_nro_mesaentrada(true);
      }
});






$(document).on('change', '#etiquetas', function(event) {

            var valorselect = $("#etiquetas option:selected").val();
            var valorselectsecuetiquetas = $("#secuetiquetas option:selected").val();

            cargar_select(valorselect);

            if (valorselectsecuetiquetas != 0) {

              limpiarselect("secuetiquetas");
              
            }else{
              valor = "si";
            }

            
            
            if (valorselect == 0) {
              valor = "no";
            }else{
              valor="si";
            }

            ver_elementoHTML(valor,"divsecuetiquetas");  
  });



  var check = "I";
   $(document).on('change','input[type="radio"]' ,function(e) {
       check = this.value;
    });
   


function funcionclick(){
document.getElementById("envioform").disabled = true;

var contadorvalor = "";
    $("#secuetiquetas :selected").each(function(){
      if($(this).val() != "" &&  $(this).val() > 0){
        contadorvalor += $(this).val()+";";
      }
    });
  if (validacionform_ingresosvarios()) {


  var inputFileImage    = document.getElementById("adjunto");
  var file              = inputFileImage.files[0];
  var i                 = 0;
  var quienenvia        = document.getElementById("quienenvia").value;
  var titulo            = document.getElementById("titulo").value;
  var mensaje           = document.getElementById("mensaje").value;
  var email             = document.getElementById("email").value;
  var observacion       = document.getElementById("observacion").value;
  var dusuario          = document.getElementById("dusuario").value;
  var drol              = document.getElementById("drol").value;
  var NUMMESAENT        = document.getElementById("NUMMESAENT").value;
  var etiquetas         = document.getElementById("etiquetas").value;
  var entidades         = document.getElementById("entidades").value;


  if(document.getElementById("documento").selectedIndex != ""){
      var documento         = document.getElementById("documento").value;
    } else {
      var documento         = "0";
    }

  if(document.getElementById("expediente").selectedIndex != ""){
      var expediente         = document.getElementById("expediente").value;
    } else {
      var expediente         = "0";
    }

  var data = new FormData();
    data.append('nroarchivos',i);
    data.append('quienenvia',quienenvia);
    data.append('titulo',titulo);
    data.append('mensaje',mensaje);
    data.append('email',email);
    data.append('observacion',observacion);
    data.append('dusuario',dusuario);
    data.append('drol',drol);
    data.append('NUMMESAENT',NUMMESAENT);
    data.append('entidades',entidades);
    data.append('documento',documento);
    data.append('check',check);
    data.append('etiquetas',etiquetas);
    data.append('secuetiquetas',contadorvalor);
    data.append('expediente',expediente);
    
  jQuery.each($('#adjunto')[0].files, function(i, file) {
    data.append('archivo'+i, file);
    i = i + 1 ;
    data.append('nroarchivos',i);
  });

  $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
  $("#cargador").removeClass("vOculto").addClass("vVisible");


  $.ajax({
    url: "mesaentrada/_varios.php",  // Url to which the request is send
    type: "POST",                    // Type of request to be send, called as method
    data: data,                      // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false,              // The content type used when sending data to the server.
    cache: false,                    // To unable request pages to be cached
    processData:false,               // To send DOMDocument or non processed data file it is set to false
    
  })
  .done(function(respuesta) {
    if (respuesta.success) {
      if (respuesta.data > 0) {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        ver_elementoHTML("no","divsecuetiquetas");
        var correcto = "Se registraron los datos correctamente."+"\n" +"Numero de mesa:  "+respuesta.data;
        cronmenu(nrousuario,rol,"2");
      }else{
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        var correcto = "Se registraron los datos correctamente";
      }
      
      $("#jGrowl-container1").removeClass("vOculto").addClass("vVisible");    
      document.getElementById("formregistro").reset();
      mostrar_nro_mesaentrada(true);
      $('#jGrowl-container1').jGrowl(correcto, {
        header: 'Ingresos Varios',
        theme:  'manilla',
        glue: 'before'
      });
    }else{
      $("#cargador").removeClass("vVisible").addClass("vOculto");
      window.alert(respuesta.error.mensaje);
    }
  })
  .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
    $("#cargador").removeClass("vVisible").addClass("vOculto");
    console.log("Algo ha fallado: " +  textStatus);
  })  
  .always(function() {
    $("#cargador").removeClass("vVisible").addClass("vOculto");   
    document.getElementById("envioform").disabled = false;
  });
  }else{
    document.getElementById("envioform").disabled = false;
  }
}



function agenda(){
    
    table_ingreso_varios = $('#tablaAgenda').DataTable({
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
    var mailseleccion         = "";
    var separador       = "";
    var emails          = "";
    var rows_selected   = table_ingreso_varios.column(0).checkboxes.selected();
    if(rows_selected.length > 1){
      separador         = ";"; 
    }

    //Iterar sobre todas las casillas de verificación seleccionadas
    $.each(rows_selected, function(index, rowId){
      //Crea un elemento oculto
      mailseleccion += rowId + separador;
    });

      var campoemail           = document.getElementById("email").value;
      if (campoemail != "") {
        campoemail += ";";
      }
      emails    = campoemail + mailseleccion;
      emails = eliminar_puntocoma(emails);
      document.getElementById("email").value=emails;
   });

  $('#tablaAgenda').on('error.dt', function(e, settings, techNote, message) {
    alert("Ocurrió un error al cargar la tabla");
    console.log('Ocurrió un error al cargar la tabla: ', message);
  });



</script>
