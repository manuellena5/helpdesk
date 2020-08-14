<?php 
session_start();
include("connect.php");
include("funciones.php");
salir(); 
$sql = mysqli_query($con, "SELECT * FROM `fil01lib` WHERE `mail`='".$_GET['mail']."'");
$re = mysqli_fetch_array($sql);
$cod = strpos($re['telef_fijo'], '-')-1;
$codigo_area_fijo = substr($re['telef_fijo'], 1, $cod);
$telef_fijo = substr($re['telef_fijo'], strpos($re['telef_fijo'], '-')+1);
$cod2 = strpos($re['telef_movil'], '-')-1;
$codigo_area_movil = substr($re['telef_movil'], 1, $cod);
$telef_movil = substr($re['telef_movil'], strpos($re['telef_movil'], '-')+3);
?>

<h3>Editar Contacto</h3>
<form  method="POST" accept-charset="utf-8" id="formulario">
	<div class="container">
	  <div class="row">
  	    <div class="col-6">
  	      <b>Nombre</b>
  	      <input type="text" name="nombre" class="form-control campousuario" value="<?php echo $re['nombre']; ?>" required>
  	    </div>
  	    <div class="col-6">
  	      <b>Email</b>
  	      <input type="text" name="email" class="form-control" value="<?php echo $re['mail']; ?>" required>
          <input type="text" name="mail" class="form-control" value="<?php echo $re['mail']; ?>" hidden='hidden'>
  	    </div>
        <br>
        <div class="col-sm">
          <b>Telefono Fijo</b>
          <div class="input-group">
            <div class="input-group-text">0</div><input type="text" name="telef_fijo_cod_area" id="telef_fijo_cod_area" class="form-control" value="<?php echo $codigo_area_fijo; ?>" required maxlength="4" style="width: 15%;">
            <div class="input-group-text">-</div><input type="text" name="telef_fijo_tel" id="telef_fijo_tel" class="form-control" value="<?php echo $telef_fijo; ?>" required maxlength="7" style="width: 25%;">
        </div>
        </div>
  	    <div class="col-sm">
  	      <b>Telefono Movil</b>
          <div class="input-group">
    	      <div class="input-group-text">0</div><input type="text" name="telef_movil_cod_area" id="telef_movil_cod_area" class="form-control" value="<?php echo $codigo_area_movil; ?>" required maxlength="4" style="width: 15%;">
            <div class="input-group-text">-15</div><input type="text" name="telef_movil_tel" id="telef_movil_tel" class="form-control" value="<?php echo $telef_movil; ?>" required maxlength="7" style="width: 25%;">
        </div>
  	    </div>
  	  </div>
  </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="editar();">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('contactos.php?ver=no&men=&donde=');">Volver</button>
<br>
<script>
  var formulario = document.querySelector("#formulario");
  formulario.telef_fijo_cod_area.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_fijo_tel.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_movil_cod_area.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_movil_tel.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
  function soloNumero(e){
    var key = e.charCode;
    return key >= 48 && key <= 57;
  }
function editar(){  
  $.ajax({
    url: '_modificarcontacto.php',
    type: 'POST',
    dataType: 'json',
    data: $("#formulario").serialize(),
  })
  .done(function(respuesta) {
    if (respuesta.success) { 
      cargarPagina(respuesta.url);
    }else{
      alert(respuesta.error.mensaje);
    }
  })
  .fail(function(respuesta) {
    alert("Algo ha fallado.");
  })
}
</script>