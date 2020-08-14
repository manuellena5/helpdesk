<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$id_mail = $_GET['id_mail'];
$sql = mysqli_query($con, "SELECT * FROM `fil01mail` where id_mail='$id_mail'");
$re = mysqli_fetch_array($sql);
?>
 
<!-- Botones de Acciones -->
<div style="text-align:right">
  <button type='button' value='Send' id='envioform' class='btn btn-primary' onclick="archivar();">Archivar</button>
  <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('mesaentrada/ingresopormail.php?ver=no&men=&donde=');">Volver</button>
</div>

<!-- Formulario para enviar la Respuesta -->
<form method='post' id='formregistro' name="formregistro" enctype="multipart/form-data">
	<h4>Archivar Mail</h4><br>
  	<div class='form-group'>
    	<input type='text' name='numail' id="numail" value="<?php echo $re['id_mail']; ?>" hidden='hidden'> 
    </div>
    <div class="container">
      	<div class="row">
          	<div class="col-5"> 
            	<select class='form-control' id='etiquetas' name='etiquetas'>
                    <option value="0" disabled selected>Seleccione una etiqueta*</option>
                <?php
                  $rol5s = mysqli_query($con, "SELECT `pardesc`,`parvalor` FROM fil00par WHERE `parcod`='5' order by pardesc asc");
                  while($rro5l = mysqli_fetch_array($rol5s)){
                    echo "<option value='".$rro5l['parvalor']."'>".$rro5l['pardesc']."</option>";
                  }
                  ?>
            	</select>
          	</div>
      	</div>
    </div>
</form>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario">
      <div class="modal-body" id="cuerpo">
        <?php //include("agenda.php"); ?>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" id="seleccion" class="close" data-dismiss="modal" aria-label="Close">Seleccion</button>
      </div>
    </div>
  </div>
</div>
  
<script>
function archivar(){
  document.getElementById("envioform").disabled = true;
	if (validacionform_archivar()) {
        
    var numail          = document.getElementById("numail").value;

    if(document.getElementById("etiquetas") != null){
      var etiquetas     = document.getElementById("etiquetas").value;
    } else {
      var etiquetas     = "";
    }

    var data = new FormData();
      data.append('numail',numail); 
      data.append('etiquetas',etiquetas);

    $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
    $("#cargador").removeClass("vOculto").addClass("vVisible");
        
    $.ajax({
      url: "mesaentrada/_archivar.php",      // Url to which the request is send
      type: "POST",               // Type of request to be send, called as method
      data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
      processData:false,         // To send DOMDocument or non processed data file it is set to false
      dataType: 'json',
    })
      .done(function(respuesta){
      if(respuesta.success){
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        
        cronmenu(nrousuario,rol,"2");
        cargarPagina('mesaentrada/ingresopormail.php?ver=si&men=Se archivo correctamente el Mail.&donde=Ingreso por Mail');
      } else {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
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
      }else{
        document.getElementById("envioform").disabled = false;

      }
}
</script>