<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); 
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>

<h3>Generar Nueva Notificacion</h3>
<form class="form-inline" id="formulario">
  	<div class="container">
		<div class="row">
			<div class="col-12">
    			<label for="staticEmail2" >Titulo:</label>
  			</div>
  			<div class="col-12">
  				<input type="text" name="titulo" class="form-control campo_mayu" id="titulo" placeholder="Titulo" style="width: 100%;">
  			</div>
  			<div class="container">
    <div class="row">
      <div class="col-sm">
        <div class='form-group'>
          <select class='form-control' id='dusuario' name='dusuario' style="width: 100%;">
            <option value="">Seleccione Usuarios*</option>
              <?php
              $users = mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg order by nombre asc");
              while($ruser = mysqli_fetch_array($users)){
                echo "<option value='".$ruser['nrousuario']."'>".$ruser['nombre']."</option>";
              }
              ?>
          </select>
        </div>
      </div>
      <div class="col-sm">
        <div class='form-group'>
          <select class='form-control' id='drol' name='drol' style="width: 100%;">
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
    </div>
  </div>
  			<div class="col-12">
    			<label for="staticEmail2" >Mensaje:</label>
  			</div>
  			<div class="col-sm">
    			<textarea class='form-control' id='cuerpo' name='cuerpo' ></textarea>
  			</div>
  		</div>
  	</div>
</form>
<div class="vOculto" id="cargador"></div>
<button type="button" class="btn btn-primary mb-2" onclick="funcion_notif();">Confirmar</button>
<script>
	$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
$('#cuerpo').Editor();
$('#cuerpo').Editor('setText', ['']);
$('#btn-enviar').click(function(e){
    e.preventDefault();
    $('#cuerpo').text($('#cuerpo').Editor('getText'));
    $('#frm-test').submit();        
});

function funcion_notif(){
  //document.getElementById("envioform").disabled = true;
	if (validacionform_notificacion()) {
        
    var cuerpo          = document.getElementById("editor").innerHTML;
    var titulo          = document.getElementById("titulo").value;
    var dusuario            = document.getElementById("dusuario").value;
    var drol                = document.getElementById("drol").value;

    if(document.getElementById("drol") != null){
      var drol              = document.getElementById("drol").value;
    } else {
      var drol              = "";
    }

    if(document.getElementById("dusuario") != null){
      var dusuario          = document.getElementById("dusuario").value;
    } else {
      var dusuario          = "";
    }

    var data = new FormData();
      data.append('cuerpo',cuerpo);
      data.append('titulo',titulo);
      data.append('dusuario',dusuario);
      data.append('drol',drol);
      
    $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
    $("#cargador").removeClass("vOculto").addClass("vVisible");
        
    $.ajax({
      url: "notificaciones/_generar_notif.php",      // Url to which the request is send
      type: "POST",               // Type of request to be send, called as method
      data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
      processData:false,          // To send DOMDocument or non processed data file it is set to false
      dataType: 'json',

    })
      .done(function(respuesta){
      if(respuesta.success){
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        
        cronmenu(nrousuario,rol,"2");
        cargarPagina('notificaciones/generar_notificacion.php?ver=si&men=Se genero la Notificacion con Exito.&donde=Generar Notificacion');
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
          
      });
      }
}
</script>